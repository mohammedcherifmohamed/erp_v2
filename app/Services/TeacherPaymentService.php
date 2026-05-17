<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\Enrollment;
use App\Models\Invoice;
use App\Models\TeacherContract;
use App\Models\TeacherWithdrawal;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TeacherPaymentService
{
    public function __construct(
        private readonly AuditService $auditService,
    ) {}

    public function calculateTeacherEarnings(User $teacher, ?string $fromDate = null, ?string $toDate = null): array
    {
        $contracts = TeacherContract::with(['course'])
            ->where('teacher_id', $teacher->id)
            ->active()
            ->get();

        $totalEarnings = 0;
        $breakdown = [];

        foreach ($contracts as $contract) {
            $earnings = $this->calculateContractEarnings($contract, $fromDate, $toDate);

            $totalEarnings += $earnings;
            $breakdown[] = [
                'contract' => $contract,
                'earnings' => $earnings,
            ];
        }

        return [
            'total' => $totalEarnings,
            'breakdown' => $breakdown,
            'wallet_balance' => $teacher->teacherProfile->wallet_balance,
            'pending_balance' => $teacher->teacherProfile->pending_balance,
        ];
    }

    public function calculateContractEarnings(TeacherContract $contract, ?string $fromDate = null, ?string $toDate = null): float
    {
        $course = $contract->course;
        if (!$course) return 0;

        $query = Attendance::where('course_id', $course->id)
            ->where('marked_by', $contract->teacher_id);

        if ($fromDate) $query->where('date', '>=', $fromDate);
        if ($toDate) $query->where('date', '<=', $toDate);

        return match ($contract->contract_type) {
            'per_session' => $query->count() * $contract->rate,
            'per_student' => $course->classe->enrolled_count * $contract->rate,
            'percentage' => Invoice::whereHas('student.enrollments', fn($q) => $q->where('course_id', $course->id)->approved())
                ->whereIn('status', ['paid', 'partial'])
                ->sum('paid_amount') * ($contract->rate / 100),
            'monthly' => $contract->rate,
            default => 0,
        };
    }

    public function processWithdrawal(User $teacher, float $amount, string $method, ?string $accountNumber = null): TeacherWithdrawal
    {
        $profile = $teacher->teacherProfile;

        if ($profile->wallet_balance < $amount) {
            throw new \RuntimeException('Insufficient wallet balance.');
        }

        return DB::transaction(function () use ($teacher, $profile, $amount, $method, $accountNumber) {
            $profile->decrement('wallet_balance', $amount);

            $withdrawal = TeacherWithdrawal::create([
                'teacher_id' => $teacher->id,
                'amount' => $amount,
                'status' => 'pending',
                'payment_method' => $method,
                'account_number' => $accountNumber,
            ]);

            $this->auditService->logCreate($withdrawal, $withdrawal->toArray());

            return $withdrawal;
        });
    }

    public function approveWithdrawal(TeacherWithdrawal $withdrawal): TeacherWithdrawal
    {
        return DB::transaction(function () use ($withdrawal) {
            $withdrawal->update([
                'status' => 'approved',
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);

            $this->auditService->logUpdate($withdrawal, ['status' => 'pending'], ['status' => 'approved']);

            return $withdrawal;
        });
    }

    public function completeWithdrawal(TeacherWithdrawal $withdrawal): TeacherWithdrawal
    {
        $withdrawal->update([
            'status' => 'completed',
            'processed_at' => now(),
        ]);

        return $withdrawal;
    }

    public function rejectWithdrawal(TeacherWithdrawal $withdrawal, string $reason): TeacherWithdrawal
    {
        return DB::transaction(function () use ($withdrawal, $reason) {
            $withdrawal->update([
                'status' => 'rejected',
                'notes' => $reason,
                'processed_by' => auth()->id(),
                'processed_at' => now(),
            ]);

            $withdrawal->teacher->teacherProfile->increment('wallet_balance', $withdrawal->amount);

            return $withdrawal;
        });
    }

    public function getPendingWithdrawals(int $perPage = 15)
    {
        return TeacherWithdrawal::with(['teacher.teacherProfile'])
            ->pending()
            ->latest()
            ->paginate($perPage);
    }
}
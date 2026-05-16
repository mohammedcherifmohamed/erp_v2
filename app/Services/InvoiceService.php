<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class InvoiceService
{
    public function __construct(
        private readonly AuditService $auditService,
    ) {}

    public function generateForEnrollment(Enrollment $enrollment): Invoice
    {
        $classe = $enrollment->classe;
        $student = $enrollment->student;
        $course = $enrollment->course;

        if ($course) {
            $totalAmount = $course->price ?? 0;
            $description = "Inscription au cours: {$course->name} ({$classe->name})";
        } else {
            $totalAmount = $classe->reduction_price ?? $classe->total_courses_price ?? $classe->price ?? 0;
            $description = "Forfait complet: {$classe->name} ({$classe->courses->count()} cours)";
        }

        $invoiceNumber = 'INV-' . date('Y') . '-' . Str::padLeft(Invoice::count() + 1, 6, '0');

        return DB::transaction(function () use ($classe, $student, $invoiceNumber, $enrollment, $totalAmount, $description) {
            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'student_id' => $student->id,
                'parent_id' => $student->studentProfile?->parent_id,
                'class_id' => $classe->id,
                'total_amount' => $totalAmount,
                'paid_amount' => 0,
                'remaining_amount' => $totalAmount,
                'status' => 'unpaid',
                'due_date' => now()->addDays(30),
                'description' => $description,
            ]);

            $this->auditService->logCreate($invoice, $invoice->toArray());

            return $invoice;
        });
    }

    public function recordPayment(Invoice $invoice, float $amount, ?string $method = null, ?string $notes = null): Payment
    {
        return DB::transaction(function () use ($invoice, $amount, $method, $notes) {
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'collected_by' => auth()->id(),
                'amount' => $amount,
                'payment_method' => $method,
                'notes' => $notes,
                'paid_at' => now(),
            ]);

            $newPaid = $invoice->paid_amount + $amount;
            $newRemaining = $invoice->netAmount() - $newPaid;

            $status = $newRemaining <= 0 ? 'paid' : ($newPaid > 0 ? 'partial' : 'unpaid');

            $invoice->update([
                'paid_amount' => $newPaid,
                'remaining_amount' => $newRemaining,
                'status' => $status,
            ]);

            $this->auditService->logCreate($payment, $payment->toArray());

            return $payment;
        });
    }

    public function getOverdueInvoices(int $perPage = 15)
    {
        return Invoice::with(['student.studentProfile'])
            ->overdue()
            ->latest()
            ->paginate($perPage);
    }

    public function getStudentInvoices(int $studentId, int $perPage = 15)
    {
        return Invoice::with(['payments', 'classe'])
            ->where('student_id', $studentId)
            ->latest()
            ->paginate($perPage);
    }

    public function markOverdue(): int
    {
        return Invoice::whereIn('status', ['unpaid', 'partial'])
            ->where('due_date', '<', now())
            ->update(['status' => 'overdue']);
    }
}
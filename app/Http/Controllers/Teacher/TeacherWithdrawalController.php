<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWithdrawalRequest;
use App\Models\TeacherWithdrawal;
use App\Services\TeacherPaymentService;

class TeacherWithdrawalController extends Controller
{
    public function __construct(
        private readonly TeacherPaymentService $paymentService,
    ) {}

    public function index()
    {
        $query = request('query');
        $withdrawals = TeacherWithdrawal::where('teacher_id', auth()->id())
            ->when($query, fn($q) => $q->where(function($sq) use ($query) {
                $sq->where('amount', 'like', "%{$query}%")
                  ->orWhere('method', 'like', "%{$query}%")
                  ->orWhere('status', 'like', "%{$query}%");
            }))
            ->latest()
            ->paginate(15);

        $walletBalance = auth()->user()->teacherProfile->wallet_balance ?? 0;
        $pendingBalance = auth()->user()->teacherProfile->pending_balance ?? 0;

        if (request()->ajax()) {
            return view('teacher-withdrawals._teacher_table', compact('withdrawals'))->render();
        }

        return view('teacher-withdrawals.teacher-index', compact('withdrawals', 'walletBalance', 'pendingBalance'));
    }

    public function store(StoreWithdrawalRequest $request)
    {
        try {
            $withdrawal = $this->paymentService->processWithdrawal(
                auth()->user(),
                $request->amount,
                $request->payment_method,
                $request->account_number
            );

            return redirect()->route('teacher.withdrawals.index')
                ->with('success', 'Withdrawal request submitted successfully.');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(TeacherWithdrawal $teacherWithdrawal)
    {
        return redirect()->route('teacher.withdrawals.index');
    }
}
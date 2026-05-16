<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeacherWithdrawal;
use App\Services\TeacherPaymentService;
use Illuminate\Http\Request;

class TeacherWithdrawalController extends Controller
{
    public function __construct(
        private readonly TeacherPaymentService $paymentService,
    ) {}

    public function index()
    {
        $withdrawals = TeacherWithdrawal::with(['teacher.teacherProfile', 'processor'])
            ->latest()
            ->paginate(15);

        return view('teacher-withdrawals.index', compact('withdrawals'));
    }

    public function show(TeacherWithdrawal $teacherWithdrawal)
    {
        $teacherWithdrawal->load(['teacher.teacherProfile', 'processor']);
        return view('teacher-withdrawals.show', compact('teacherWithdrawal'));
    }

    public function approve(TeacherWithdrawal $teacherWithdrawal)
    {
        $this->paymentService->approveWithdrawal($teacherWithdrawal);
        return back()->with('success', 'Retrait approuvé.');
    }

    public function complete(TeacherWithdrawal $teacherWithdrawal)
    {
        $this->paymentService->completeWithdrawal($teacherWithdrawal);
        return back()->with('success', 'Retrait marqué comme terminé.');
    }

    public function reject(TeacherWithdrawal $teacherWithdrawal, Request $request)
    {
        $data = $request->validate(['reason' => ['required', 'string', 'max:500']]);
        $this->paymentService->rejectWithdrawal($teacherWithdrawal, $data['reason']);
        return back()->with('success', 'Retrait rejeté.');
    }
}
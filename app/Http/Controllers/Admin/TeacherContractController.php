<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeacherContractRequest;
use App\Models\Classe;
use App\Models\Course;
use App\Models\TeacherContract;
use App\Models\User;
use App\Services\AuditService;

class TeacherContractController extends Controller
{
    public function __construct(
        private readonly AuditService $auditService,
    ) {}

    public function index()
    {
        $query = request('query');
        $contracts = TeacherContract::with(['teacher.teacherProfile', 'course', 'classe'])
            ->when($query, fn($q) => $q->where('contract_type', 'like', "%{$query}%"))
            ->latest()
            ->paginate(15);

        if (request()->ajax()) {
            return view('teacher-contracts._table', compact('contracts'))->render();
        }

        return view('teacher-contracts.index', compact('contracts'));
    }

    public function create()
    {
        $teachers = User::byRole('teacher')->get();
        $courses = Course::with('classe')->active()->get();
        $classes = Classe::active()->get();
        return view('teacher-contracts.create', compact('teachers', 'courses', 'classes'));
    }

    public function store(StoreTeacherContractRequest $request)
    {
        $contract = TeacherContract::create($request->validated());
        $this->auditService->logCreate($contract, $contract->toArray());

        return redirect()->route('admin.teacher-contracts.index')
            ->with('success', 'Contract created successfully.');
    }

    public function show(TeacherContract $teacherContract)
    {
        $teacherContract->load(['teacher.teacherProfile', 'course', 'classe']);
        return view('teacher-contracts.show', compact('teacherContract'));
    }

    public function edit(TeacherContract $teacherContract)
    {
        $teachers = User::byRole('teacher')->get();
        $courses = Course::active()->get();
        $classes = Classe::active()->get();
        return view('teacher-contracts.edit', compact('teacherContract', 'teachers', 'courses', 'classes'));
    }

    public function update(StoreTeacherContractRequest $request, TeacherContract $teacherContract)
    {
        $old = $teacherContract->toArray();
        $teacherContract->update($request->validated());
        $this->auditService->logUpdate($teacherContract, $old, $teacherContract->toArray());

        return redirect()->route('admin.teacher-contracts.index')
            ->with('success', 'Contract updated successfully.');
    }

    public function destroy(TeacherContract $teacherContract)
    {
        $teacherContract->delete();
        return redirect()->route('admin.teacher-contracts.index')
            ->with('success', 'Contract deleted.');
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;
use App\Models\Invoice;
use App\Models\User;
use App\Services\InvoiceService;

class InvoiceController extends Controller
{
    public function __construct(
        private readonly InvoiceService $invoiceService,
    ) {}

    public function index()
    {
        $query = request('query');
        $invoices = Invoice::with(['student.studentProfile', 'classe'])
            ->when($query, fn($q) => $q->where('invoice_number', 'like', "%{$query}%"))
            ->latest()
            ->paginate(15);

        if (request()->ajax()) {
            return view('invoices._table', compact('invoices'))->render();
        }

        return view('invoices.index', compact('invoices'));
    }

    public function show(Invoice $invoice)
    {
        $invoice->load(['student.studentProfile', 'parent.parentProfile', 'classe', 'payments.collector']);
        return view('invoices.show', compact('invoice'));
    }

    public function create()
    {
        $students = User::byRole('student')->with('studentProfile')->get();
        return view('invoices.create', compact('students'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $data = $request->validate([
            'student_id' => ['required', 'exists:users,id'],
            'total_amount' => ['required', 'numeric', 'min:0'],
            'due_date' => ['required', 'date', 'after_or_equal:today'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $student = User::findOrFail($data['student_id']);
        $invoiceNumber = 'INV-' . date('Y') . '-' . str_pad(Invoice::count() + 1, 6, '0', STR_PAD_LEFT);

        $invoice = Invoice::create([
            'invoice_number' => $invoiceNumber,
            'student_id' => $student->id,
            'parent_id' => $student->studentProfile?->parent_id,
            'total_amount' => $data['total_amount'],
            'paid_amount' => 0,
            'remaining_amount' => $data['total_amount'],
            'status' => 'unpaid',
            'due_date' => $data['due_date'],
            'description' => $data['description'] ?? 'Tuition fee',
        ]);

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'Facture créée avec succès.');
    }

    public function recordPayment(Invoice $invoice, StorePaymentRequest $request)
    {
        $payment = $this->invoiceService->recordPayment(
            $invoice,
            $request->amount,
            $request->payment_method,
            $request->notes
        );

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'Paiement enregistré avec succès.');
    }

    public function applyReduction(\Illuminate\Http\Request $request, Invoice $invoice)
    {
        $data = $request->validate([
            'reduction_amount' => ['required', 'numeric', 'min:0', 'max:' . $invoice->total_amount],
            'reduction_reason' => ['nullable', 'string', 'max:500'],
        ]);

        $newRemaining = ($invoice->total_amount - $data['reduction_amount']) - $invoice->paid_amount;
        $status = $newRemaining <= 0 ? 'paid' : ($invoice->paid_amount > 0 ? 'partial' : 'unpaid');

        $invoice->update([
            'reduction_amount' => $data['reduction_amount'],
            'reduction_reason' => $data['reduction_reason'] ?? null,
            'remaining_amount' => max(0, $newRemaining),
            'status' => $status,
        ]);

        return redirect()->route('admin.invoices.show', $invoice)
            ->with('success', 'Réduction appliquée avec succès.');
    }

    public function overdue()
    {
        $this->invoiceService->markOverdue();
        $query = request('query');

        $invoices = Invoice::with(['student.studentProfile', 'classe'])
            ->overdue()
            ->when($query, fn($q) => $q->where('invoice_number', 'like', "%{$query}%"))
            ->latest()
            ->paginate(15);

        if (request()->ajax()) {
            return view('invoices._overdue_table', compact('invoices'))->render();
        }

        return view('invoices.overdue', compact('invoices'));
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('admin.invoices.index')
            ->with('success', 'Facture supprimée.');
    }
}
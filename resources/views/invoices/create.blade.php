@extends('layouts.app')

@section('title', 'Créer une facture')
@section('page-title', 'Créer une facture')
@section('page-subtitle', 'Générer une nouvelle facture')

@section('content')
<div class="max-w-2xl">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.invoices.store') }}" class="space-y-6">
                @csrf
                <div>
                    <label for="student_id" class="label">Étudiant</label>
                    <select id="student_id" name="student_id" required class="input @error('student_id') input-error @enderror">
                        <option value="">Sélectionner un étudiant...</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                {{ $student->full_name }} ({{ $student->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('student_id') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="total_amount" class="label">Montant total (MAD)</label>
                    <input id="total_amount" type="number" step="0.01" name="total_amount" value="{{ old('total_amount') }}" required class="input @error('total_amount') input-error @enderror" placeholder="0.00">
                    @error('total_amount') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="due_date" class="label">Date d'échéance</label>
                    <input id="due_date" type="date" name="due_date" value="{{ old('due_date', now()->addDays(30)->format('Y-m-d')) }}" required class="input @error('due_date') input-error @enderror">
                    @error('due_date') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="description" class="label">Description</label>
                    <textarea id="description" name="description" rows="3" class="input" placeholder="Description de la facture">{{ old('description') }}</textarea>
                </div>
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('admin.invoices.index') }}" class="btn-outline">Annuler</a>
                    <button type="submit" class="btn-primary">Créer la facture</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
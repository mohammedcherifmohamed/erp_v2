@extends('layouts.app')

@section('title', 'Créer un quiz')
@section('page-title', 'Créer un quiz')
@section('page-subtitle', 'Créer un nouveau quiz avec des questions')

@push('styles')
<style>
    .question-item { border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem; background: #f9fafb; }
</style>
@endpush

@section('content')
<div class="max-w-3xl">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('teacher.quizzes.store') }}" class="space-y-6">
                @csrf
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="title" class="label">Titre <span class="text-danger-500">*</span></label>
                        <input id="title" type="text" name="title" value="{{ old('title') }}" required class="input @error('title') input-error @enderror">
                        @error('title') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="course_id" class="label">Cours <span class="text-danger-500">*</span></label>
                        <select id="course_id" name="course_id" required class="input @error('course_id') input-error @enderror">
                            <option value="">Sélectionner un cours</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>{{ $course->name }} - {{ $course->class->name ?? '' }}</option>
                            @endforeach
                        </select>
                        @error('course_id') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label for="description" class="label">Description</label>
                    <textarea id="description" name="description" rows="2" class="input">{{ old('description') }}</textarea>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label for="passing_points" class="label">Points de réussite</label>
                        <input id="passing_points" type="number" name="passing_points" value="{{ old('passing_points', 0) }}" class="input" min="0">
                    </div>
                    <div>
                        <label for="time_limit" class="label">Limite de temps (minutes)</label>
                        <input id="time_limit" type="number" name="time_limit" value="{{ old('time_limit', 30) }}" class="input" min="0">
                    </div>
                    <div>
                        <label for="total_points" class="label">Points totaux</label>
                        <input id="total_points" type="number" name="total_points" value="{{ old('total_points', 0) }}" class="input" readonly>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="available_from" class="label">Disponible du</label>
                        <input id="available_from" type="datetime-local" name="available_from" value="{{ old('available_from') }}" class="input">
                    </div>
                    <div>
                        <label for="available_until" class="label">Disponible jusqu'au</label>
                        <input id="available_until" type="datetime-local" name="available_until" value="{{ old('available_until') }}" class="input">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-3">
                        <p class="font-medium text-gray-900">Questions</p>
                        <button type="button" onclick="addQuestion()" class="btn-sm btn-secondary">+ Ajouter une question</button>
                    </div>
                    <div id="questions-container">
                        @if(old('questions'))
                            @foreach(old('questions') as $index => $q)
                                @include('quizzes._question_fields', ['index' => $index, 'q' => $q])
                            @endforeach
                        @endif
                    </div>
                    @error('questions') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('teacher.quizzes.index') }}" class="btn-outline">Annuler</a>
                    <button type="submit" class="btn-primary">Créer le quiz</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let questionIndex = {{ old('questions') ? count(old('questions')) : 0 }};
    function addQuestion() {
        const html = `@include('quizzes._question_fields', ['index' => 'INDEX', 'q' => null])`.replace(/INDEX/g, questionIndex);
        document.getElementById('questions-container').insertAdjacentHTML('beforeend', html);
        questionIndex++;
    }
    function removeQuestion(btn) {
        btn.closest('.question-item').remove();
        updateTotalPoints();
    }
    function updateTotalPoints() {
        let total = 0;
        document.querySelectorAll('[name$="[points]"]').forEach(function(input) {
            total += parseInt(input.value) || 0;
        });
        document.getElementById('total_points').value = total;
    }
    document.addEventListener('change', function(e) {
        if (e.target.matches('[name$="[points]"]')) updateTotalPoints();
        if (e.target.matches('[name$="[type]"]')) {
            const opts = e.target.closest('.question-item').querySelector('.options-field');
            opts.style.display = e.target.value === 'mcq' ? 'block' : 'none';
        }
    });
</script>
@endpush
@endsection

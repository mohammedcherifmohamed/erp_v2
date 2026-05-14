@extends('layouts.app')

@section('title', 'Edit Quiz')
@section('page-title', 'Edit Quiz')
@section('page-subtitle', $quiz->title)

@push('styles')
<style>
    .question-item { border: 1px solid #e5e7eb; border-radius: 0.5rem; padding: 1rem; margin-bottom: 1rem; background: #f9fafb; }
</style>
@endpush

@section('content')
<div class="max-w-3xl">
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('teacher.quizzes.update', $quiz) }}" class="space-y-6">
                @csrf @method('PUT')
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="title" class="label">Title <span class="text-danger-500">*</span></label>
                        <input id="title" type="text" name="title" value="{{ old('title', $quiz->title) }}" required class="input @error('title') input-error @enderror">
                        @error('title') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label for="course_id" class="label">Course <span class="text-danger-500">*</span></label>
                        <select id="course_id" name="course_id" required class="input @error('course_id') input-error @enderror">
                            <option value="">Select Course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id', $quiz->course_id) == $course->id ? 'selected' : '' }}>{{ $course->name }} - {{ $course->class->name ?? '' }}</option>
                            @endforeach
                        </select>
                        @error('course_id') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div>
                    <label for="description" class="label">Description</label>
                    <textarea id="description" name="description" rows="2" class="input">{{ old('description', $quiz->description) }}</textarea>
                </div>
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label for="passing_points" class="label">Passing Points</label>
                        <input id="passing_points" type="number" name="passing_points" value="{{ old('passing_points', $quiz->passing_points) }}" class="input" min="0">
                    </div>
                    <div>
                        <label for="time_limit" class="label">Time Limit (minutes)</label>
                        <input id="time_limit" type="number" name="time_limit" value="{{ old('time_limit', $quiz->time_limit) }}" class="input" min="0">
                    </div>
                    <div>
                        <label for="total_points" class="label">Total Points</label>
                        <input id="total_points" type="number" name="total_points" value="{{ old('total_points', $quiz->total_points) }}" class="input" readonly>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="available_from" class="label">Available From</label>
                        <input id="available_from" type="datetime-local" name="available_from" value="{{ old('available_from', $quiz->available_from ? $quiz->available_from->format('Y-m-d\TH:i') : '') }}" class="input">
                    </div>
                    <div>
                        <label for="available_until" class="label">Available Until</label>
                        <input id="available_until" type="datetime-local" name="available_until" value="{{ old('available_until', $quiz->available_until ? $quiz->available_until->format('Y-m-d\TH:i') : '') }}" class="input">
                    </div>
                </div>

                <div>
                    <div class="flex items-center justify-between mb-3">
                        <p class="font-medium text-gray-900">Questions</p>
                        <button type="button" onclick="addQuestion()" class="btn-sm btn-secondary">+ Add Question</button>
                    </div>
                    <div id="questions-container">
                        @if(old('questions'))
                            @foreach(old('questions') as $index => $q)
                                @include('quizzes._question_fields', ['index' => $index, 'q' => $q])
                            @endforeach
                        @else
                            @foreach($quiz->questions as $index => $q)
                                @include('quizzes._question_fields', ['index' => $index, 'q' => $q->toArray()])
                            @endforeach
                        @endif
                    </div>
                    @error('questions') <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="is_published" class="label">Status</label>
                    <select id="is_published" name="is_published" class="input">
                        <option value="1" {{ old('is_published', $quiz->is_published) ? 'selected' : '' }}>Published</option>
                        <option value="0" {{ old('is_published', $quiz->is_published) ? '' : 'selected' }}>Draft</option>
                    </select>
                </div>

                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('teacher.quizzes.index') }}" class="btn-outline">Cancel</a>
                    <button type="submit" class="btn-primary">Update Quiz</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let questionIndex = {{ old('questions') ? count(old('questions')) : $quiz->questions->count() }};
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

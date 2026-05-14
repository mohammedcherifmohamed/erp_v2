@extends('layouts.app')

@section('title', 'Correct Quiz')
@section('page-title', 'Correct: ' . $quiz->title)
@section('page-subtitle', 'Grade student submissions')

@section('content')
<div class="space-y-6">
    @forelse($quiz->submissions as $submission)
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">{{ $submission->student->full_name ?? 'Unknown' }}</h3>
                <span class="badge-{{ $submission->is_graded ? 'success' : 'warning' }}">
                    {{ $submission->is_graded ? 'Graded' : 'Not Graded' }}
                </span>
            </div>
            <div class="card-body">
                @if($submission->is_graded)
                    <div class="mb-4 p-3 bg-green-50 rounded-lg">
                        <p class="font-medium">Score: {{ $submission->score }} / {{ $submission->total }} ({{ $submission->total > 0 ? round(($submission->score / $submission->total) * 100) : 0 }}%)</p>
                    </div>
                @else
                    <form method="POST" action="{{ route('teacher.quizzes.submit-correction', [$quiz, $submission]) }}" class="space-y-4">
                        @csrf
                        @foreach($submission->answers as $answer)
                            <div class="border rounded-lg p-3 @error("answers.{$answer->question_id}.score") border-danger-300 @enderror">
                                <p class="font-medium text-gray-900">{{ $answer->question->text ?? 'Question' }}</p>
                                <p class="text-sm text-gray-500 mt-1">
                                    <span class="font-medium">Answer:</span> {{ $answer->answer }}
                                </p>
                                @if($answer->question->type === 'text')
                                    <div class="mt-2">
                                        <label class="text-sm text-gray-600">Score (max {{ $answer->question->points }} pts)</label>
                                        <input type="number" name="answers[{{ $answer->question_id }}][score]" class="input w-32" min="0" max="{{ $answer->question->points }}" value="{{ $answer->score ?? $answer->question->points }}">
                                        @error("answers.{$answer->question_id}.score") <p class="text-sm text-danger-600 mt-1">{{ $message }}</p> @enderror
                                    </div>
                                @endif
                            </div>
                        @endforeach
                        <div class="flex justify-end">
                            <button type="submit" class="btn-primary">Submit Grades</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center text-gray-500 py-8">No submissions to correct</div>
        </div>
    @endforelse
    <a href="{{ route('teacher.quizzes.show', $quiz) }}" class="btn-outline">Back to Quiz</a>
</div>
@endsection

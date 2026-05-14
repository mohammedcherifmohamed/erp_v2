@extends('layouts.app')

@section('title', 'Quiz Results')
@section('page-title', 'Quiz Results')
@section('page-subtitle', $quiz->title)

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <div class="grid grid-cols-3 gap-4">
        <div class="card">
            <div class="card-body text-center">
                <p class="text-3xl font-bold text-primary-600">{{ $submission->score }}</p>
                <p class="text-sm text-gray-500">Your Score</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <p class="text-3xl font-bold text-gray-700">{{ $submission->total }}</p>
                <p class="text-sm text-gray-500">Total Points</p>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <p class="text-3xl font-bold {{ $submission->passed ? 'text-success-600' : 'text-danger-600' }}">
                    {{ $submission->total > 0 ? round(($submission->score / $submission->total) * 100) : 0 }}%
                </p>
                <p class="text-sm text-gray-500">Percentage</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="font-semibold text-gray-900">Result</h3>
        </div>
        <div class="card-body text-center">
            <span class="text-2xl font-bold {{ $submission->passed ? 'text-success-600' : 'text-danger-600' }}">
                {{ $submission->passed ? 'PASSED' : 'FAILED' }}
            </span>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="font-semibold text-gray-900">Detailed Answers</h3>
        </div>
        <div class="card-body space-y-4">
            @foreach($submission->answers as $answer)
                <div class="border rounded-lg p-4 {{ $answer->is_correct ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200' }}">
                    <p class="font-medium text-gray-900">{{ $answer->question->text ?? 'Question' }}</p>
                    <div class="mt-2 grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Your Answer</p>
                            <p class="text-sm font-medium">{{ $answer->answer }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Correct Answer</p>
                            <p class="text-sm font-medium text-success-600">{{ $answer->question->correct_answer ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="mt-2 flex items-center gap-2">
                        <span class="text-sm">Points: {{ $answer->score ?? 0 }} / {{ $answer->question->points }}</span>
                        <span class="badge-{{ $answer->is_correct ? 'success' : 'danger' }}">
                            {{ $answer->is_correct ? 'Correct' : 'Incorrect' }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', $quiz->title)
@section('page-title', $quiz->title)
@section('page-subtitle', 'Take quiz')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="card mb-4">
        <div class="card-body text-center">
            <p class="text-sm text-gray-500">{{ $quiz->course->name ?? '' }}</p>
            <p class="text-sm text-gray-500 mt-1">
                {{ $quiz->questions_count }} Questions | {{ $quiz->total_points }} Total Points
                @if($quiz->time_limit) | Time Limit: {{ $quiz->time_limit }} min @endif
            </p>
        </div>
    </div>

    <form method="POST" action="{{ route('student.quizzes.submit', $quiz) }}" class="space-y-4">
        @csrf
        @foreach($quiz->questions as $index => $question)
            <div class="card">
                <div class="card-body">
                    <p class="font-medium text-gray-900 mb-2">
                        {{ $index + 1 }}. {{ $question->text }}
                        <span class="text-sm text-gray-400 font-normal">({{ $question->points }} pts)</span>
                    </p>

                    @if($question->type === 'true_false')
                        <div class="space-y-1">
                            <label class="inline-flex items-center gap-2">
                                <input type="radio" name="answers[{{ $question->id }}]" value="true" class="rounded border-gray-300 text-primary-600">
                                <span>True</span>
                            </label>
                            <label class="inline-flex items-center gap-2 ml-6">
                                <input type="radio" name="answers[{{ $question->id }}]" value="false" class="rounded border-gray-300 text-primary-600">
                                <span>False</span>
                            </label>
                        </div>
                    @elseif($question->type === 'mcq' && $question->options)
                        @php $options = is_array($question->options) ? $question->options : explode("\n", $question->options); @endphp
                        <div class="space-y-1">
                            @foreach($options as $option)
                                @if(trim($option))
                                    <label class="inline-flex items-center gap-2">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ trim($option) }}" class="rounded border-gray-300 text-primary-600">
                                        <span>{{ trim($option) }}</span>
                                    </label>
                                @endif
                            @endforeach
                        </div>
                    @else
                        <textarea name="answers[{{ $question->id }}]" rows="3" class="input w-full" placeholder="Your answer..."></textarea>
                    @endif
                </div>
            </div>
        @endforeach

        <div class="card">
            <div class="card-body flex items-center justify-between">
                <p class="text-sm text-gray-500">Make sure you've answered all questions before submitting.</p>
                <button type="submit" class="btn-primary" onclick="return confirm('Submit your answers?')">Submit Quiz</button>
            </div>
        </div>
    </form>
</div>
@endsection

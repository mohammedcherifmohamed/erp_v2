@extends('layouts.app')

@section('title', 'Corriger le quiz')
@section('page-title', 'Corriger : ' . $quiz->title)
@section('page-subtitle', 'Noter les soumissions des étudiants')

@section('content')
<div class="space-y-6">
    @forelse($quiz->submissions as $submission)
        <div class="card">
            <div class="card-header flex items-center justify-between">
                <h3 class="font-semibold text-gray-900">{{ $submission->student->full_name ?? 'Inconnu' }}</h3>
                <span class="badge-{{ $submission->is_graded ? 'success' : 'warning' }}">
                    {{ $submission->is_graded ? 'Noté' : 'Non noté' }}
                </span>
            </div>
            <div class="card-body">
                @if($submission->is_graded)
                    <div class="mb-4 p-3 bg-green-50 rounded-lg">
                        <p class="font-medium">Score : {{ $submission->score }} / {{ $submission->total }} ({{ $submission->total > 0 ? round(($submission->score / $submission->total) * 100) : 0 }}%)</p>
                    </div>
                @else
                    <form method="POST" action="{{ route('teacher.quizzes.submit-correction', [$quiz, $submission]) }}" class="space-y-4">
                        @csrf
                        @foreach($submission->answers as $answer)
                            <div class="border rounded-lg p-3 @error("answers.{$answer->question_id}.score") border-danger-300 @enderror">
                                <p class="font-medium text-gray-900">{{ $answer->question->text ?? 'Question' }}</p>
                                <p class="text-sm text-gray-500 mt-1">
                                    <span class="font-medium">Réponse :</span> {{ $answer->answer }}
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
                                <button type="submit" class="btn-primary">Soumettre les notes</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body text-center text-gray-500 py-8">Aucune soumission à corriger</div>
        </div>
    @endforelse
    <a href="{{ route('teacher.quizzes.show', $quiz) }}" class="btn-outline">Retour au quiz</a>
</div>
@endsection

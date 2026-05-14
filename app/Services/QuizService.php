<?php

namespace App\Services;

use App\Models\Quiz;
use App\Models\QuizQuestion;
use App\Models\QuizResult;
use Illuminate\Support\Facades\DB;

class QuizService
{
    public function __construct(
        private readonly AuditService $auditService,
    ) {}

    public function createQuiz(array $data): Quiz
    {
        return DB::transaction(function () use ($data) {
            $quiz = Quiz::create([
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'course_id' => $data['course_id'],
                'class_id' => $data['class_id'],
                'teacher_id' => auth()->id(),
                'total_points' => 0,
                'passing_points' => $data['passing_points'] ?? 0,
                'time_limit_minutes' => $data['time_limit_minutes'] ?? null,
                'available_from' => $data['available_from'] ?? null,
                'available_until' => $data['available_until'] ?? null,
                'is_published' => $data['is_published'] ?? false,
            ]);

            $totalPoints = 0;
            if (isset($data['questions'])) {
                foreach ($data['questions'] as $i => $qData) {
                    $question = $quiz->questions()->create([
                        'question' => $qData['question'],
                        'type' => $qData['type'],
                        'options' => $qData['options'] ?? null,
                        'correct_answer' => $qData['correct_answer'] ?? null,
                        'points' => $qData['points'] ?? 1,
                        'sort_order' => $i + 1,
                    ]);
                    $totalPoints += $question->points;
                }
            }

            $quiz->update(['total_points' => $totalPoints]);

            $this->auditService->logCreate($quiz, $quiz->toArray());

            return $quiz->load(['questions', 'course']);
        });
    }

    public function submitQuiz(Quiz $quiz, array $answers): QuizResult
    {
        $studentId = auth()->id();

        $existing = QuizResult::where('quiz_id', $quiz->id)
            ->where('student_id', $studentId)
            ->first();

        if ($existing) {
            throw new \RuntimeException('You have already submitted this quiz.');
        }

        return DB::transaction(function () use ($quiz, $answers, $studentId) {
            $questions = $quiz->questions;
            $score = 0;
            $autoCorrected = true;

            foreach ($questions as $question) {
                $userAnswer = $answers[$question->id] ?? null;

                if ($question->type === 'text') {
                    $autoCorrected = false;
                    continue;
                }

                if ($userAnswer === $question->correct_answer) {
                    $score += $question->points;
                }
            }

            $result = QuizResult::create([
                'quiz_id' => $quiz->id,
                'student_id' => $studentId,
                'score' => $score,
                'total_points' => $quiz->total_points,
                'answers' => $answers,
                'started_at' => now(),
                'submitted_at' => now(),
                'is_auto_corrected' => $autoCorrected,
            ]);

            $this->auditService->logCreate($result, $result->toArray());

            return $result;
        });
    }

    public function correctTextAnswers(QuizResult $result, array $manualScores): QuizResult
    {
        return DB::transaction(function () use ($result, $manualScores) {
            $questions = $result->quiz->questions()->where('type', 'text')->get();
            $textScore = 0;

            foreach ($questions as $question) {
                if (isset($manualScores[$question->id])) {
                    $textScore += $manualScores[$question->id];
                }
            }

            $result->update([
                'score' => $result->score + $textScore,
                'is_auto_corrected' => true,
                'corrected_at' => now(),
                'corrected_by' => auth()->id(),
            ]);

            return $result;
        });
    }

    public function publishQuiz(Quiz $quiz): Quiz
    {
        $quiz->update(['is_published' => true]);
        $this->auditService->logUpdate($quiz, ['is_published' => false], ['is_published' => true]);
        return $quiz;
    }

    public function getQuizzesByTeacher(int $teacherId, int $perPage = 15)
    {
        return Quiz::with(['course', 'classe', 'questions'])
            ->forTeacher($teacherId)
            ->latest()
            ->paginate($perPage);
    }

    public function getQuizzesByClass(int $classId, int $perPage = 15)
    {
        return Quiz::with(['course', 'teacher'])
            ->where('class_id', $classId)
            ->published()
            ->latest()
            ->paginate($perPage);
    }

    public function getQuizResults(int $quizId, int $perPage = 15)
    {
        return QuizResult::with(['student.studentProfile'])
            ->where('quiz_id', $quizId)
            ->latest()
            ->paginate($perPage);
    }
}
<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnswerRequest;
use App\Models\Enrollment;
use App\Models\Invoice;
use App\Models\Quiz;
use App\Models\QuizResult;
use App\Models\Schedule;
use App\Services\QuizService;

class StudentController extends Controller
{
    public function __construct(
        private readonly QuizService $quizService,
    ) {}

    public function quizzes()
    {
        $classIds = Enrollment::where('student_id', auth()->id())
            ->approved()
            ->pluck('class_id');

        $quizzes = Quiz::with(['course', 'teacher'])
            ->whereIn('class_id', $classIds)
            ->published()
            ->latest()
            ->paginate(15);

        return view('quizzes.student-index', compact('quizzes'));
    }

    public function takeQuiz(Quiz $quiz)
    {
        if (!$quiz->is_published) abort(404);

        $classIds = Enrollment::where('student_id', auth()->id())
            ->approved()
            ->pluck('class_id');

        if (!$classIds->contains($quiz->class_id)) abort(403);

        $existing = QuizResult::where('quiz_id', $quiz->id)
            ->where('student_id', auth()->id())
            ->first();

        if ($existing) {
            return redirect()->route('student.quizzes.results', $quiz)
                ->with('info', 'You have already completed this quiz.');
        }

        $quiz->load('questions');

        return view('quizzes.take', compact('quiz'));
    }

    public function submitQuiz(Quiz $quiz, StoreAnswerRequest $request)
    {
        try {
            $result = $this->quizService->submitQuiz($quiz, $request->answers);

            return redirect()->route('student.quizzes.results', $quiz)
                ->with('success', 'Quiz submitted successfully!');
        } catch (\RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function quizResults(Quiz $quiz)
    {
        $result = QuizResult::where('quiz_id', $quiz->id)
            ->where('student_id', auth()->id())
            ->firstOrFail();

        return view('quizzes.results', compact('quiz', 'result'));
    }

    public function schedule()
    {
        $classIds = Enrollment::where('student_id', auth()->id())
            ->approved()
            ->pluck('class_id');

        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];

        $schedules = Schedule::with(['course', 'teacher'])
            ->whereIn('class_id', $classIds)
            ->where('is_active', true)
            ->get()
            ->groupBy('day_of_week');

        $week = collect();
        foreach ($days as $day) {
            $week[$day] = $schedules->get($day, collect());
        }

        return view('schedules.student', compact('week'));
    }

    public function invoices()
    {
        $invoices = Invoice::with(['payments', 'classe'])
            ->where('student_id', auth()->id())
            ->latest()
            ->paginate(15);

        return view('invoices.student-index', compact('invoices'));
    }

    public function invoiceShow(Invoice $invoice)
    {
        if ($invoice->student_id !== auth()->id()) abort(403);
        $invoice->load(['payments', 'classe']);
        return view('invoices.student-show', compact('invoice'));
    }
}
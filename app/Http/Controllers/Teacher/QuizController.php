<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuizRequest;
use App\Models\Course;
use App\Models\Quiz;
use App\Services\QuizService;

class QuizController extends Controller
{
    public function __construct(
        private readonly QuizService $quizService,
    ) {}

    public function index()
    {
        $query = request('query');

        $quizzes = Quiz::with(['course', 'classe'])
            ->forTeacher(auth()->id())
            ->when($query, fn($q) => $q->where('title', 'like', "%{$query}%"))
            ->latest()
            ->paginate(15);

        if (request()->ajax()) {
            return view('quizzes._table', compact('quizzes'))->render();
        }

        return view('quizzes.index', compact('quizzes'));
    }

    public function create()
    {
        $courses = Course::with('classe')
            ->where('teacher_id', auth()->id())
            ->active()
            ->get();

        return view('quizzes.create', compact('courses'));
    }

    public function store(StoreQuizRequest $request)
    {
        $quiz = $this->quizService->createQuiz($request->validated());

        return redirect()->route('teacher.quizzes.show', $quiz)
            ->with('success', 'Quiz created successfully.');
    }

    public function show(Quiz $quiz)
    {
        if ($quiz->teacher_id !== auth()->id()) abort(403);

        $quiz->load(['course', 'classe', 'questions', 'results.student.studentProfile']);

        return view('quizzes.show', compact('quiz'));
    }

    public function edit(Quiz $quiz)
    {
        if ($quiz->teacher_id !== auth()->id()) abort(403);

        $courses = Course::where('teacher_id', auth()->id())->active()->get();

        return view('quizzes.edit', compact('quiz', 'courses'));
    }

    public function update(StoreQuizRequest $request, Quiz $quiz)
    {
        if ($quiz->teacher_id !== auth()->id()) abort(403);

        $quiz->update($request->validated());

        return redirect()->route('teacher.quizzes.show', $quiz)
            ->with('success', 'Quiz updated successfully.');
    }

    public function publish(Quiz $quiz)
    {
        if ($quiz->teacher_id !== auth()->id()) abort(403);

        $this->quizService->publishQuiz($quiz);

        return back()->with('success', 'Quiz published successfully.');
    }

    public function destroy(Quiz $quiz)
    {
        if ($quiz->teacher_id !== auth()->id()) abort(403);

        $quiz->delete();

        return redirect()->route('teacher.quizzes.index')
            ->with('success', 'Quiz deleted.');
    }

    public function correct(Quiz $quiz)
    {
        if ($quiz->teacher_id !== auth()->id()) abort(403);

        $results = $this->quizService->getQuizResults($quiz->id);

        return view('quizzes.correct', compact('quiz', 'results'));
    }

    public function submitCorrection(Quiz $quiz, \Illuminate\Http\Request $request)
    {
        if ($quiz->teacher_id !== auth()->id()) abort(403);

        $data = $request->validate([
            'results' => ['required', 'array'],
            'results.*.id' => ['required', 'exists:quiz_results,id'],
            'results.*.score' => ['required', 'integer', 'min:0'],
            'results.*.feedback' => ['nullable', 'string', 'max:1000'],
        ]);

        foreach ($data['results'] as $resultData) {
            $result = \App\Models\QuizResult::findOrFail($resultData['id']);

            if (!$result->is_auto_corrected) {
                $this->quizService->correctTextAnswers($result, $resultData);

                if (isset($resultData['feedback'])) {
                    $result->update(['feedback' => $resultData['feedback']]);
                }
            }
        }

        return redirect()->route('teacher.quizzes.show', $quiz)
            ->with('success', 'Corrections submitted successfully.');
    }
}
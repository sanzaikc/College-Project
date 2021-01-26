<?php

namespace App\Http\Controllers;

use App\Events\QuestionChanged;
use App\Events\QuizEnded;
use App\Question;
use App\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response(['quizzes' => Quiz::with(['questions', 'questions.category', 'questions.answer', 'questions.options', 'players', 'players.score'])->where('user_id', auth()->id())->get()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'        => 'required',
            'description' => 'required',
        ]);
        $path = null;
        if ($request->hasFile('image')) {
            $path = Storage::disk('public')->putFile('quizzes', $request->file('image'));
        }

        $quiz = auth()->user()->quizzes()->create(array_merge($data, [
            'image' => $path,
        ]));

        return response(['quiz' => $quiz]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quiz $quiz)
    {
        $path = $quiz->image;
        if ($request->hasFile('image')) {
            if ($quiz->image) {
                Storage::disk('public')->delete($quiz->image);
            }
            $path = Storage::disk('public')->putFile('quizzes', $request->file('image'));
        }

        $quiz->touch();
        $quiz->update(array_merge(
            $request->all(),
            ['image' => $path, 'user_id' => auth()->user()->id]
        ));

        if ($request->current_question) {
            $currentQuestion = Question::with('options', 'answer', 'category')->whereId($request->current_question)->first();
            event(new QuestionChanged($currentQuestion, $quiz));
        }

        return response(['quiz' => $quiz->fresh()]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $quiz = Quiz::find($id);
        if ($quiz) {
            $quiz->delete();
            return response(['message' => 'The quiz has been deleted.', 'quiz' => $quiz]);
        }
        return response(['message' => 'Quiz not found'], 404);
    }

    public function end(Quiz $quiz)
    {
        $endedQuiz = Quiz::with(['players', 'players.score'])->where('id', $quiz->id)->first();
        event(new QuizEnded($endedQuiz));
        $quiz->update([
            'pin'              => null,
            'current_question' => null,
        ]);
        $quiz->players()->delete();
        return response(['quiz' => $quiz]);
    }

    public function getQuizDetails(Request $request, $quiz)
    {
        $quiz            = Quiz::with(['players', 'players.score'])->where('id', $quiz)->first();
        $currentQuestion = Question::with(['options', 'answer', 'category'])->where('id', $quiz->current_question)->first();

        $quiz['current_question'] = $currentQuestion;
        return response(['quiz' => $quiz]);
    }
}

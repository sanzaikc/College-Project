<?php

namespace App\Http\Controllers;

use App\Answer;
use App\Option;
use App\Question;
use App\Quiz;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller {
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    $questions = Question::with(['options', 'answer'])->where('user_id', auth()->user()->id)->get();
    return response(['questions' => $questions]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    $data = $request->validate([
      'body' => 'required|unique:questions',
      'category_id' => 'required',
    ]);

    $path = null;
    if ($request->hasFile('image')) {
      $path = Storage::disk('public')->putFile('questions', $request->file('image'));
    }

    $question = auth()->user()->questions()->create(array_merge($data, [
      'image' => $path,
    ]));

    $shuffledOptions = collect($request->options)->shuffle();

    foreach ($shuffledOptions as $option) {
      $createdOption = $question->options()->create([
        'body' => $option["'body'"],
      ]);
      if ($option["'correct'"] === "true") {
        $question->answer()->create([
          'option_id' => $createdOption->id,
        ]);
      }
    }

    return response([
      'question' => Question::with(['options', 'answer'])->where('id', $question->id)->first(),
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Question  $question
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Question $question) {
    $data = $request->validate([
      'body' => 'required|unique:questions,body,' . $question->id,
      'category_id' => 'required',
    ]);

    $path = $question->image;
    if ($request->hasFile('image')) {
      $path = Storage::disk('public')->putFile('questions', $request->file('image'));
    }

    $question->update(array_merge($data, [
      'image' => $path,
    ]));

    foreach ($request->options as $option) {
      Option::where('id', $option["'id'"])->update([
        'body' => $option["'body'"],
      ]);
      if ($option["'correct'"] === "true") {
        Answer::where('question_id', $question->id)->update([
          'option_id' => $option["'id'"],
        ]);
      }
    }

    return response([
      'question' => Question::with(['options', 'answer'])->where('id', $question->id)->first(),
    ]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Question  $question
   * @return \Illuminate\Http\Response
   */
  public function destroy(Question $question) {
    $question->delete();
    return response(['message' => 'The question has been deleted', 'question' => $question]);
  }

  public function attach(Request $request, Quiz $quiz) {
    $quiz->questions()->syncWithoutDetaching($request->questions);
    $questions = $quiz->questions()->with(['options', 'answer'])->get();
    return response(['data' => $questions]);
  }

  public function detach(Request $request, Quiz $quiz) {
    $quiz->questions()->detach($request->questions);
    $questions = $quiz->questions()->with(['options', 'answer'])->get();
    return response(['data' => $questions]);
  }
}

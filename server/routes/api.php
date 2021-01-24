<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
  Route::get('users/me', 'UserController@show');
  Route::put('users/update/{user}', 'UserController@update');
  Route::delete('users/avatar/{user}', 'UserController@removeAvatar');
  Route::put('users/password/change', 'UserController@changePassword');
  Route::post('logout', 'AuthController@logout');
  Route::get('categories/{status?}', 'CategoryController@index');

  Route::middleware('admin')->group(function () {
    Route::get('users', 'UserController@index');
    Route::get('categories/restore/{category}', 'CategoryController@restore');
    Route::delete('categories/{category}/{force?}', 'CategoryController@destroy');
    Route::apiResource('categories', 'CategoryController')->except(['index', 'delete']);
  });

  // quiz route
  Route::apiResource('quizzes', 'QuizController')->except('show', 'update');
  Route::post('quizzes/{quiz}', 'QuizController@update');
  Route::post('quizzes/end/{quiz}', 'QuizController@end');

  // question route
  Route::apiResource('questions', 'QuestionController')->except('show', 'update');
  Route::post('questions/{question}', 'QuestionController@update');
  Route::post('questions/attach/{quiz}', 'QuestionController@attach');
  Route::post('questions/detach/{quiz}', 'QuestionController@detach');

});

Route::post('register', 'AuthController@register');
Route::post('login', 'AuthController@login');
Route::post('player', 'PlayerController@create');
// score
Route::post('score', 'ScoreController@store');
// get quiz details for player
Route::get('getQuizDetails/{quiz}', 'QuizController@getQuizDetails');

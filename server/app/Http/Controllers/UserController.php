<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller {
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    $users = User::latest()->get()->except([auth()->user()->id, 1]);
    return response(['users' => $users]);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\User  $user
   * @return \Illuminate\Http\Response
   */
  public function show() {
    $user = auth()->user();
    if ($user->is_admin) {
      $categories = auth()->user()->categories;
      return response(['user' => $user, 'categories' => $categories]);
    }
    $counts = [
      'question_count' => auth()->user()->questions->count(),
      'quiz_count' => auth()->user()->quizzes->count(),
    ];
    return response(['user' => $user, 'counts' => $counts]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\User  $user
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $user) {
    $user = User::find($user);
    if ($user) {
      $path = null;
      if ($request->hasFile('avatar')) {
        if ($user->avatar) {
          Storage::disk('public')->delete($user->avatar);
        }
        $path = Storage::disk('public')->putFile('avatars', $request->file('avatar'));
      }
      $user->touch();
      User::where('id', $user->id)->update(array_merge($request->except('password'), [
        'avatar' => $path ? $path : $user->avatar,
      ]));

      return response(['user' => $user->fresh()]);
    }
    return response(['message' => 'User not found'], 404);
  }

  public function removeAvatar(User $user) {
    if ($user->avatar) {
      Storage::disk('public')->delete($user->avatar);
      $user->update(['avatar' => null]);
      return response(['user' => $user->fresh()]);
    }
    return response(['message' => 'No avatar uploaded']);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\User  $user
   * @return \Illuminate\Http\Response
   */
  public function destroy(User $user) {
    //
  }

  public function changePassword(Request $request) {
    $data = $request->validate([
      'password' => 'required',
      'new' => 'required|min:8',
    ]);
    if ($data['new'] === $data['password']) {
      return response(['message' => 'Same old and new passowrd']);
    }
    if (!Hash::check($data['password'], auth()->user()->password)) {
      return response(['message' => 'Current password doesn\'t match your provided password']);
    }
    auth()->user()->update([
      'password' => Hash::make($data['new']),
    ]);
    return response(['message' => 'Your password has been changed']);
  }
}

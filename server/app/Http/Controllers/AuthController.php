<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
  public function register(Request $request) {
    $userData = $request->validate([
      'name' => 'required',
      'email' => 'email | required | unique:users',
      'password' => 'required | min:8',
    ]);

    $userData['password'] = Hash::make($userData['password']);

    $user = User::create($userData);
    $token = $user->createToken('auth-token')->plainTextToken;

    return response(['user' => $user, 'token' => $token]);
  }

  public function login(Request $request) {
    $credentials = $request->validate([
      'email' => 'email | required',
      'password' => 'required',
    ]);

    if (!auth()->attempt($credentials)) {
      return response(['message' => 'Invalid email or password!'], 422);
    }

    $token = auth()->user()->createToken('auth-token')->plainTextToken;

    return response(['user' => auth()->user(), 'token' => $token]);
  }

  public function logout() {
    auth()->user()->tokens()->delete();
    return response(['message' => 'You are logged out!']);
  }
}

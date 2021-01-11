<?php

namespace App\Http\Controllers;

use App\Quiz;
use App\Player;
use App\Events\PlayerJoined;
use Illuminate\Http\Request;

class PlayerController extends Controller {
  public function create(Request $request) {
    $validData = $request->validate([
      'name' => 'required|min:3',
      'pin' => 'required',
    ]);
    $quiz = Quiz::wherePin($request->pin)->first();
    if (!$quiz) {
      return response(['message' => 'Invalid pin!']);
    }
    $player = Player::create([
      "name" => $request->name,
      "quiz_id" => $quiz->id,
    ]);
    event(new PlayerJoined($player));
    
    return response(['player' => $player]);
  }
}

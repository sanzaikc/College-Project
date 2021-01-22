<?php

namespace App\Http\Controllers;

use App\Events\PlayerJoined;
use App\Player;
use App\Quiz;
use Illuminate\Http\Request;

class PlayerController extends Controller {
  public function create(Request $request) {
    $quiz = Quiz::wherePin($request->pin)->firstOrFail();
    $validData = $request->validate([
      'name' => 'required|min:3|unique:players,name,NULL,id,quiz_id,' . $quiz->id,
      'pin' => 'required',
    ]);
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

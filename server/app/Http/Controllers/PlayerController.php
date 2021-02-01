<?php

namespace App\Http\Controllers;

use App\Events\PlayerBuzzed;
use App\Events\PlayerJoined;
use App\Player;
use App\Quiz;
use Illuminate\Http\Request;

class PlayerController extends Controller {
  public function create(Request $request) {
    $quiz = Quiz::wherePin($request->pin)->first();
    if (!$quiz) {
      return response(['message' => 'Invalid pin!']);
    }if ($quiz->current_question) {
      return response(['message' => 'The quiz has already begun!']);
    }
    $request->validate([
      'name' => 'required|min:3|unique:players,name,NULL,id,quiz_id,' . $quiz->id,
      'pin' => 'required',
    ]);
    $player = Player::create([
      "name" => $request->name,
      "quiz_id" => $quiz->id,
    ]);
    event(new PlayerJoined($player));

    return response(['player' => $player]);
  }

  public function buzz(Request $request) {
    $player = Player::find($request->player_id);
    $quiz = Quiz::where('id', $player->quiz_id)->get();
    if (!$quiz->can_answer) {
      return response(422, ['message' => 'A player has already pressed the buzzer!']);
    }
    $quiz->update([
      'can_answer' => false,
      'player_id' => $player->id,
    ]);
    event(new PlayerBuzzed($quiz->fresh()));
    return response(['quiz' => $quiz]);
  }
}

<?php

namespace App\Http\Controllers;

use App\Events\PlayerAnswered;
use App\Score;
use Illuminate\Http\Request;

class ScoreController extends Controller {
  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    $prev_score = Score::where('player_id', $request->player_id)->first();
    $increment = $prev_score ? $prev_score->score : 0;
    $score = Score::updateOrCreate(
      ['player_id' => $request->player_id],
      ['score' => $request->score + $increment]
    );

    event(new PlayerAnswered($score->player, $request->optionId));

    return response(['player' => $score->player]);
  }
}

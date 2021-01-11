<?php

namespace App\Http\Controllers;

use App\Events\ScoreChanged;
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
    $score = Score::updateOrCreate(
      ['player_id' => $request->player_id],
      ['score' => $request->score]
    );
    event(new ScoreChanged($score->player));
    return response(['player' => $score->player]);
  }
}

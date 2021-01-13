<?php

namespace App;

use App\Quiz;
use Illuminate\Database\Eloquent\Model;

class Player extends Model {
  protected $guarded = [];

  public function quiz() {
    return $this->belongsTo(Quiz::class);
  }

  public function score() {
    return $this->hasOne(Score::class);
  }
}

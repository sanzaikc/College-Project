<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Score extends Model {
  protected $guarded = [];

  public function player() {
    return $this->belongsTo(Player::class);
  }
}

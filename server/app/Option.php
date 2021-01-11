<?php

namespace App;

use App\Question;
use Illuminate\Database\Eloquent\Model;

class Option extends Model {
  protected $guarded = [];

  public function question() {
    return $this->belongsTo(Question::class);
  }
}

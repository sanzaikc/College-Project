<?php

namespace App;

use App\Player;
use App\Question;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model {
  protected $guarded = [];
  protected $appends = ['image_url'];

  public function getImageUrlAttribute() {
    if ($this->image) {
      return asset('storage/' . $this->image);
    }
    return null;
  }

  public function user() {
    return $this->belongsTo(User::class);
  }

  public function questions() {
    return $this->belongsToMany(Question::class);
  }

  public function players() {
    return $this->hasMany(Player::class);
  }
}

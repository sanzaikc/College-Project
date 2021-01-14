<?php

namespace App;

use App\Answer;
use App\Option;
use App\Quiz;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Question extends Model {
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

  public function quizzes() {
    return $this->belongsToMany(Quiz::class);
  }

  public function options() {
    return $this->hasMany(Option::class);
  }

  public function answer() {
    return $this->hasOne(Answer::class);
  }

  public function category() {
    return $this->belongsTo(Category::class);
  }
}

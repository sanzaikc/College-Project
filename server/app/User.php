<?php

namespace App;

use App\Category;
use App\Question;
use App\Quiz;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable {
  use Notifiable, HasApiTokens;

  protected $appends = ['avatar_url'];

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
    'name', 'email', 'password', 'avatar',
  ];

  /**
   * The attributes that should be hidden for arrays.
   *
   * @var array
   */
  protected $hidden = [
    'password', 'remember_token', 'categories', 'avatar',
  ];

  /**
   * The attributes that should be cast to native types.
   *
   * @var array
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public function getAvatarUrlAttribute() {
    if ($this->avatar) {
      return asset('storage/' . $this->avatar);
    }
    return null;
  }

  public function categories() {
    return $this->hasMany(Category::class);
  }

  public function quizzes() {
    return $this->hasMany(Quiz::class);
  }

  public function questions() {
    return $this->hasMany(Question::class);
  }
}

<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model {
  use SoftDeletes;
  protected $guarded = [];

  public function scopeStatus($query, $status) {
    if ($status == 1) {
      return $query->onlyTrashed();
    }
    return $query;
  }

  public function user() {
    return $this->belongsTo(User::class);
  }
}

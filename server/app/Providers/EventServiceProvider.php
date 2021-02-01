<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider {
  /**
   * The event listener mappings for the application.
   *
   * @var array
   */
  protected $listen = [
    'App\Events\PlayerJoined' => [],
    'App\Events\QuestionChanged' => [],
    'App\Events\ScoreChanged' => [],
    'App\Events\PlayerAnswered' => [],
    'App\Events\QuestionPassed' => [],
    'App\Events\QuizEnded' => [],
    'App\Events\PlayerBuzzed' => [],
  ];

  /**
   * Register any events for your application.
   *
   * @return void
   */
  public function boot() {
    parent::boot();

    //
  }
}

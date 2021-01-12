<?php

namespace App\Events;

use App\Score;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ScoreChanged implements ShouldBroadcast {
  use Dispatchable, InteractsWithSockets, SerializesModels;
  public $player;
  public $scores;
  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct($player) {
    $this->player = $player;

    $this->scores = Score::whereHas('player', function (Builder $query) {
      $query->where('quiz_id', $this->player->quiz->id);
    })->get();
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return \Illuminate\Broadcasting\Channel|array
   */
  public function broadcastOn() {
    return new Channel('quizy' . $this->player->quiz->id);
  }
}

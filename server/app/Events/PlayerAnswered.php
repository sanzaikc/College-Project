<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlayerAnswered {
  use Dispatchable, InteractsWithSockets, SerializesModels;
  public $player;
  public $scores;
  public $optionId;
  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct($player, $optionId) {
    $this->player = $player;
    $this->optionId = $optionId;
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

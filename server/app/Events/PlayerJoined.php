<?php

namespace App\Events;

use App\Player;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PlayerJoined implements ShouldBroadcast {
  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $player;
  public $players;
  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct($player) {
    $this->player = $player;
    $this->players = Player::where('quiz_id', $player->quiz_id)->get();
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return \Illuminate\Broadcasting\Channel|array
   */
  public function broadcastOn() {
    return new Channel('quizy' . $this->player->quiz_id);
  }
}

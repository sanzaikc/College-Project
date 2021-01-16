<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuestionChanged implements ShouldBroadcast {
  use Dispatchable, InteractsWithSockets, SerializesModels;
  public $question;
  public $quiz;
  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct($question, $quiz) {
    $this->question = $question;
    $this->quiz = $quiz;
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return \Illuminate\Broadcasting\Channel|array
   */
  public function broadcastOn() {
    return new Channel('Quizy' . $this->quiz->id);
  }
}

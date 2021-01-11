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
  public $quiz_id;
  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct($question, $quiz_id) {
    $this->question = $question;
    $this->quiz_id = $quiz_id;
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return \Illuminate\Broadcasting\Channel|array
   */
  public function broadcastOn() {
    return new Channel('Quizy' . $this->quiz_id);
  }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuizzesTable extends Migration {
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up() {
    Schema::create('quizzes', function (Blueprint $table) {
      $table->id();
      $table->string('name');
      $table->text('description');
      $table->string('image')->nullable();
      $table->integer('pin')->nullable();
      $table->bigInteger('current_question')->nullable();
      $table->enum('round_type', ['timer', 'buzzer'])->default('timer');
      $table->bigInteger('player_id')->nullable();
      $table->boolean('can_answer')->default('true');
      $table->foreignId('user_id')->constrained()->onDelete('cascade');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down() {
    Schema::dropIfExists('quizzes');
  }
}

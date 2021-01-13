<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder {
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {
    $quizzes = [
      [
        'name' => 'Fun Quiz',
        'description' => 'This quiz contains test questions.',
      ],
      [
        'name' => 'About Nepal',
        'description' => 'This quiz contains questions about Nepal.',
      ],
      [
        'name' => 'Capital City Quiz',
        'description' => 'This quiz contains question about captial city of different countries.',
      ],
    ];

    foreach ($quizzes as $quiz) {
      DB::table('quizzes')->insert([
        'name' => $quiz['name'],
        'description' => $quiz['description'],
        'image' => null,
        'user_id' => 2,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ]);
    }
  }
}

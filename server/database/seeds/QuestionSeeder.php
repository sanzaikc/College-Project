<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder {
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {
    $questions = [
      [
        'id' => 1,
        'body' => 'What is the capital city of Nepal?',
        'options' => ['Kathmandu', 'Hetauda', 'Pokhara', 'Nepalgunj'],
      ],
      [
        'id' => 2,
        'body' => 'What is the capital city of India?',
        'options' => ['New Dehli', 'Chennai', 'Mumbai', 'Kolkata'],
      ],
      [
        'id' => 3,
        'body' => 'What is the capital city of Japan?',
        'options' => ['Tokyo', 'Osaka', 'Hiroshima', 'Kyoto'],
      ],
      [
        'id' => 4,
        'body' => 'What is the capital city of China?',
        'options' => ['Beijing', 'Shanghai', 'Wuhan', 'Shenzhen'],
      ],
      [
        'id' => 5,
        'body' => 'What is the capital city of America?',
        'options' => ['Washington, D.C.', 'New York', 'Seattle', 'Baston'],
      ],
    ];

    foreach ($questions as $question) {
      DB::table('questions')->insert([
        'body' => $question['body'],
        'image' => null,
        'category_id' => 1,
        'user_id' => 2,
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
      ]);
      foreach ($question['options'] as $key => $option) {
        DB::table('options')->insert([
          'body' => $option,
          'question_id' => $question['id'],
          'created_at' => Carbon::now(),
          'updated_at' => Carbon::now(),
        ]);
        if ($key == 0) {
          DB::table('answers')->insert([
            'option_id' => $question['id'] + (4 * ($question['id'] - 1)) - ($question['id'] - 1),
            'question_id' => $question['id'],
          ]);
        }
      }
    }
  }
}

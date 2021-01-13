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
      // general 1
      [
        'id' => 1,
        'body' => 'What is the capital city of Nepal?',
        'options' => ['Kathmandu', 'Hetauda', 'Pokhara', 'Nepalgunj'],
        'category' => 1,
      ],
      [
        'id' => 2,
        'body' => 'What is the capital city of India?',
        'options' => ['New Dehli', 'Chennai', 'Mumbai', 'Kolkata'],
        'category' => 1,
      ],
      [
        'id' => 3,
        'body' => 'What is the capital city of Japan?',
        'options' => ['Tokyo', 'Osaka', 'Hiroshima', 'Kyoto'],
        'category' => 1,
      ],
      [
        'id' => 4,
        'body' => 'What is the capital city of China?',
        'options' => ['Beijing', 'Shanghai', 'Wuhan', 'Shenzhen'],
        'category' => 1,
      ],
      [
        'id' => 5,
        'body' => 'What is the capital city of America?',
        'options' => ['Washington, D.C.', 'New York', 'Seattle', 'Baston'],
        'category' => 1,
      ],

      // science 8
      [
        'id' => 6,
        'body' => 'What is the chemical formula for water?',
        'options' => ['H2O', 'CO2', 'O2', 'O2H'],
        'category' => 8,
      ],
      [
        'id' => 7,
        'body' => 'What is equal to mass times acceleration?',
        'options' => ['Force', 'Momentum', 'Pressure', 'Speed'],
        'category' => 8,
      ],
      [
        'id' => 8,
        'body' => 'Which frozen gas forms dry ice?',
        'options' => ['Carbon Dioxide', 'Nitrogen', 'Hydrogen', 'Oxygen'],
        'category' => 8,
      ],

      // sports 3
      [
        'id' => 9,
        'body' => 'What is the national sport of Nepal?',
        'options' => ['Volleyball', 'Football', 'Kabaddi', 'Wrestling'],
        'category' => 3,
      ],
      [
        'id' => 10,
        'body' => 'Which country won the World Cup in 2014?',
        'options' => ['Germany', 'Brazil', 'France', 'Italy'],
        'category' => 3,
      ],
    ];

    foreach ($questions as $question) {
      DB::table('questions')->insert([
        'body' => $question['body'],
        'image' => null,
        'category_id' => $question['category'],
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
    // foreach ($scienceQuestions as $question) {
    //   DB::table('questions')->insert([
    //     'body' => $question['body'],
    //     'image' => null,
    //     'category_id' => 8,
    //     'user_id' => 2,
    //     'created_at' => Carbon::now(),
    //     'updated_at' => Carbon::now(),
    //   ]);
    //   foreach ($question['options'] as $key => $option) {
    //     DB::table('options')->insert([
    //       'body' => $option,
    //       'question_id' => $question['id'],
    //       'created_at' => Carbon::now(),
    //       'updated_at' => Carbon::now(),
    //     ]);
    //     if ($key == 0) {
    //       DB::table('answers')->insert([
    //         'option_id' => $question['id'] + (4 * ($question['id'] - 1)) - ($question['id'] - 1),
    //         'question_id' => $question['id'],
    //       ]);
    //     }
    //   }
    // }
  }
}

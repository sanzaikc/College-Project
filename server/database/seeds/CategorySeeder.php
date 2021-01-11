<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder {
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {
    $categories = ['General', 'Programming', 'Sports', 'Art and Culture', 'TV and Films', 'History', 'Geography', 'Science'];

    foreach ($categories as $category) {
      DB::table('categories')->insert([
        'name' => $category,
        'user_id' => 1,
        'created_at' => Carbon::now(),
      ]);
    }
  }
}

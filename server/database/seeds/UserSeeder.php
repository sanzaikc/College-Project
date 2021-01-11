<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder {
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {
    DB::table('users')->insert([
      'name' => 'Admin',
      'email' => 'admin@quizy.com',
      'password' => Hash::make('password'),
      'is_admin' => true,
      'is_disabled' => false,
      'created_at' => Carbon::now(),
    ]);
    DB::table('users')->insert([
      'name' => 'User',
      'email' => 'user@quizy.com',
      'password' => Hash::make('password'),
      'is_admin' => false,
      'is_disabled' => false,
      'created_at' => Carbon::now(),
    ]);
  }
}

<?php

/* 
 * A Forever Home Rescue Foundation 
 * 
 */

use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();

        User::create([
            'name' => 'Default Administrator',
            'email' => 'admin@example.org',
            'password' => Hash::make('secret'),
        ]);
    }
}
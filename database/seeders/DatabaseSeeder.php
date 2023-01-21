<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Asofroniei Roxana',
            'email' => 'andreea.asofroniei99@e-uvt.ro',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'isAdmin' => true,
            'remember_token' => Str::random(10),
            'current_team_id' => 1,
            'profile_photo_path' => 'path/to/profile/photo',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

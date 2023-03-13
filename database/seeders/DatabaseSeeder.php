<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'name' => 'admin name',
            'username' => 'admin',
            'email' => 'rons@gmail.com',
            'password' => bcrypt('helloworld'),
            'api_token' => Str::random(60)
        ]);        
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            "name" => "admin",
            "email" => "admin@gmail.com",
            "role" => "admin",
            "password" => Hash::make("asdffdsa"),
        ]);

        User::factory()->create([
            "name" => "staff",
            "email" => "staff@gmail.com",
            "role" => "staff",
            "password" => Hash::make("asdffdsa"),
        ]);
    }
}

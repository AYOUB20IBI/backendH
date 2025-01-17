<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // \App\Models\Receptionist::factory(1)->create();

        // \App\Models\Receptionist::factory()->create([
        //     'first_name' => 'Test',
        //     'last_name'=>'Receptionist',
        //     'email' => 'Receptionist@example.com',
        // ]);

        // \App\Models\Admin::factory(1)->create();

        // \App\Models\Admin::factory()->create([
        //     'first_name' => 'Test',
        //     'last_name'=>'admin',
        //     'email' => 'admin@example.com',
        // ]);

        \App\Models\Guest::factory(1)->create();

        \App\Models\Guest::factory()->create([
            'first_name' => 'Test',
            'last_name'=>'user',
            'email' => 'user@example.com',
        ]);
    }
}

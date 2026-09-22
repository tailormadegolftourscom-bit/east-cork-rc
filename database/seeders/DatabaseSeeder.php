<?php

namespace Database\Seeders;

use App\Models\Parents;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Parents::factory(10)->create();

        Parents::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
<<<<<<< HEAD
use Illuminate\Support\Facades\Hash;
=======
>>>>>>> d2ba4ff (Initial commit adukan vercel)

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

<<<<<<< HEAD
        User::create([
        'name' => 'Admin',
        'email' => 'admin@sekolah.sch.id',
        'password' => bcrypt('adminstemroska'),
        'role' => 'admin',
=======
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
>>>>>>> d2ba4ff (Initial commit adukan vercel)
        ]);
    }
}

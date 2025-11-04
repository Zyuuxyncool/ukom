<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Admin Si-Baca',
            'email' => 'admin@sibaca',
            'password' => bcrypt('devi_putri_handayani20')
        ]);
        $user->akses()->create(['akses' => 'Administrator']);
    }
}

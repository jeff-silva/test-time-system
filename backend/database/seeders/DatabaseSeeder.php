<?php

namespace Database\Seeders;

use App\Models\AppUser;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $appUser = AppUser::firstOrNew(['id' => 1], [
            'name' => 'Main User',
            'email' => 'main@grr.la',
            'password' => 'main@grr.la',
            'email_verified_at' => now(),
        ]);

        $appUser->save();
    }
}

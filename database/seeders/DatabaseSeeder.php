<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Administrateur',
            'email' => 'admin@codelearn.fr',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create free user
        User::create([
            'name' => 'Utilisateur Gratuit',
            'email' => 'free@codelearn.fr',
            'password' => Hash::make('password'),
            'role' => 'free',
            'email_verified_at' => now(),
        ]);

        // Create subscribed user
        User::create([
            'name' => 'Utilisateur Premium',
            'email' => 'premium@codelearn.fr',
            'password' => Hash::make('password'),
            'role' => 'subscribed',
            'subscription_expires_at' => now()->addDays(30),
            'email_verified_at' => now(),
        ]);

        // Run other seeders
        $this->call([
            CategorySeeder::class,
            LessonSeeder::class,
            ExerciseSeeder::class,
            VideoSeeder::class,
        ]);
    }
}

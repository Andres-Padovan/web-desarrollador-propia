<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = env('ADMIN_PASSWORD') ?: Str::password(16);

        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'andrespadovan38@gmail.com')],
            ['name' => config('profile.name'), 'password' => $password],
        );

        if (! env('ADMIN_PASSWORD')) {
            $this->command?->warn("Usuario admin creado con contraseña temporal: {$password}");
        }

        $this->call([
            ServiceSeeder::class,
            ProjectSeeder::class,
        ]);
    }
}

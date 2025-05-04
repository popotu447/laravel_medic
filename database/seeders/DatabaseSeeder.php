<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\Patient;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // Konkretnych użytkowników
        $users = collect([
            ['name' => 'doctor1', 'email' => 'doctor1@example.com', 'password' => Hash::make('haslo1')],
            ['name' => 'doctor2', 'email' => 'doctor2@example.com', 'password' => Hash::make('haslo2')],
        ])->map(fn ($data) => User::create($data));

        // Pacjenci i pliki
        $users->each(function (User $user) {
            Patient::factory()
                ->count(2)
                ->create(['user_id' => $user->id])
//                ->each(function (Patient $patient) {
//                    //File::factory()->create(['patient_id' => $patient->id]);
//                })
            ;
        });
    }

}

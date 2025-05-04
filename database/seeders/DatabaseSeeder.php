<?php

namespace Database\Seeders;

use App\Models\File;
use App\Models\Patient;
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

        User::factory()->count(2)->create()->each(function ($user) {
            Patient::factory()->count(2)->create([
                'user_id' => $user->id,
            ])->each(function ($patient) {
                File::factory()->count(1)->create([
                    'patient_id' => $patient->id,
                ]);
            });
        });

    }

}

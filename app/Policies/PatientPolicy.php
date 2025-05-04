<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PatientPolicy
{
    public function createFile(User $user, Patient $patient): bool
    {
        return $patient->user_id === $user->id;
    }

    public function view(User $user, Patient $patient): bool
    {
        return $patient->user_id === $user->id;
    }
}

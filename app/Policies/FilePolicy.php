<?php

namespace App\Policies;

use App\Models\File;
use App\Models\Patient;
use App\Models\User;

class FilePolicy
{
    public function view(User $user, File $file): bool
    {
        return $file->patient->user_id === $user->id;
    }

    public function create(User $user, File $file): bool
    {
        return $file->patient->user_id === $user->id;
    }

    public function update(User $user, File $file): bool
    {
        return $file->patient->user_id === $user->id;
    }

    public function delete(User $user, File $file): bool
    {
        return $file->patient->user_id === $user->id;
    }
}

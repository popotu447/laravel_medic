<?php

namespace App\Services;

use App\Contracts\FileStorageInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileStorageService implements FileStorageInterface
{
    public function store(UploadedFile $file): string
    {
        return $file->store('uploads', 'public');
    }

    public function delete(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    public function url(string $path): string
    {
        return Storage::url($path);
    }

    public function fullPath(?string $path): string
    {
        return storage_path('app/public/' . $path);
    }
}

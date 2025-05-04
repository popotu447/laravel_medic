<?php

namespace App\Contracts;

use Illuminate\Http\UploadedFile;

interface FileStorageInterface
{
    public function store(UploadedFile $file): string;
    public function delete(?string $path): void;
    public function url(string $path): string;
    public function fullPath(string $path): string;
}

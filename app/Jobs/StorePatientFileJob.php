<?php

namespace App\Jobs;

use App\Models\File;
use App\Services\FileStorageService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\UploadedFile;

class StorePatientFileJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public string $tmpRelativePath,
        public int $fileId
    ) {}

    public function handle(FileStorageService $storage)
    {
        $fileModel = File::findOrFail($this->fileId);
        $fullPath = storage_path('app/' . $this->tmpRelativePath);

        if (!file_exists($fullPath)) {
            \Log::error("Brak pliku tymczasowego: {$fullPath}");
            return;
        }

        $uploaded = new UploadedFile($fullPath, basename($fullPath), null, null, true);
        $path = $storage->store($uploaded);
        $fileModel->update(['path' => $path]);

        unlink($fullPath);
    }
}

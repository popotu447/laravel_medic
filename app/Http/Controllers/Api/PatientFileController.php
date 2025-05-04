<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatientFileRequest;
use App\Http\Requests\UpdatePatientFileRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\FileStorageService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class PatientFileController extends Controller
{
    use AuthorizesRequests;

    public function __construct(private FileStorageService $fileStorage)
    {
    }

    public function index(Patient $patient)
    {
        $this->authorize('view', $patient);
        return FileResource::collection($patient->files);
    }

    public function store(StorePatientFileRequest $request, Patient $patient)
    {
        $this->authorize('createFile', $patient);

        $path = $this->fileStorage->store($request->file('file'));
        $file = $patient->files()->create([
            'description' => $request->input('description'),
            'path' => $path,
        ]);

        return (new FileResource($file))
            ->additional(['message' => 'Plik został zapisany.']);

    }

    public function show(File $file)
    {
        $this->authorize('view', $file);

        $path = $this->fileStorage->fullPath($file->path);

        if (!file_exists($path)) {
            return response()->json(['message' => 'Plik nie istnieje na serwerze.'], 404);
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
        ]);
    }

    public function update(UpdatePatientFileRequest $request, File $file)
    {

        $this->authorize('update', $file);

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($file->path);

            $file->path = $request->file('file')->store('uploads', 'public');
        }

        if ($request->has('description')) {
            $file->description = $request->input('description');
        }

        $file->save();

        return (new FileResource($file))
            ->additional(['message' => 'Plik został zaktualizowany.']);
    }

    public function destroy(File $file)
    {
        $this->authorize('delete', $file);

        $this->fileStorage->delete($file->path);

        $file->delete();

        return (new FileResource($file))
            ->additional(['message' => 'Plik został usunięty.']);

    }

}

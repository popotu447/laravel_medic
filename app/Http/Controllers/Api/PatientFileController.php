<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePatientFileRequest;
use App\Http\Requests\UpdatePatientFileRequest;
use App\Models\File;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PatientFileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Patient $patient)
    {
        if ($patient->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return response()->json($patient->files);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePatientFileRequest $request, Patient $patient)
    {
        $path = $request->file('file')->store('uploads', 'public');

        $file = $patient->files()->create([
            'description' => $request->input('description'),
            'path' => $path,
        ]);

        return response()->json([
            'message' => 'Plik zapisany.',
            'data' => $file,
            'url' => Storage::url($file->path),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(File $file)
    {
        if ($file->patient->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $path = storage_path('app/public/' . $file->path);

        if (!file_exists($path)) {
            return response()->json(['message' => 'Plik nie istnieje na serwerze.'], 404);
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . basename($path) . '"',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePatientFileRequest $request, File $file)
    {
        if ($request->hasFile('file')) {
            if (Storage::disk('public')->exists($file->path)) {
                Storage::disk('public')->delete($file->path);
            }

            $file->path = $request->file('file')->store('uploads', 'public');
        }

        if ($request->filled('description')) {
            $file->description = $request->input('description');
        }

        $file->save();

        return response()->json([
            'message' => 'Plik zaktualizowany.',
            'data' => $file,
            'url' => Storage::url($file->path),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(File $file)
    {
        if ($file->patient->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (Storage::disk('public')->exists($file->path)) {
            Storage::disk('public')->delete($file->path);
        }

        $file->delete();

        return response()->json(['message' => 'Plik został usunięty.'], 200);
    }
}

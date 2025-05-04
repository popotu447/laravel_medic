<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StorePatientFileTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_upload_pdf_file_for_own_patient()
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $patient = Patient::factory()->create(['user_id' => $user->id]);

        $file = UploadedFile::fake()->create('test.pdf', 500, 'application/pdf');

        $response = $this->actingAs($user)
            ->postJson(route('patients.files.store', $patient), [
                'file' => $file,
                'description' => 'Wynik badania'
            ]);

        $response->assertCreated();
        $response->assertJsonStructure([
            'data' => ['id', 'description', 'url', 'created_at'],
            'message'
        ]);

        // Sprawdź, czy plik zapisany
        Storage::disk('public')->assertExists('uploads/' . basename($response->json('data.url')));
    }

    public function test_user_cannot_upload_file_for_foreign_patient()
    {
        $user = User::factory()->create();
        $foreignPatient = Patient::factory()->create(); // inny user_id

        $file = UploadedFile::fake()->create('test.pdf', 500, 'application/pdf');

        $response = $this->actingAs($user)
            ->postJson(route('patients.files.store', $foreignPatient), [
                'file' => $file,
                'description' => 'Wynik badania'
            ]);

        $response->assertForbidden();
    }

    public function test_upload_fails_with_non_pdf()
    {
        $user = User::factory()->create();
        $patient = Patient::factory()->create(['user_id' => $user->id]);

        $file = UploadedFile::fake()->create('image.jpg', 500, 'image/jpeg');

        $response = $this->actingAs($user)
            ->postJson(route('patients.files.store', $patient), [
                'file' => $file,
                'description' => 'Nieprawidłowy typ'
            ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors('file');
    }
}

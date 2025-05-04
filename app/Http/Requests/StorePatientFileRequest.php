<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePatientFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $patient = $this->route('patient');

        return $patient && $patient->user_id === auth()->id();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'max:' . env('PATIENT_FILE_MAX_SIZE'),
                'mimes:pdf',
            ],
            'description' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'file.mimes' => 'Dozwolony jest tylko plik PDF.',
            'file.max' => 'Plik nie może być większy niż ' . env('PATIENT_FILE_MAX_SIZE') / 1024 . ' MB.'
        ];
    }
}

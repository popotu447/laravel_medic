<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientFileRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $file = $this->route('file');

        return $file && $file->patient->user_id === auth()->id();
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
                'nullable',
                'file',
                'max:' . env('PATIENT_FILE_MAX_SIZE'),
                'mimes:pdf',
            ],
            'description' => 'nullable|string|max:255',
        ];
    }
}

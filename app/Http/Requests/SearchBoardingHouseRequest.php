<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Validates guest search queries for boarding houses.
 * Since guests are unauthenticated, authorize() returns true.
 */
class SearchBoardingHouseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100', 'min:2'],
        ];
    }

    public function messages(): array
    {
        return [
            'q.min'  => 'Kata kunci pencarian minimal 2 karakter.',
            'q.max'  => 'Kata kunci pencarian maksimal 100 karakter.',
        ];
    }

    /**
     * Extract validated filters into a clean array consumed by the Service layer.
     */
    public function filters(): array
    {
        return [
            'q' => $this->validated('q'),
        ];
    }
}

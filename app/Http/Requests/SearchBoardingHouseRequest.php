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
            'q'                  => ['nullable', 'string', 'max:100', 'min:2'],
            'gender_type'        => ['nullable', 'string', 'in:putra,putri,campur'],
            'city'               => ['nullable', 'string', 'max:100'],
            'district_id'        => ['nullable', 'string', 'max:7'],     // BPS 7-char code
            'landmark_id'        => ['nullable', 'string', 'uuid'],      // UUID of a landmark
            'min_price'          => ['nullable', 'integer', 'min:0'],
            'max_price'          => ['nullable', 'integer', 'min:0'],
            'facilities'         => ['nullable', 'array'],
            'facilities.*'       => ['nullable', 'string', 'uuid'],
            'room_facilities'    => ['nullable', 'array'],
            'room_facilities.*'  => ['nullable', 'string', 'uuid'],
            'rule_categories'    => ['nullable', 'array'],
            'rule_categories.*'  => ['nullable', 'string', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'q.min'              => 'Kata kunci pencarian minimal 2 karakter.',
            'q.max'              => 'Kata kunci pencarian maksimal 100 karakter.',
            'min_price.integer'  => 'Harga minimum harus berupa angka.',
            'max_price.integer'  => 'Harga maksimum harus berupa angka.',
        ];
    }

    /**
     * Extract validated filters into a clean array consumed by the Service layer.
     */
    public function filters(): array
    {
        return [
            'q'               => $this->validated('q'),
            'gender_type'     => $this->validated('gender_type'),
            'city'            => $this->validated('city'),
            'district_id'     => $this->validated('district_id'),
            'landmark_id'     => $this->validated('landmark_id'),
            'min_price'       => $this->validated('min_price'),
            'max_price'       => $this->validated('max_price'),
            'facilities'      => $this->validated('facilities'),
            'room_facilities' => $this->validated('room_facilities'),
            'rule_categories' => $this->validated('rule_categories'),
        ];
    }
}


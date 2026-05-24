<?php

namespace App\Http\Requests\Owner;

use App\Enum\GenderType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateKosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role->value === 'owner';
    }

    public function rules(): array
    {
        return [
            'name'        => ['sometimes', 'required', 'string', 'max:255'],
            'address'     => ['sometimes', 'required', 'string'],
            'city'        => ['sometimes', 'required', 'string', 'max:100'],
            'province'    => ['sometimes', 'required', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'gender_type' => ['sometimes', 'required', new Enum(GenderType::class)],
            'description' => ['nullable', 'string'],
            'facilities'  => ['nullable', 'array'],
            'facilities.*'=> ['uuid', 'exists:facilities,id'],
            'rules'       => ['nullable', 'array'],
            'rules.*'     => ['uuid', 'exists:rules,id'],
        ];
    }
}

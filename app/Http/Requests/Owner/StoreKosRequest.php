<?php

namespace App\Http\Requests\Owner;

use App\Enum\GenderType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreKosRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->role->value === 'owner';
    }

    public function rules(): array
    {
        return [
            'name'        => ['required', 'string', 'max:255'],
            'address'     => ['required', 'string'],
            'city'        => ['required', 'string', 'max:100'],
            'province'    => ['required', 'string', 'max:100'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'gender_type' => ['required', new Enum(GenderType::class)],
            'description' => ['nullable', 'string'],
            'facilities'  => ['nullable', 'array'],
            'facilities.*'=> ['uuid', 'exists:facilities,id'],
            'rules'       => ['nullable', 'array'],
            'rules.*'     => ['uuid', 'exists:rules,id'],
        ];
    }
}

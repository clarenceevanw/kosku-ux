<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KosBotChatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Public endpoint — no auth required
    }

    public function rules(): array
    {
        return [
            'message'         => ['required', 'string', 'min:1', 'max:1000'],
            'conversation_id' => ['nullable', 'string', 'uuid'],
        ];
    }

    public function messages(): array
    {
        return [
            'message.required' => 'Pesan tidak boleh kosong.',
            'message.max'      => 'Pesan terlalu panjang (maksimal 1000 karakter).',
        ];
    }
}

<?php

namespace App\Http\Requests;

use App\Enum\PriorityLevel;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreMaintananceTicketRequest extends FormRequest
{
    /**
     * Only authenticated tenants may submit maintenance tickets.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_id'     => ['required', 'uuid', 'exists:rooms,id'],
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:2000'],
            'priority'    => ['required', new Enum(PriorityLevel::class)],
            'photo'       => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp', 'max:5120'],
        ];
    }

    public function messages(): array
    {
        return [
            'room_id.required'     => 'Kamar tidak ditemukan. Harap login ulang.',
            'title.required'       => 'Judul laporan wajib diisi.',
            'title.max'            => 'Judul laporan maksimal 255 karakter.',
            'description.required' => 'Deskripsi kerusakan wajib diisi.',
            'description.max'      => 'Deskripsi maksimal 2000 karakter.',
            'priority.required'    => 'Prioritas wajib dipilih.',
            'photo.image'          => 'File harus berupa gambar.',
            'photo.mimes'          => 'Format gambar harus jpeg, jpg, png, atau webp.',
            'photo.max'            => 'Ukuran gambar maksimal 5MB.',
        ];
    }
}

<?php

namespace App\Ai\Tools;

use App\Models\BoardingHouse;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

/**
 * GetHouseDetailsTool
 *
 * Digunakan KosBotAgent untuk mengambil detail lengkap satu kos berdasarkan UUID-nya.
 * AI akan memanggil ini setelah mendapatkan ID kos dari tool pencarian.
 */
class GetHouseDetailsTool implements Tool
{
    public function name(): string
    {
        return 'get_house_details';
    }

    public function description(): string
    {
        return 'Mengambil detail lengkap sebuah kos berdasarkan ID-nya, termasuk semua tipe kamar, '
            . 'fasilitas, aturan, dan ulasan. Gunakan setelah mendapatkan ID kos dari pencarian.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'boarding_house_id' => $schema->string()->description('UUID dari kos yang ingin dilihat detailnya.')->required(),
        ];
    }

    public function handle(Request $request): Stringable|string
    {
        \Log::info('Isi Request dari AI: ', (array) $request);
        $boarding_house_id = $request['boarding_house_id'] ?? '';
        try {
            $house = BoardingHouse::with([
                'rooms.facilities:id,name',
                'facilities:id,name',
                'rules:id,boarding_house_id,category,rule_text',
                'reviews:id,boarding_house_id,rating,comment',
                'owner:id,name,phone_number',
            ])->findOrFail($boarding_house_id);
        } catch (ModelNotFoundException) {
            return json_encode([
                'found'   => false,
                'message' => 'Kos dengan ID tersebut tidak ditemukan.',
            ]);
        }

        $avgRating = $house->reviews->avg('rating');

        return json_encode([
            'found'       => true,
            'id'          => $house->id,
            'name'        => $house->name,
            'description' => $house->description,
            'address'     => $house->address,
            'city'        => $house->city,
            'gender'      => $house->gender_type,
            'owner'       => $house->owner?->name,
            'owner_phone' => $house->owner?->phone_number,
            'facilities'  => $house->facilities->pluck('name'),
            'rules'       => $house->rules->map(fn($r) => ['category' => $r->category, 'rule' => $r->rule_text]),
            'rooms'       => $house->rooms->map(fn($room) => [
                'type'         => $room->type_name,
                'price'        => $room->price_per_month,
                'price_text'   => 'Rp ' . number_format($room->price_per_month, 0, ',', '.') . '/bulan',
                'size'         => $room->size,
                'stock'        => $room->stock,
                'facilities'   => $room->facilities->pluck('name'),
            ]),
            'rating'      => $avgRating ? round($avgRating, 1) : null,
            'total_reviews' => $house->reviews->count(),
            'detail_url'  => route('kos.show', $house->id),
        ]);
    }
}

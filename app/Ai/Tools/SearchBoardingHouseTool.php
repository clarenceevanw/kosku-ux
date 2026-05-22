<?php

namespace App\Ai\Tools;

use App\Models\BoardingHouse;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

/**
 * SearchBoardingHouseTool
 *
 * Digunakan oleh KosBotAgent untuk mencari kos dari database KosKu
 * berdasarkan keyword (nama, kota, alamat) secara real-time.
 *
 * Hanya layer Tools yang boleh menyentuh Eloquent langsung.
 * Tools adalah "jembatan" antara AI dan database.
 */
class SearchBoardingHouseTool implements Tool
{
    /**
     * The name of the tool as seen by the AI.
     */
    public function name(): string
    {
        return 'search_boarding_house';
    }

    /**
     * Description of what this tool does, used by the AI to decide when to call it.
     */
    public function description(): string
    {
        return 'Mencari kos (boarding house) dari database KosKu berdasarkan kata kunci, nama, kota, atau alamat. '
            . 'Gunakan tool ini ketika pengguna menyebutkan lokasi atau nama kos yang ingin dicari.';
    }

    /**
     * Get the tool's schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'query' => $schema->string()->description('Kata kunci pencarian: nama kos, kota, kecamatan, atau alamat. Contoh: "ITS", "Surabaya", "Darmo".')->required(),
            'city'  => $schema->string()->description('Filter berdasarkan kota secara spesifik. Contoh: "Surabaya", "Jakarta Selatan", "Bandung".')->nullable(),
        ];
    }

    /**
     * Execute the tool and return results to the AI as a JSON string.
     */
    public function handle(Request $request): Stringable|string
    {
        \Log::info('Tool search dipanggil dengan query: ', (array) $request);
        $query = $request['query'] ?? '';
        $city  = $request['city'] ?? null;

        $results = BoardingHouse::query()
            ->with([
                'rooms:id,boarding_house_id,type_name,price_per_month,image_url',
                'reviews:id,boarding_house_id,rating',
            ])
            ->where(function (Builder $q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('city', 'like', "%{$query}%")
                    ->orWhere('address', 'like', "%{$query}%")
                    ->orWhere('description', 'like', "%{$query}%");
            })
            ->when($city, fn(Builder $q) => $q->where('city', 'like', "%{$city}%"))
            ->limit(4)
            ->get()
            ->map(fn($house) => $this->formatHouse($house))
            ->toArray();

        if (empty($results)) {
            return json_encode([
                'found'    => false,
                'message'  => "Tidak ditemukan kos yang cocok dengan pencarian '{$query}'.",
                'results'  => [],
            ]);
        }

        return json_encode([
            'found'   => true,
            'count'   => count($results),
            'results' => $results,
        ]);
    }

    private function formatHouse(BoardingHouse $house): array
    {
        $minPrice = $house->rooms->min('price_per_month') ?? 0;
        $avgRating = $house->reviews->avg('rating');

        return [
            'id'         => $house->id,
            'name'       => $house->name,
            'city'       => $house->city,
            'address'    => $house->address,
            'gender'     => $house->gender_type,
            'min_price'  => $minPrice,
            'price_text' => 'Rp ' . number_format($minPrice, 0, ',', '.') . '/bulan',
            'rating'     => $avgRating ? round($avgRating, 1) : null,
            'image_url'  => $house->rooms->first()?->image_url,
            'detail_url' => route('kos.show', $house->id),
        ];
    }
}

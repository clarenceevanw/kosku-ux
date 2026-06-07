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
            'query' => $schema->string()->description('Kata kunci pencarian umum: nama kos atau alamat. Contoh: "Kos Eksklusif", "Darmo".')->nullable(),
            'city'  => $schema->string()->description('Filter berdasarkan kota secara spesifik. Contoh: "Surabaya", "Jakarta Selatan", "Bandung".')->nullable(),
            'landmark_name' => $schema->string()->description('Nama kampus, stasiun, atau mall jika ingin mencari di sekitar lokasi tersebut. Contoh: "ITS", "Stasiun Gubeng", "Universitas Kristen Petra". Jika pengguna menyebut "dekat ITS", masukkan "ITS" ke sini.')->nullable(),
        ];
    }

    /**
     * Execute the tool and return results to the AI as a JSON string.
     */
    public function handle(Request $request): Stringable|string
    {
        \Log::info('Tool search dipanggil dengan query: ', (array) $request);
        
        $filters = [
            'q'    => $request['query'] ?? null,
            'city' => $request['city'] ?? null,
        ];

        // Resolusi nama landmark menjadi landmark_id agar fitur Haversine di Service aktif
        if (!empty($request['landmark_name'])) {
            $landmark = \App\Models\Landmark::where('name', 'like', "%{$request['landmark_name']}%")->first();
            if ($landmark) {
                $filters['landmark_id'] = $landmark->id;
            } else {
                // Jika tidak ketemu di DB, gabungkan ke keyword search biasa
                $filters['q'] = trim($filters['q'] . ' ' . $request['landmark_name']);
            }
        }

        // Pendelegasian murni ke layer Service agar semua logic (termasuk radius) terpakai
        $paginator = app(\App\Services\BoardingHouseService::class)->searchBoardingHouses($filters);

        // Ambil 4 hasil teratas
        $results = collect($paginator->items())
            ->take(4)
            ->map(fn($house) => $this->formatHouse($house))
            ->toArray();

        if (empty($results)) {
            $qStr = implode(', ', array_filter([$request['query'] ?? null, $request['landmark_name'] ?? null]));
            return json_encode([
                'found'    => false,
                'message'  => "Tidak ditemukan kos yang cocok dengan pencarian '{$qStr}'.",
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
            'location'   => ($house->district?->name ?? '') . ', ' . ($house->district?->city?->name ?? ''),
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

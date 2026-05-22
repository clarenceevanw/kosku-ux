<?php

namespace App\Ai\Tools;

use App\Models\BoardingHouse;
use Illuminate\Contracts\JsonSchema\JsonSchema;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Tools\Request;
use Stringable;

/**
 * FilterByBudgetTool
 *
 * Digunakan KosBotAgent untuk menyaring kos berdasarkan rentang harga per bulan.
 * AI akan memanggil tool ini ketika user menyebutkan budget (misal "max 1.5jt").
 */
class FilterByBudgetTool implements Tool
{
    public function name(): string
    {
        return 'filter_by_budget';
    }

    public function description(): string
    {
        return 'Mencari kos dari database KosKu berdasarkan rentang budget/harga sewa per bulan. '
            . 'Gunakan tool ini ketika pengguna menyebutkan budget, harga maksimum, atau rentang harga.';
    }

    public function schema(JsonSchema $schema): array
    {
        return [
            'min_price' => $schema->integer()->description('Harga minimum per bulan dalam Rupiah. Contoh: 500000 untuk Rp 500.000.')->nullable(),
            'max_price' => $schema->integer()->description('Harga maksimum per bulan dalam Rupiah. Contoh: 1500000 untuk Rp 1.500.000.')->nullable(),
            'city'      => $schema->string()->description('Filter tambahan berdasarkan kota. Contoh: "Surabaya".')->nullable(),
            'gender'    => $schema->string()->description('Filter berdasarkan tipe kos: "putra", "putri", atau "campur".')->nullable(),
        ];
    }

    public function handle(Request $request): Stringable|string
    {
        \Log::info('Isi Request dari AI: ', (array) $request);
        $min_price = isset($request['min_price']) ? (int) $request['min_price'] : null;
        $max_price = isset($request['max_price']) ? (int) $request['max_price'] : null;
        $city      = $request['city'] ?? null;
        $gender    = $request['gender'] ?? null;
        $results = BoardingHouse::query()
            ->with([
                'rooms:id,boarding_house_id,type_name,price_per_month,image_url',
                'reviews:id,boarding_house_id,rating',
            ])
            ->when($city, fn(Builder $q) => $q->where('city', 'like', "%{$city}%"))
            ->when($gender, fn(Builder $q) => $q->where('gender_type', $gender))
            ->whereHas('rooms', function (Builder $q) use ($min_price, $max_price) {
                $q->when($min_price, fn(Builder $q) => $q->where('price_per_month', '>=', $min_price))
                    ->when($max_price, fn(Builder $q) => $q->where('price_per_month', '<=', $max_price));
            })
            ->limit(4)
            ->get()
            ->map(function ($house) use ($min_price, $max_price) {
                return $this->formatHouse($house, $min_price, $max_price);
            })
            ->toArray();

        if (empty($results)) {
            $priceRange = [];
            if ($min_price) $priceRange[] = 'mulai Rp ' . number_format($min_price, 0, ',', '.');
            if ($max_price) $priceRange[] = 'maksimal Rp ' . number_format($max_price, 0, ',', '.');

            return json_encode([
                'found'   => false,
                'message' => 'Tidak ada kos yang tersedia dengan budget ' . implode(' - ', $priceRange) . '.',
                'results' => [],
            ]);
        }

        return json_encode([
            'found'   => true,
            'count'   => count($results),
            'results' => $results,
        ]);
    }

    private function formatHouse(BoardingHouse $house, ?int $minFilter, ?int $maxFilter): array
    {
        // Show rooms within the budget filter
        $rooms = $house->rooms
            ->when($minFilter, fn($c) => $c->where('price_per_month', '>=', $minFilter))
            ->when($maxFilter, fn($c) => $c->where('price_per_month', '<=', $maxFilter));

        $minPrice = $rooms->min('price_per_month') ?? $house->rooms->min('price_per_month') ?? 0;
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

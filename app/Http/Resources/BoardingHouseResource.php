<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * BoardingHouseResource
 *
 * The primary data-transformation layer for boarding houses.
 * All formatting logic (Rupiah, ratings, derived fields) lives HERE,
 * never in the Controller or the Blade template.
 */
class BoardingHouseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // ── Price: extract the minimum room price ──────────────────────────
        $rooms = $this->whenLoaded('rooms');
        $minPrice = null;
        $minPriceFormatted = null;
        $primaryImage = null;
        $availableStock = 0;

        if ($rooms instanceof \Illuminate\Support\Collection && $rooms->isNotEmpty()) {
            $cheapestRoom  = $rooms->sortBy('price_per_month')->first();
            $minPrice      = $cheapestRoom->price_per_month;
            $minPriceFormatted = $this->formatRupiah($minPrice);
            $primaryImage  = $cheapestRoom->image_url;
            $availableStock = $rooms->sum('stock');
        }

        // ── Reviews: calculate average rating ─────────────────────────────
        $reviews    = $this->whenLoaded('reviews');
        $avgRating  = null;
        $ratingFormatted = null;
        $reviewCount = 0;

        if ($reviews instanceof \Illuminate\Support\Collection && $reviews->isNotEmpty()) {
            $reviewCount      = $reviews->count();
            $avgRating        = round($reviews->avg('rating'), 1);
            $ratingFormatted  = number_format($avgRating, 1);
        }

        // ── Facilities: first 3 for card display ──────────────────────────
        $facilities = $this->whenLoaded('facilities');
        $facilityPreview = [];
        $allFacilityIds = [];
        
        if ($facilities instanceof \Illuminate\Support\Collection) {
            $facilityPreview = FacilityResource::collection($facilities->take(3))->resolve();
            $allFacilityIds = $facilities->pluck('id')->toArray();
        }

        // Collect room facilities for filtering
        if ($this->relationLoaded('rooms') && $this->rooms) {
            foreach ($this->rooms as $room) {
                if ($room->relationLoaded('facilities') && $room->facilities) {
                    $allFacilityIds = array_merge($allFacilityIds, $room->facilities->pluck('id')->toArray());
                }
            }
        }
        $allFacilityIds = array_values(array_unique($allFacilityIds));

        // ── Gender badge ───────────────────────────────────────────────────
        $genderLabel = match ($this->gender_type?->value ?? $this->gender_type) {
            'putra'  => 'Khusus Putra',
            'putri'  => 'Khusus Putri',
            'campur' => 'Campur',
            default  => 'Campur',
        };

        return [
            'id'                   => $this->id,
            'name'                 => $this->name,
            'description'          => $this->description,
            'address'              => $this->address,

            // Normalized regional data — requires 'district.city.province' eager-loaded
            'district_name'        => $this->district?->name,
            'city'                 => $this->district?->city?->name,
            'province'             => $this->district?->city?->province?->name,

            'gender_type'          => $this->gender_type?->value ?? $this->gender_type,
            'gender_label'         => $genderLabel,
            'latitude'             => $this->latitude,
            'longitude'            => $this->longitude,

            // Price
            'min_price'            => $minPrice,
            'min_price_formatted'  => $minPriceFormatted,

            // Image (primary room image)
            'primary_image'        => $primaryImage,

            // Stock
            'available_stock'      => $availableStock,

            // Rating
            'avg_rating'           => $avgRating,
            'rating_formatted'     => $ratingFormatted,
            'review_count'         => $reviewCount,

            // Facility preview for cards
            'facility_preview'     => $facilityPreview,
            
            // All facility IDs for filtering
            'all_facility_ids'     => $allFacilityIds,

            // Owner verified badge — for cards
            'owner_is_verified'    => $this->relationLoaded('owner') && $this->owner?->is_verified,

            // All facilities (full detail page)
            'facilities'           => FacilityResource::collection($this->whenLoaded('facilities')),

            // Rooms (full detail page)
            'rooms'                => $this->when(
                $this->relationLoaded('rooms'),
                fn () => RoomResource::collection($this->rooms)->resolve()
            ),

            // Owner (detail page)
            'owner'                => $this->when(
                $this->relationLoaded('owner'),
                fn () => [
                    'id'           => $this->owner->id,
                    'name'         => $this->owner->name,
                    'phone_number' => $this->owner->phone_number,
                    'is_verified'  => (bool) $this->owner->is_verified,
                ]
            ),

            // Reviews (detail page)
            'reviews' => $this->when(
                $this->relationLoaded('reviews'),
                fn () => $this->reviews->map(fn ($r) => [
                    'id'          => $r->id,
                    'rating'      => $r->rating,
                    'comment'     => $r->comment,
                    'tenant_name' => $r->relationLoaded('tenant') ? $r->tenant->name : null,
                    'created_at'  => $r->created_at?->translatedFormat('d M Y'),
                ])
            ),

            // Rules (detail page)
            'rules' => $this->when(
                $this->relationLoaded('rules'),
                fn () => $this->rules->map(fn ($rule) => ['category' => $rule->category, 'name' => $rule->name, 'icon' => $rule->icon])
            ),
        ];
    }

    /**
     * Format a number as Indonesian Rupiah string.
     * e.g. 1500000 → "Rp 1.500.000"
     */
    private function formatRupiah(int $amount): string
    {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

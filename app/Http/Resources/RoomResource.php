<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * RoomResource
 *
 * Transforms a single Room model for use in Blade views.
 * Handles price formatting, size display, and stock availability.
 */
class RoomResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'type_name'       => $this->type_name,
            'price_per_month' => $this->price_per_month,
            'price_formatted' => $this->formatRupiah($this->price_per_month),
            'stock'           => $this->stock,
            'is_available'    => $this->stock > 0,
            'size'            => $this->size,
            'image_url'       => $this->image_url,
            'facilities'      => FacilityResource::collection($this->whenLoaded('facilities')),
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

<?php

namespace App\Services;

use App\Enum\FacilityType;
use App\Models\BoardingHouse;
use App\Models\Facility;
use App\Models\Rule;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Collection as SupportCollection;

/**
 * BoardingHouseService — the ONLY layer allowed to touch Eloquent.
 *
 * All database queries are encapsulated here following the
 * "Fat Service, Skinny Controller" clean architecture pattern.
 */
class BoardingHouseService
{
    /**
     * Retrieve the 6 most recently created boarding houses for the home page
     * recommendations section. Eagerly loads relationships needed by the Resource.
     */
    public function getHomeRecommendations(): Collection
    {
        return BoardingHouse::query()
            ->with([
                'rooms:id,boarding_house_id,type_name,price_per_month,stock,size,image_url',
                'facilities:id,name,icon',
                'reviews:id,boarding_house_id,rating',
            ])
            ->latest()
            ->limit(6)
            ->get();
    }

    /**
     * Return all distinct cities that have at least one boarding house,
     * ordered alphabetically. Used to populate the city filter checkboxes.
     */
    public function getAllCities(): SupportCollection
    {
        return BoardingHouse::query()
            ->select('city')
            ->distinct()
            ->orderBy('city')
            ->pluck('city');
    }

    /**
     * Return all available facilities, optionally filtered by FacilityType.
     * Ordered alphabetically within each type.
     */
    public function getAllFacilities(?FacilityType $type = null): Collection
    {
        $query = Facility::orderBy('name');
        if ($type !== null) {
            $query->where('type', $type->value);
        }
        return $query->get(['id', 'name', 'type', 'icon']);
    }

    /**
     * Return all facilities keyed by type for easy view rendering.
     * Returns ['bersama' => Collection, 'ruang' => Collection]
     */
    public function getAllFacilitiesByType(): array
    {
        $all = Facility::orderBy('name')->get(['id', 'name', 'type', 'icon']);
        return [
            FacilityType::BERSAMA->value => $all->where('type', FacilityType::BERSAMA),
            FacilityType::RUANG->value   => $all->where('type', FacilityType::RUANG),
        ];
    }

    /**
     * Return all master rules grouped by category.
     * Used to render the "Aturan Kos" filter section with grouped checkboxes.
     */
    public function getAllRules(): SupportCollection
    {
        return Rule::orderBy('category')
                   ->orderBy('name')
                   ->get(['id', 'category', 'name', 'icon'])
                   ->groupBy('category');
    }

    /**
     * Search boarding houses with multi-dimensional filters.
     * Returns a paginated result for the search results page.
     *
     * @param  array{
     *   q: string|null,
     *   gender_type: string|null,
     *   city: string|null,
     *   min_price: int|null,
     *   max_price: int|null,
     *   facilities: array|null,
     *   room_facilities: array|null,
     *   rule_categories: array|null,
     * }  $filters
     */
    public function searchBoardingHouses(array $filters): LengthAwarePaginator
    {
        $query = BoardingHouse::query()
            ->with([
                'rooms:id,boarding_house_id,type_name,price_per_month,stock,size,image_url',
                'facilities:id,name,icon',
                'reviews:id,boarding_house_id,rating',
            ]);

        // ── Keyword search (name, city, address, description) ───────────────
        if (! empty($filters['q'])) {
            $keyword = $filters['q'];
            $query->where(function (Builder $q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('city', 'like', "%{$keyword}%")
                  ->orWhere('address', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        // ── City filter (exact match — values sourced from DB dropdown) ──────
        if (! empty($filters['city'])) {
            $query->where('city', $filters['city']);
        }

        // ── Gender type filter ──────────────────────────────────────────────
        if (! empty($filters['gender_type'])) {
            $query->where('gender_type', $filters['gender_type']);
        }

        // ── Price range filter (via rooms relationship) ─────────────────────
        if (! empty($filters['min_price']) || ! empty($filters['max_price'])) {
            $query->whereHas('rooms', function (Builder $q) use ($filters) {
                if (! empty($filters['min_price'])) {
                    $q->where('price_per_month', '>=', (int) $filters['min_price']);
                }
                if (! empty($filters['max_price'])) {
                    $q->where('price_per_month', '<=', (int) $filters['max_price']);
                }
            });
        }

        // ── Shared facility filter (boarding-house-level facilities) ─────────
        // Boarding house MUST have ALL selected shared facilities.
        if (! empty($filters['facilities'])) {
            foreach ($filters['facilities'] as $facilityId) {
                $query->whereHas('facilities', function (Builder $q) use ($facilityId) {
                    $q->where('facilities.id', $facilityId);
                });
            }
        }

        // ── Room facility filter (room-level facilities) ─────────────────────
        // At least one room in the boarding house must have each selected facility.
        if (! empty($filters['room_facilities'])) {
            foreach ($filters['room_facilities'] as $facilityId) {
                $query->whereHas('rooms', function (Builder $q) use ($facilityId) {
                    $q->whereHas('facilities', function (Builder $q2) use ($facilityId) {
                        $q2->where('facilities.id', $facilityId);
                    });
                });
            }
        }

        // ── Aturan Kos filter (via rule_id pivot) ────────────────────────────
        // Boarding house MUST have all selected rules attached.
        if (! empty($filters['rule_categories'])) {
            foreach ($filters['rule_categories'] as $category) {
                $query->whereHas('rules', function (Builder $q) use ($category) {
                    $q->where('rules.category', $category);
                });
            }
        }

        return $query
            ->latest()
            ->paginate(9)
            ->withQueryString();
    }

    /**
     * Retrieve a single boarding house with ALL its related data
     * for the detail page. Throws ModelNotFoundException if not found.
     */
    public function getBoardingHouseDetails(string $id): BoardingHouse
    {
        return BoardingHouse::with([
            'owner:id,name,phone_number',
            'rooms',
            'rooms.facilities:id,name,icon',
            'facilities:id,name,icon',
            'rules:id,category,name,icon',
            'reviews.tenant:id,name',
        ])->findOrFail($id);
    }
}

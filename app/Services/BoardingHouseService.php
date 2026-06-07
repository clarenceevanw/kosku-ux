<?php

namespace App\Services;

use App\Enum\FacilityType;
use App\Models\BoardingHouse;
use App\Models\City;
use App\Models\Facility;
use App\Models\Landmark;
use App\Models\Rule;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
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
                'owner:id,name,phone_number,is_verified',
                'district.city.province',
                'rooms:id,boarding_house_id,type_name,price_per_month,stock,size,image_url',
                'facilities:id,name,icon',
                'reviews:id,boarding_house_id,rating',
            ])
            ->latest()
            ->limit(6)
            ->get();
    }

    /**
     * Return all distinct cities that have at least one boarding house.
     * Now queries the normalized cities table via district join.
     */
    public function getAllCities(): SupportCollection
    {
        return City::query()
            ->whereHas('districts.boardingHouses')
            ->orderBy('name')
            ->pluck('name');
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
     *   district_id: string|null,
     *   landmark_id: string|null,
     *   min_price: int|null,
     *   max_price: int|null,
     *   facilities: array|null,
     *   room_facilities: array|null,
     *   rule_categories: array|null,
     * }  $filters
     */
    public function searchBoardingHouses(array $filters): LengthAwarePaginator
    {
        // ── Resolve Landmark for radius search ──────────────────────────────
        $landmark = null;
        if (! empty($filters['landmark_id'])) {
            $landmark = Landmark::find($filters['landmark_id']);
        }

        // ── Base query with eager loads ─────────────────────────────────────
        $query = BoardingHouse::query()
            ->with([
                'owner:id,name,phone_number,is_verified',
                'district.city.province',
                'rooms:id,boarding_house_id,type_name,price_per_month,stock,size,image_url',
                'facilities:id,name,icon',
                'reviews:id,boarding_house_id,rating',
            ]);

        // ── Haversine distance select (only when landmark search active) ────
        if ($landmark !== null) {
            $lat = (float) $landmark->latitude;
            $lng = (float) $landmark->longitude;

            // Haversine formula returns distance in km.
            // 6371 = Earth radius in km.
            $haversine = "
                ( 6371 * ACOS(
                    LEAST(1.0, COS(RADIANS(?))
                    * COS(RADIANS(boarding_houses.latitude))
                    * COS(RADIANS(boarding_houses.longitude) - RADIANS(?))
                    + SIN(RADIANS(?))
                    * SIN(RADIANS(boarding_houses.latitude)))
                ) )
            ";

            $query->selectRaw("boarding_houses.*, {$haversine} AS distance", [$lat, $lng, $lat])
                  ->whereNotNull('boarding_houses.latitude')
                  ->whereNotNull('boarding_houses.longitude')
                  ->whereRaw("{$haversine} <= ?", [$lat, $lng, $lat, 3.0])  // 3 km radius
                  ->orderByRaw("distance ASC");
        }

        // ── Keyword search (name, address, description, district name) ──────
        if (! empty($filters['q'])) {
            $keyword = $filters['q'];
            $query->where(function (Builder $q) use ($keyword) {
                $q->where('boarding_houses.name', 'like', "%{$keyword}%")
                  ->orWhere('boarding_houses.address', 'like', "%{$keyword}%")
                  ->orWhere('boarding_houses.description', 'like', "%{$keyword}%")
                  ->orWhereHas('district', function (Builder $dq) use ($keyword) {
                      $dq->where('name', 'like', "%{$keyword}%");
                  })
                  ->orWhereHas('district.city', function (Builder $cq) use ($keyword) {
                      $cq->where('name', 'like', "%{$keyword}%");
                  });
            });
        }

        // ── District filter (exact BPS code) ────────────────────────────────
        if (! empty($filters['district_id'])) {
            $query->where('district_id', $filters['district_id']);
        }

        // ── City filter (via normalized district → city join) ────────────────
        if (! empty($filters['city'])) {
            $query->whereHas('district.city', function (Builder $q) use ($filters) {
                $q->where('name', $filters['city']);
            });
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

        // Default ordering only when NOT in landmark/distance mode
        if ($landmark === null) {
            $query->latest();
        }

        return $query
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
            'owner:id,name,phone_number,is_verified',
            'district.city.province',
            'district.landmarks',          // nearby landmarks for proximity display
            'rooms',
            'rooms.facilities:id,name,icon',
            'facilities:id,name,icon',
            'rules:id,category,name,icon',
            'reviews.tenant:id,name',
        ])->findOrFail($id);
    }

    /**
     * Fetch a single landmark by ID for display context in the search results.
     * Returns null if not found (graceful degradation).
     */
    public function getActiveLandmark(string $landmarkId): ?\App\Models\Landmark
    {
        return \App\Models\Landmark::with('district.city')
            ->find($landmarkId);
    }

    /**
     * Fetch a single district by ID for display context.
     */
    public function getActiveDistrict(string $districtId): ?\App\Models\District
    {
        return \App\Models\District::with('city')
            ->find($districtId);
    }
}

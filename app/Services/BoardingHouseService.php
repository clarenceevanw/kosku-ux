<?php

namespace App\Services;

use App\Models\BoardingHouse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

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
    public function getHomeRecommendations(): \Illuminate\Database\Eloquent\Collection
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
     * Search boarding houses by keyword (name, city, or address).
     * Returns a paginated result for the search results page.
     * Eagerly loads the minimum-price room for display.
     *
     * @param  array{q: string|null}  $filters
     */
    public function searchBoardingHouses(array $filters): LengthAwarePaginator
    {
        $query = BoardingHouse::query()
            ->with([
                'rooms:id,boarding_house_id,type_name,price_per_month,stock,size,image_url',
                'facilities:id,name,icon',
                'reviews:id,boarding_house_id,rating',
            ]);

        if (! empty($filters['q'])) {
            $keyword = $filters['q'];
            $query->where(function (Builder $q) use ($keyword) {
                $q->where('name', 'like', "%{$keyword}%")
                  ->orWhere('city', 'like', "%{$keyword}%")
                  ->orWhere('address', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%");
            });
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
            'rules:id,boarding_house_id,category,rule_text',
            'reviews.tenant:id,name',
        ])->findOrFail($id);
    }
}

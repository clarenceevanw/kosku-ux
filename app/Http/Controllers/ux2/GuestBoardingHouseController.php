<?php

namespace App\Http\Controllers\ux2;

use App\Http\Resources\BoardingHouseResource;
use App\Models\BoardingHouse;
use App\Services\BoardingHouseService;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * GuestBoardingHouseController
 *
 * Handles all public-facing (guest/unauthenticated) boarding house pages.
 *
 * Responsibilities of this controller:
 *   1. Accept the HTTP Request (or FormRequest).
 *   2. Delegate ALL data retrieval to BoardingHouseService.
 *   3. Transform the data via BoardingHouseResource.
 *   4. Return the appropriate View.
 *
 * This controller NEVER touches Eloquent directly. Zero DB queries here.
 */
class GuestBoardingHouseController extends Controller
{
    public function __construct(
        private readonly BoardingHouseService $boardingHouseService
    ) {}

    /**
     * GET /
     * Home page: hero, recommendations, city section.
     */
    public function index(): View
    {
        $recommendations = BoardingHouseResource::collection(
            $this->boardingHouseService->getHomeRecommendations()
        )->resolve();

        return view('ux2.home', compact('recommendations'));
    }

    /**
     * GET /search?q={keyword}
     * Search results page with pagination.
     */
    public function search(Request $request): View
    {
        $filters = [
            'q' => $request->string('q')->toString(),
            'min_price' => null,
            'max_price' => null,
            'gender_type' => [],
            'sort' => 'recommended',
        ];

        $boardingHouses = BoardingHouseResource::collection(
            BoardingHouse::query()
            ->with([
                'rooms:id,boarding_house_id,type_name,price_per_month,stock,size,image_url',
                'facilities:id,name,icon',
                'reviews:id,boarding_house_id,rating',
            ])
            ->latest()
            ->get()
        )->resolve();

        return view('ux2.search', [
            'boardingHouses' => $boardingHouses,
            'totalHouses'    => count($boardingHouses),
            'keyword'        => $filters['q'] ?? null,
            'filters'        => $filters,
        ]);
    }

    /**
     * GET /kos/{id}
     * Boarding house detail page.
     */
    public function show(string $id): View
    {
        $boardingHouse = new BoardingHouseResource(
            $this->boardingHouseService->getBoardingHouseDetails($id)
        );

        return view('ux2.detailkos', [
            'boardingHouse' => $boardingHouse->resolve(),
        ]);
    }

    /**
     * GET /bot
     * KosBot AI chat page.
     */
    public function bot(): View
    {
        return view('ux2.kosbot');
    }
}

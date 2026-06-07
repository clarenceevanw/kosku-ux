<?php

namespace App\Http\Controllers;

use App\Http\Requests\SearchBoardingHouseRequest;
use App\Http\Resources\BoardingHouseResource;
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

        return view('home', compact('recommendations'));
    }

    /**
     * GET /search?q={keyword}&city={city}&gender_type={type}&min_price={min}&max_price={max}
     *          &facilities[]={id}&room_facilities[]={id}&rule_categories[]={cat}
     * Search results page with full multi-filter support.
     */
    public function search(SearchBoardingHouseRequest $request): View
    {
        $filters   = $request->filters();
        $paginator = $this->boardingHouseService->searchBoardingHouses($filters);

        // Transform each item through the resource while preserving pagination metadata
        $boardingHouses = BoardingHouseResource::collection($paginator)->resolve();

        // Resolve location context for result header badge
        $activeLandmark = null;
        $activeDistrict = null;
        if (! empty($filters['landmark_id'])) {
            $activeLandmark = $this->boardingHouseService->getActiveLandmark($filters['landmark_id']);
        } elseif (! empty($filters['district_id'])) {
            $activeDistrict = $this->boardingHouseService->getActiveDistrict($filters['district_id']);
        }

        // All lookup data — zero Eloquent in this controller
        $cities           = $this->boardingHouseService->getAllCities();
        $facilitiesByType = $this->boardingHouseService->getAllFacilitiesByType();
        $rules            = $this->boardingHouseService->getAllRules();

        return view('search', [
            'boardingHouses'   => $boardingHouses,
            'paginator'        => $paginator,
            'keyword'          => $filters['q'] ?? null,
            'cities'           => $cities,
            'facilitiesByType' => $facilitiesByType,
            'rules'            => $rules,
            'activeFilters'    => $filters,
            'activeLandmark'   => $activeLandmark,   // Landmark model or null
            'activeDistrict'   => $activeDistrict,   // District model or null
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

        return view('kos-detail', [
            'boardingHouse' => $boardingHouse->resolve(),
        ]);
    }

    /**
     * GET /bot
     * KosBot AI chat page.
     */
    public function bot(): View
    {
        return view('kosbot');
    }
}

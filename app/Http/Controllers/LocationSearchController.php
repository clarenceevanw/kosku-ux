<?php

namespace App\Http\Controllers;

use App\Models\District;
use App\Models\Landmark;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * LocationSearchController
 *
 * Provides instant autocomplete suggestions for the navbar search bar.
 * Searches both Districts (Areas) and Landmarks (Campus/Station/Mall)
 * and returns results grouped by category, ready for the frontend dropdown.
 */
class LocationSearchController extends Controller
{
    private const MAX_PER_GROUP = 4;
    private const MIN_QUERY_LEN = 2;

    /**
     * GET /api/location/suggest?q={query}
     *
     * Returns grouped suggestions:
     *   - "Kampus"           — Landmark type: campus
     *   - "Stasiun & Halte" — Landmark type: station
     *   - "Mall"            — Landmark type: mall
     *   - "Area / Kawasan"  — Districts
     */
    public function suggest(Request $request): JsonResponse
    {
        $q = trim($request->input('q', ''));

        // Return empty early — avoids noise for very short queries
        if (mb_strlen($q) < self::MIN_QUERY_LEN) {
            return response()->json(['suggestions' => []]);
        }

        // ── Landmarks: campus, station, mall ──────────────────────────────
        $landmarks = Landmark::query()
            ->where('name', 'like', "%{$q}%")
            ->with('district:id,city_id,name', 'district.city:id,name')
            ->select('id', 'name', 'type', 'district_id', 'latitude', 'longitude')
            ->orderBy('name')
            ->limit(self::MAX_PER_GROUP * 3)
            ->get();

        // ── Districts ─────────────────────────────────────────────────────
        $districts = District::query()
            ->where('name', 'like', "%{$q}%")
            ->with('city:id,name')
            ->select('id', 'city_id', 'name')
            ->orderBy('name')
            ->limit(self::MAX_PER_GROUP)
            ->get();

        // ── Group and format ──────────────────────────────────────────────
        $groups = [];

        // Campus group
        $campuses = $landmarks->where('type.value', 'campus')
            ->take(self::MAX_PER_GROUP)
            ->values();

        if ($campuses->isNotEmpty()) {
            $groups[] = [
                'label' => 'Kampus',
                'icon'  => 'school',
                'items' => $campuses->map(fn($l) => $this->formatLandmark($l))->values(),
            ];
        }

        // Station group
        $stations = $landmarks->where('type.value', 'station')
            ->take(self::MAX_PER_GROUP)
            ->values();

        if ($stations->isNotEmpty()) {
            $groups[] = [
                'label' => 'Stasiun & Halte',
                'icon'  => 'train',
                'items' => $stations->map(fn($l) => $this->formatLandmark($l))->values(),
            ];
        }

        // Mall group
        $malls = $landmarks->where('type.value', 'mall')
            ->take(self::MAX_PER_GROUP)
            ->values();

        if ($malls->isNotEmpty()) {
            $groups[] = [
                'label' => 'Pusat Perbelanjaan',
                'icon'  => 'shopping_bag',
                'items' => $malls->map(fn($l) => $this->formatLandmark($l))->values(),
            ];
        }

        // District / Area group
        if ($districts->isNotEmpty()) {
            $groups[] = [
                'label' => 'Area / Kawasan',
                'icon'  => 'location_on',
                'items' => $districts->map(fn($d) => [
                    'type'        => 'district',
                    'id'          => $d->id,
                    'name'        => $d->name,
                    'subtitle'    => $d->city?->name ?? '',
                    'param_key'   => 'district_id',
                    'param_value' => $d->id,
                ])->values(),
            ];
        }

        return response()->json([
            'suggestions' => $groups,
            'query'       => $q,
        ]);
    }

    // ── Private helpers ────────────────────────────────────────────────────

    private function formatLandmark(Landmark $landmark): array
    {
        return [
            'type'        => 'landmark',
            'id'          => $landmark->id,
            'name'        => $landmark->name,
            'subtitle'    => implode(', ', array_filter([
                $landmark->district?->name,
                $landmark->district?->city?->name,
            ])),
            'landmark_type' => $landmark->type?->value,
            'param_key'   => 'landmark_id',
            'param_value' => $landmark->id,
        ];
    }
}

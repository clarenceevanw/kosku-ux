<?php

namespace App\Http\Controllers\ux2\Owner;

use App\Http\Controllers\Controller;
use App\Services\Owner\DashboardService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboardService)
    {
    }

    public function index(Request $request): View
    {
        $ownerId = auth()->id();
        $kosId = $request->query('kos_id');
        
        $boardingHouses = \App\Models\BoardingHouse::byOwner($ownerId)->get(['id', 'name']);
        
        $stats = $this->dashboardService->getSummaryStats($ownerId, $kosId);
        $recentTickets = $this->dashboardService->getRecentTickets($ownerId, $kosId);
        
        $visualRooms = collect();
        if ($kosId) {
            $visualRooms = $this->dashboardService->getRoomsByKos($kosId, $ownerId);
        }

        $occupancyTrends = $this->dashboardService->getOccupancyTrends($ownerId, $kosId);

        return view('ux2.owner.dashboard', [
            'boardingHouses'    => $boardingHouses,
            'selectedKosId'     => $kosId,
            'totalPendapatan'   => $stats['totalPendapatan'],
            'kamarTerisi'       => $stats['kamarTerisi'],
            'laporanAktif'      => $stats['laporanAktif'],
            'tagihanBelumLunas' => $stats['tagihanBelumLunas'],
            'recentTickets'     => $recentTickets,
            'visualRooms'       => $visualRooms,
            'occupancyTrends'   => $occupancyTrends,
        ]);
    }
}

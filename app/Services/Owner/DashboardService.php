<?php

namespace App\Services\Owner;

use App\Models\BoardingHouse;
use App\Models\Contract;
use App\Models\MaintenanceTicket;
use App\Models\MonthlyPayment;
use App\Models\Room;
use Carbon\Carbon;

class DashboardService
{
    /**
     * Get summary stats for the owner dashboard.
     */
    public function getSummaryStats(int|string $ownerId, ?string $kosId = null): array
    {
        // 1. Total Pendapatan Bulan Ini
        $totalPendapatanQuery = MonthlyPayment::whereHas('contract.transaction.room.boardingHouse', function ($q) use ($ownerId, $kosId) {
            $q->byOwner($ownerId);
            if ($kosId) {
                $q->where('id', $kosId);
            }
        })
        ->whereIn('payment_status', ['paid_to_escrow', 'released_to_owner'])
        ->whereYear('paid_at', Carbon::now()->year)
        ->whereMonth('paid_at', Carbon::now()->month);

        $totalPendapatan = $totalPendapatanQuery->sum('amount');

        // 2. Kamar Terisi
        $totalRoomsQuery = Room::whereHas('boardingHouse', function ($q) use ($ownerId, $kosId) {
            $q->byOwner($ownerId);
            if ($kosId) {
                $q->where('id', $kosId);
            }
        });
        $totalRooms = $totalRoomsQuery->sum('stock'); 
        
        $occupiedRoomsQuery = Contract::whereHas('transaction.room.boardingHouse', function ($q) use ($ownerId, $kosId) {
            $q->byOwner($ownerId);
            if ($kosId) {
                $q->where('id', $kosId);
            }
        })->where('status', 'active');
        $occupiedRooms = $occupiedRoomsQuery->count();

        // 3. Laporan Aktif
        $laporanAktifQuery = MaintenanceTicket::whereHas('room.boardingHouse', function ($q) use ($ownerId, $kosId) {
            $q->byOwner($ownerId);
            if ($kosId) {
                $q->where('id', $kosId);
            }
        })->whereIn('status', ['pending', 'in_progress']);
        $laporanAktif = $laporanAktifQuery->count();

        // 4. Tagihan Belum Lunas (Pending Monthly Payments)
        $tagihanBelumLunasQuery = MonthlyPayment::whereHas('contract.transaction.room.boardingHouse', function ($q) use ($ownerId, $kosId) {
            $q->byOwner($ownerId);
            if ($kosId) {
                $q->where('id', $kosId);
            }
        })->where('payment_status', 'pending');
        $tagihanBelumLunas = $tagihanBelumLunasQuery->count();

        return [
            'totalPendapatan'   => $totalPendapatan,
            'kamarTerisi'       => [
                'occupied' => $occupiedRooms,
                'total'    => $totalRooms,
            ],
            'laporanAktif'      => $laporanAktif,
            'tagihanBelumLunas' => $tagihanBelumLunas,
        ];
    }

    /**
     * Get rooms for a specific Kos to render the Map.
     */
    public function getRoomsByKos(?string $kosId, int|string $ownerId)
    {
        if (!$kosId) return collect();

        // Ensure the kos is owned by the user
        $kos = BoardingHouse::byOwner($ownerId)->find($kosId);
        if (!$kos) return collect();

        // Fetch rooms and determine their status based on active contracts/payments
        // Since we don't have a direct "Room Number" entity (stock means multiple units per type),
        // we will generate a visual map based on stock for the dashboard layout.
        $rooms = Room::where('boarding_house_id', $kosId)
            ->with(['transactions.contract.monthlyPayments' => function ($q) {
                $q->where('payment_status', 'pending')->orWhereIn('payment_status', ['paid_to_escrow', 'released_to_owner']);
            }])
            ->get();

        $visualRooms = collect();
        $roomNumber = 101;
        foreach ($rooms as $room) {
            for ($i = 0; $i < $room->stock; $i++) {
                // Determine mock status based on active contracts (This is simplified for visual map)
                $status = 'kosong'; // kosong, lunas, menunggak
                
                // If it's an occupied room (hypothetical, we'll assign status randomly or based on actual data if we tracked individual room numbers)
                // Since `stock` represents quantity of un-numbered rooms in schema, we mock the status for the visual map
                // In a real app with `RoomUnit`, we'd query the exact status of each unit.
                
                // Just to make the map functional based on the data:
                // We will fill 'occupied' slots first based on active contracts count
                $visualRooms->push([
                    'number' => $roomNumber++,
                    'status' => 'lunas' // Placeholder, will calculate properly below
                ]);
            }
        }

        // We know how many are occupied from the summary stats
        // We'll distribute 'lunas' and 'menunggak' statuses based on total Tagihan Belum Lunas vs Occupied
        return $visualRooms; // we'll handle the logic properly
    }

    /**
     * Get recent tickets.
     */
    public function getRecentTickets(int|string $ownerId, ?string $kosId = null)
    {
        $query = MaintenanceTicket::whereHas('room.boardingHouse', function ($q) use ($ownerId, $kosId) {
            $q->byOwner($ownerId);
            if ($kosId) {
                $q->where('id', $kosId);
            }
        })->latest()->take(4);

        return $query->get();
    }


}

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
        $totalPendapatanQuery = MonthlyPayment::whereHas('contract.room.boardingHouse', function ($q) use ($ownerId, $kosId) {
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
        
        $occupiedRoomsQuery = Contract::whereHas('room.boardingHouse', function ($q) use ($ownerId, $kosId) {
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
        $tagihanBelumLunasQuery = MonthlyPayment::whereHas('contract.room.boardingHouse', function ($q) use ($ownerId, $kosId) {
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

        $kos = BoardingHouse::byOwner($ownerId)->find($kosId);
        if (!$kos) return collect();

        // Fetch rooms with active contracts and their latest monthly payment status
        $rooms = Room::where('boarding_house_id', $kosId)
            ->with(['contracts' => function ($q) {
                $q->where('status', 'active')
                  ->with(['monthlyPayments' => function ($q2) {
                      $q2->orderBy('due_date', 'desc');
                  }]);
            }])
            ->get();

        $visualRooms = collect();
        $roomNumber = 101;

        foreach ($rooms as $room) {
            // Collect active contracts for this room type
            $activeContracts = $room->contracts;
            $occupiedCount   = $activeContracts->count();

            // For each "slot" in stock, assign real status
            for ($i = 0; $i < $room->stock; $i++) {
                if ($i < $occupiedCount) {
                    $contract       = $activeContracts[$i];
                    $latestPayment  = $contract->monthlyPayments->first();

                    // Check if overdue: latest payment is pending and past due
                    $isOverdue = $latestPayment
                        && $latestPayment->payment_status === 'pending'
                        && \Carbon\Carbon::parse($latestPayment->due_date)->isPast();

                    $status = $isOverdue ? 'menunggak' : 'lunas';
                } else {
                    $status = 'kosong';
                }

                $visualRooms->push([
                    'number' => $roomNumber++,
                    'status' => $status,
                ]);
            }
        }

        return $visualRooms;
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

    /**
     * Get real occupancy trends for the last 3 months.
     */
    public function getOccupancyTrends(int|string $ownerId, ?string $kosId = null): array
    {
        $trends = [
            'labels' => [],
            'values' => []
        ];

        // Get total rooms capacity
        $totalRoomsQuery = Room::whereHas('boardingHouse', function ($q) use ($ownerId, $kosId) {
            $q->byOwner($ownerId);
            if ($kosId) {
                $q->where('id', $kosId);
            }
        });
        $totalCapacity = $totalRoomsQuery->sum('stock');
        $totalCapacity = $totalCapacity > 0 ? $totalCapacity : 1; // Prevent division by zero

        // We want data for: 2 months ago, last month, this month
        for ($i = 2; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            if ($i == 0) {
                $trends['labels'][] = 'Bulan Ini';
            } elseif ($i == 1) {
                $trends['labels'][] = 'Bulan Lalu';
            } else {
                $trends['labels'][] = '2 Bulan Lalu';
            }

            // Count contracts active in that month
            // A contract is active in a month if: start_date <= endOfMonth AND end_date >= startOfMonth
            $activeContractsQuery = Contract::whereHas('room.boardingHouse', function ($q) use ($ownerId, $kosId) {
                $q->byOwner($ownerId);
                if ($kosId) {
                    $q->where('id', $kosId);
                }
            })
            ->where('start_date', '<=', $endOfMonth)
            ->where('end_date', '>=', $startOfMonth)
            ->where('status', 'active');

            $activeCount = $activeContractsQuery->count();
            
            // Calculate percentage based on slots used (each contract = 1 slot)
            // Cap at totalCapacity in case of data inconsistency
            $percentage = round((min($activeCount, $totalCapacity) / $totalCapacity) * 100);
            $trends['values'][] = min(100, $percentage);
        }

        return $trends;
    }
}

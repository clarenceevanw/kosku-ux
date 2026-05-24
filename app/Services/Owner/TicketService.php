<?php

namespace App\Services\Owner;

use App\Models\BoardingHouse;
use App\Models\MaintenanceTicket;
use Illuminate\Http\Request;

class TicketService
{
    public function getTicketsData(string $ownerId, Request $request): array
    {
        // Fetch all boarding houses owned by this user
        $boardingHouses = BoardingHouse::byOwner($ownerId)->get();
        
        if ($boardingHouses->isEmpty()) {
            return [
                'boardingHouses' => collect(),
                'selectedKos' => null,
                'groupedTickets' => collect(),
            ];
        }

        $kosId = $request->input('kos_id');
        $selectedKos = $kosId 
            ? $boardingHouses->firstWhere('id', $kosId) 
            : $boardingHouses->first();

        if (!$selectedKos) {
            $selectedKos = $boardingHouses->first();
        }

        // Fetch tickets for this specific Kos
        $tickets = MaintenanceTicket::whereHas('room', function($q) use ($selectedKos) {
                $q->where('boarding_house_id', $selectedKos->id);
            })
            ->when($request->input('search'), function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                      ->orWhereHas('tenant', function($q2) use ($search) {
                          $q2->where('name', 'like', "%{$search}%");
                      });
                });
            })
            ->with(['room', 'tenant'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Group tickets by status for the Kanban board
        $groupedTickets = [
            'dilaporkan' => $tickets->where('status.value', \App\Enum\TicketStatus::REPORTED->value),
            'dikerjakan' => $tickets->where('status.value', \App\Enum\TicketStatus::IN_PROGRESS->value),
            'selesai'    => $tickets->where('status.value', \App\Enum\TicketStatus::RESOLVED->value),
        ];

        return [
            'boardingHouses' => $boardingHouses,
            'selectedKos' => $selectedKos,
            'groupedTickets' => $groupedTickets,
        ];
    }

    public function getTicketDetails(string $ownerId, string $id): MaintenanceTicket
    {
        return MaintenanceTicket::whereHas('room.boardingHouse', function($q) use ($ownerId) {
            $q->byOwner($ownerId);
        })
        ->with(['room.boardingHouse', 'tenant'])
        ->findOrFail($id);
    }

    public function updateTicketStatus(string $ownerId, string $id, $status): void
    {
        $ticket = MaintenanceTicket::whereHas('room.boardingHouse', function($q) use ($ownerId) {
            $q->byOwner($ownerId);
        })->findOrFail($id);

        $ticket->update(['status' => $status]);
    }
}

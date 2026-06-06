<?php

namespace App\Services\Owner;

use App\Models\BoardingHouse;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomService
{
    public function getRoomsData(string $ownerId, Request $request): array
    {
        // Fetch all boarding houses owned by this user for the dropdown
        $boardingHouses = BoardingHouse::byOwner($ownerId)->get();
        
        if ($boardingHouses->isEmpty()) {
            return [
                'boardingHouses' => collect(),
                'selectedKos' => null,
                'rooms' => collect(),
                'roomFacilities' => collect(),
            ];
        }

        // Determine which Kos to show
        $kosId = $request->input('kos_id');
        $selectedKos = $kosId 
            ? $boardingHouses->firstWhere('id', $kosId) 
            : $boardingHouses->first();

        // If an invalid ID was passed, fallback to the first one
        if (!$selectedKos) {
            $selectedKos = $boardingHouses->first();
        }

        // Fetch rooms for this Kos
        // We load facilities and transactions to determine status
        $rooms = Room::where('boarding_house_id', $selectedKos->id)
            ->when($request->input('search'), function($query, $search) {
                $query->where('type_name', 'like', "%{$search}%");
            })
            ->with(['facilities', 'contracts' => function($q) {
                $q->where('status', 'active');
            }])
            ->get();

        // Add dynamic properties for view
        foreach ($rooms as $room) {
            $activeCount = $room->contracts->count();
            
            if ($activeCount >= $room->stock) {
                $room->dynamic_status = 'Terisi Penuh';
                $room->status_color = 'bg-blue-100 text-blue-700';
                $room->status_dot = 'bg-blue-500';
            } elseif ($activeCount > 0) {
                $room->dynamic_status = "Terisi ($activeCount/{$room->stock})";
                $room->status_color = 'bg-blue-100 text-blue-700';
                $room->status_dot = 'bg-blue-500';
            } else {
                $room->dynamic_status = 'Tersedia';
                $room->status_color = 'bg-emerald-100 text-emerald-700';
                $room->status_dot = 'bg-emerald-500';
            }
        }

        $roomFacilities = \App\Models\Facility::where('type', \App\Enum\FacilityType::RUANG->value)->get();

        return [
            'boardingHouses' => $boardingHouses,
            'selectedKos' => $selectedKos,
            'rooms' => $rooms,
            'roomFacilities' => $roomFacilities,
        ];
    }

    public function createRoom(string $ownerId, array $validated, ?array $facilities = null): Room
    {
        // Ensure the boarding house belongs to the owner
        $boardingHouse = BoardingHouse::where('owner_id', $ownerId)
            ->findOrFail($validated['boarding_house_id']);

        $room = Room::create($validated);
        
        if ($facilities) {
            $room->facilities()->sync($facilities);
        }

        return $room;
    }

    public function getRoomDetails(string $ownerId, string $id): Room
    {
        return Room::whereHas('boardingHouse', function($q) use ($ownerId) {
            $q->where('owner_id', $ownerId);
        })
        ->with(['boardingHouse', 'contracts' => function($q) {
            $q->where('status', 'active')->with('tenant');
        }])
        ->findOrFail($id);
    }

    public function updateRoom(string $ownerId, string $id, array $validated, ?array $facilities = null): Room
    {
        $room = Room::whereHas('boardingHouse', function($q) use ($ownerId) {
            $q->where('owner_id', $ownerId);
        })->findOrFail($id);

        $room->update($validated);

        if ($facilities !== null) {
            $room->facilities()->sync($facilities);
        } else {
            $room->facilities()->detach();
        }

        return $room;
    }

    public function deleteRoom(string $ownerId, string $id): void
    {
        $room = Room::whereHas('boardingHouse', function($q) use ($ownerId) {
            $q->where('owner_id', $ownerId);
        })->findOrFail($id);

        // Prevent deletion if there are active contracts
        $activeContractsCount = $room->contracts()->where('status', 'active')->count();

        if ($activeContractsCount > 0) {
            throw new \Exception('Kamar tidak dapat dihapus karena masih ada penyewa aktif.');
        }

        $room->delete();
    }
}

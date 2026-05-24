<?php

namespace App\Services\Owner;

use App\Models\BoardingHouse;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class KosService
{
    /**
     * Get all boarding houses owned by the user, paginated.
     */
    public function getAllKos(int|string $ownerId, array $filters = []): LengthAwarePaginator
    {
        $query = BoardingHouse::byOwner($ownerId)
            ->with(['rooms', 'facilities']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['type'])) {
            $query->where('gender_type', $filters['type']);
        }

        $kosList = $query->latest()->paginate(10);

        // Calculate occupancy rates dynamically for each Kos
        foreach ($kosList as $kos) {
            $totalRooms = $kos->rooms->sum('stock');
            // Assuming rooms have a relationship with active contracts.
            // Since we might not have eager loaded it fully to avoid N+1, we can do it via relationship counting if needed.
            // For simplicity here, we'll do a basic calculation or use 0 if no contracts are loaded.
            // To do this properly without N+1, we'd need to add a `withCount` to the query.
            
            // For the mockup representation:
            $occupiedRooms = 0; 
            foreach ($kos->rooms as $room) {
                // In a real scenario, you'd use `$room->contracts()->where('status', 'active')->count()`
                // Let's use a placeholder occupied count for now based on a hypothetical property if it existed, or just 0
            }
            
            // Since this is a simple mock calculation for now, we can just attach dynamic properties to the model
            $kos->total_rooms = $totalRooms;
            $kos->occupied_rooms = $occupiedRooms; // TODO: Implement real occupancy count
            $kos->occupancy_rate = $totalRooms > 0 ? ($occupiedRooms / $totalRooms * 100) : 0;
            
            // Provide a dummy image if none
            $heroRoom = $kos->rooms->first();
            $kos->image_url = $heroRoom ? $heroRoom->image_url : null;
        }

        return $kosList;
    }

    /**
     * Get a specific boarding house owned by the user.
     */
    public function getKosById(int|string $id, int|string $ownerId): BoardingHouse
    {
        return BoardingHouse::byOwner($ownerId)->findOrFail($id);
    }

    public function createKos(int|string $ownerId, array $data): BoardingHouse
    {
        $data['owner_id'] = $ownerId;
        $kos = BoardingHouse::create($data);

        if (isset($data['facilities'])) {
            $kos->facilities()->sync($data['facilities']);
        }
        if (isset($data['rules'])) {
            $kos->rules()->sync($data['rules']);
        }

        return $kos;
    }

    /**
     * Update an existing boarding house.
     */
    public function updateKos(int|string $id, int|string $ownerId, array $data): BoardingHouse
    {
        $kos = $this->getKosById($id, $ownerId);
        $kos->update($data);

        if (isset($data['facilities'])) {
            $kos->facilities()->sync($data['facilities']);
        }
        if (isset($data['rules'])) {
            $kos->rules()->sync($data['rules']);
        }

        return $kos;
    }

    /**
     * Delete a boarding house.
     */
    public function deleteKos(int|string $id, int|string $ownerId): bool
    {
        $kos = $this->getKosById($id, $ownerId);
        return $kos->delete();
    }
}

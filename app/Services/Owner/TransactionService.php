<?php

namespace App\Services\Owner;

use App\Models\BoardingHouse;
use App\Models\Contract;
use Illuminate\Http\Request;

class TransactionService
{
    public function getTransactionData(string $ownerId, Request $request): array
    {
        // Fetch all boarding houses owned by this user
        $boardingHouses = BoardingHouse::byOwner($ownerId)->get();
        
        if ($boardingHouses->isEmpty()) {
            return [
                'boardingHouses' => collect(),
                'selectedKos' => null,
                'transactions' => collect(),
            ];
        }

        $kosId = $request->input('kos_id');
        $selectedKos = $kosId 
            ? $boardingHouses->firstWhere('id', $kosId) 
            : $boardingHouses->first();

        if (!$selectedKos) {
            $selectedKos = $boardingHouses->first();
        }

        $transactions = Contract::whereHas('room.boardingHouse', function($q) use ($ownerId, $selectedKos) {
            $q->where('owner_id', $ownerId)->where('id', $selectedKos->id);
        })
        ->with(['tenant', 'room.boardingHouse', 'monthlyPayments'])
        ->orderBy('created_at', 'desc')
        ->paginate(10);

        return [
            'boardingHouses' => $boardingHouses,
            'selectedKos' => $selectedKos,
            'transactions' => $transactions,
        ];
    }
}

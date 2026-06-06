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

    public function approveContract(string $ownerId, string $contractId): Contract
    {
        $contract = Contract::whereHas('room.boardingHouse', function($q) use ($ownerId) {
            $q->where('owner_id', $ownerId);
        })->findOrFail($contractId);

        if ($contract->status->value !== 'pending' && $contract->status !== 'pending') {
            throw new \Exception('Hanya kontrak pending yang dapat disetujui.');
        }

        // Get the first monthly payment
        $firstPayment = $contract->monthlyPayments()->where('billing_month', 1)->first();
        
        if (!$firstPayment || !in_array($firstPayment->payment_status->value ?? $firstPayment->payment_status, ['paid_to_escrow'])) {
            throw new \Exception('Pembayaran awal harus berstatus Escrow sebelum disetujui.');
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($contract, $firstPayment) {
            // Update contract
            $contract->update([
                'status' => \App\Enum\ContractStatus::ACTIVE->value,
                'owner_signature_date' => now(),
            ]);

            // Release escrow
            $firstPayment->update([
                'payment_status' => \App\Enum\PaymentStatus::RELEASED_TO_OWNER->value,
            ]);
        });

        return $contract;
    }
}

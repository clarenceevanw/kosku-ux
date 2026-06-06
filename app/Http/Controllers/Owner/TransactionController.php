<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Services\Owner\TransactionService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function __construct(
        protected TransactionService $transactionService
    ) {}

    public function index(Request $request): View
    {
        $ownerId = auth()->id();

        $data = $this->transactionService->getTransactionData($ownerId, $request);

        return view('owner.transactions.index', $data);
    }

    public function approve(string $id)
    {
        $ownerId = auth()->id();
        
        try {
            $this->transactionService->approveContract($ownerId, $id);
            return back()->with('success', 'Kontrak berhasil disetujui. Penyewa telah aktif.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}

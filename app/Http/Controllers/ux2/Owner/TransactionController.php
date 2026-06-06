<?php

namespace App\Http\Controllers\ux2\Owner;

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
        $data = $this->transactionService->getTransactionData(auth()->id(), $request);

        return view('ux2.owner.transactions.index', $data);
    }

    public function approve(string $id)
    {
        try {
            $this->transactionService->approveContract(auth()->id(), $id);

            return back()->with('success', 'Kontrak berhasil disetujui. Penyewa telah aktif.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}

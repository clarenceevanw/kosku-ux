<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\MonthlyPayment;
use App\Services\Owner\FinanceService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FinanceController extends Controller
{
    public function __construct(
        protected FinanceService $financeService
    ) {}

    public function index(Request $request): View
    {
        $ownerId = auth()->id();

        $data = $this->financeService->getFinanceData($ownerId, $request);

        return view('owner.keuangan.index', $data);
    }

    public function remind(string $id)
    {
        $ownerId = auth()->id();
        
        $payment = MonthlyPayment::whereHas('contract.transaction.room.boardingHouse', function($q) use ($ownerId) {
            $q->where('owner_id', $ownerId);
        })->findOrFail($id);

        // Simulasi pengiriman notifikasi pengingat ke tenant (misal via email/WA/in-app notif)
        // \Illuminate\Support\Facades\Log::info("Pengingat tagihan #{$payment->id} dikirim ke tenant.");

        return back()->with('success', 'Pengingat berhasil dikirim ke tenant.');
    }
}

<?php

namespace App\Http\Controllers\ux2\Owner;

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

        return view('ux2.owner.keuangan.index', $data);
    }

    public function laporan(Request $request): View
    {
        $ownerId = auth()->id();

        $data = $this->financeService->getReportData($ownerId, $request);

        return view('ux2.owner.keuangan.laporan', $data);
    }

    public function remind(string $id)
    {
        $ownerId = auth()->id();
        
        $payment = MonthlyPayment::whereHas('contract.transaction.room.boardingHouse', function($q) use ($ownerId) {
            $q->where('owner_id', $ownerId);
        })->findOrFail($id);

        return back()->with('success', 'Pengingat berhasil dikirim ke tenant.');
    }
}

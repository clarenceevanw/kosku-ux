<?php

namespace App\Services\Owner;

use App\Models\Contract;
use App\Models\MonthlyPayment;
use App\Models\Room;
use Illuminate\Support\Carbon;

class FinanceService
{
    public function getFinanceData(string $ownerId, \Illuminate\Http\Request $request): array
    {
        // Fetch all boarding houses owned by this user
        $boardingHouses = \App\Models\BoardingHouse::byOwner($ownerId)->get();
        
        if ($boardingHouses->isEmpty()) {
            return [
                'boardingHouses' => collect(),
                'selectedKos' => null,
                'metrics' => [
                    'totalIncome' => 0,
                    'pendingBillsAmount' => 0,
                    'pendingBillsCount' => 0,
                    'activeTenantsCount' => 0,
                    'totalRoomCapacity' => 0,
                ],
                'chartData' => [
                    'labels' => [],
                    'heights' => [],
                    'rawValues' => []
                ],
                'pendingBills' => collect(),
            ];
        }

        $kosId = $request->input('kos_id');
        $selectedKos = $kosId 
            ? $boardingHouses->firstWhere('id', $kosId) 
            : $boardingHouses->first();

        if (!$selectedKos) {
            $selectedKos = $boardingHouses->first();
        }

        $metrics = $this->getMetrics($ownerId, $selectedKos->id);
        $chartData = $this->getIncomeChartData($ownerId, $selectedKos->id);
        $pendingBills = $this->getPendingBills($ownerId, $selectedKos->id);

        return [
            'boardingHouses' => $boardingHouses,
            'selectedKos' => $selectedKos,
            'metrics' => $metrics,
            'chartData' => $chartData,
            'pendingBills' => $pendingBills,
        ];
    }

    public function getReportData(string $ownerId, \Illuminate\Http\Request $request): array
    {
        $boardingHouses = \App\Models\BoardingHouse::byOwner($ownerId)->get();
        
        if ($boardingHouses->isEmpty()) {
            return [
                'boardingHouses' => collect(),
                'summary' => [
                    'totalIncome' => 0,
                    'totalExpense' => 0,
                    'netProfit' => 0,
                    'profitMargin' => 0,
                ],
                'chartData' => [
                    'labels' => [],
                    'income' => [],
                    'expense' => []
                ],
                'incomeTransactions' => collect(),
                'expenseTransactions' => collect(),
            ];
        }

        $kosId = $request->input('kos_id');
        $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

        $summary = $this->getReportSummary($ownerId, $kosId, $startDate, $endDate);
        $chartData = $this->getReportChartData($ownerId, $kosId, $startDate, $endDate);
        $incomeTransactions = $this->getIncomeTransactions($ownerId, $kosId, $startDate, $endDate);
        $expenseTransactions = $this->getExpenseTransactions($ownerId, $kosId, $startDate, $endDate);

        return [
            'boardingHouses' => $boardingHouses,
            'summary' => $summary,
            'chartData' => $chartData,
            'incomeTransactions' => $incomeTransactions,
            'expenseTransactions' => $expenseTransactions,
        ];
    }

    private function getMetrics(string $ownerId, string $kosId): array
    {
        // 1. Total Pendapatan Bulan Ini (Paid in current month)
        $currentMonth = Carbon::now()->startOfMonth();
        
        $totalIncome = MonthlyPayment::whereHas('contract.transaction.room.boardingHouse', function($q) use ($ownerId, $kosId) {
            $q->where('owner_id', $ownerId)->where('id', $kosId);
        })
        ->whereIn('payment_status', [\App\Enum\PaymentStatus::PAID_TO_ESCROW->value, \App\Enum\PaymentStatus::RELEASED_TO_OWNER->value])
        ->where('paid_at', '>=', $currentMonth)
        ->sum('amount');

        // 2. Tagihan Pending
        $pendingBillsQuery = MonthlyPayment::whereHas('contract.transaction.room.boardingHouse', function($q) use ($ownerId, $kosId) {
            $q->where('owner_id', $ownerId)->where('id', $kosId);
        })
        ->where('payment_status', \App\Enum\PaymentStatus::PENDING->value);

        $pendingBillsAmount = $pendingBillsQuery->sum('amount');
        $pendingBillsCount = $pendingBillsQuery->count();

        // 3. Total Penyewa Aktif
        $activeTenantsCount = Contract::whereHas('transaction.room.boardingHouse', function($q) use ($ownerId, $kosId) {
            $q->where('owner_id', $ownerId)->where('id', $kosId);
        })
        ->where('status', \App\Enum\ContractStatus::ACTIVE->value)
        ->count();

        // Total Kapasitas Kamar
        $totalRoomCapacity = Room::whereHas('boardingHouse', function($q) use ($ownerId, $kosId) {
            $q->where('owner_id', $ownerId)->where('id', $kosId);
        })->sum('stock');

        return [
            'totalIncome' => $totalIncome,
            'pendingBillsAmount' => $pendingBillsAmount,
            'pendingBillsCount' => $pendingBillsCount,
            'activeTenantsCount' => $activeTenantsCount,
            'totalRoomCapacity' => $totalRoomCapacity,
        ];
    }

    private function getIncomeChartData(string $ownerId, string $kosId): array
    {
        $months = [];
        $data = [];
        
        // Loop backwards for 6 months
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->translatedFormat('M'); // e.g. "Mei"

            $start = $date->copy()->startOfMonth();
            $end = $date->copy()->endOfMonth();

            $income = MonthlyPayment::whereHas('contract.transaction.room.boardingHouse', function($q) use ($ownerId, $kosId) {
                $q->where('owner_id', $ownerId)->where('id', $kosId);
            })
            ->whereIn('payment_status', [\App\Enum\PaymentStatus::PAID_TO_ESCROW->value, \App\Enum\PaymentStatus::RELEASED_TO_OWNER->value])
            ->whereBetween('paid_at', [$start, $end])
            ->sum('amount');

            // Find the highest income to calculate SVG height correctly (max 160px based on reference)
            $data[] = $income;
        }

        $maxIncome = max($data) > 0 ? max($data) : 1; // Prevent division by zero
        $chartHeights = array_map(function($val) use ($maxIncome) {
            // max height in SVG is around 160
            return max(10, ($val / $maxIncome) * 160); 
        }, $data);

        return [
            'labels' => $months,
            'heights' => $chartHeights,
            'rawValues' => $data
        ];
    }

    private function getPendingBills(string $ownerId, string $kosId)
    {
        return MonthlyPayment::whereHas('contract.transaction.room.boardingHouse', function($q) use ($ownerId, $kosId) {
            $q->where('owner_id', $ownerId)->where('id', $kosId);
        })
        ->where('payment_status', \App\Enum\PaymentStatus::PENDING->value)
        ->with(['contract.transaction.room', 'contract.transaction.tenant'])
        ->orderBy('due_date', 'asc')
        ->paginate(10);
    }

    private function getReportSummary(string $ownerId, ?string $kosId, string $startDate, string $endDate): array
    {
        $query = MonthlyPayment::whereHas('contract.transaction.room.boardingHouse', function($q) use ($ownerId, $kosId) {
            $q->where('owner_id', $ownerId);
            if ($kosId) {
                $q->where('id', $kosId);
            }
        })
        ->whereIn('payment_status', [\App\Enum\PaymentStatus::PAID_TO_ESCROW->value, \App\Enum\PaymentStatus::RELEASED_TO_OWNER->value])
        ->whereBetween('paid_at', [Carbon::parse($startDate), Carbon::parse($endDate)]);

        $totalIncome = $query->sum('amount');
        $totalExpense = 0; // Placeholder - implement expense tracking if needed
        $netProfit = $totalIncome - $totalExpense;
        $profitMargin = $totalIncome > 0 ? ($netProfit / $totalIncome) * 100 : 0;

        return [
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'netProfit' => $netProfit,
            'profitMargin' => $profitMargin,
        ];
    }

    private function getReportChartData(string $ownerId, ?string $kosId, string $startDate, string $endDate): array
    {
        $start = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        $months = [];
        $incomeData = [];
        $expenseData = [];

        $current = $start->copy()->startOfMonth();
        while ($current->lte($end)) {
            $months[] = $current->translatedFormat('M Y');
            
            $monthStart = $current->copy()->startOfMonth();
            $monthEnd = $current->copy()->endOfMonth();

            $income = MonthlyPayment::whereHas('contract.transaction.room.boardingHouse', function($q) use ($ownerId, $kosId) {
                $q->where('owner_id', $ownerId);
                if ($kosId) {
                    $q->where('id', $kosId);
                }
            })
            ->whereIn('payment_status', [\App\Enum\PaymentStatus::PAID_TO_ESCROW->value, \App\Enum\PaymentStatus::RELEASED_TO_OWNER->value])
            ->whereBetween('paid_at', [$monthStart, $monthEnd])
            ->sum('amount');

            $incomeData[] = $income;
            $expenseData[] = 0; // Placeholder

            $current->addMonth();
        }

        return [
            'labels' => $months,
            'income' => $incomeData,
            'expense' => $expenseData,
        ];
    }

    private function getIncomeTransactions(string $ownerId, ?string $kosId, string $startDate, string $endDate)
    {
        return MonthlyPayment::whereHas('contract.transaction.room.boardingHouse', function($q) use ($ownerId, $kosId) {
            $q->where('owner_id', $ownerId);
            if ($kosId) {
                $q->where('id', $kosId);
            }
        })
        ->whereIn('payment_status', [\App\Enum\PaymentStatus::PAID_TO_ESCROW->value, \App\Enum\PaymentStatus::RELEASED_TO_OWNER->value])
        ->whereBetween('paid_at', [Carbon::parse($startDate), Carbon::parse($endDate)])
        ->with(['contract.transaction.room', 'contract.transaction.tenant'])
        ->orderBy('paid_at', 'desc')
        ->limit(10)
        ->get()
        ->map(function($payment) {
            return (object)[
                'description' => 'Pembayaran dari ' . ($payment->contract->transaction->tenant->name ?? 'Unknown'),
                'amount' => $payment->amount,
                'date' => $payment->paid_at,
            ];
        });
    }

    private function getExpenseTransactions(string $ownerId, ?string $kosId, string $startDate, string $endDate)
    {
        // Placeholder - implement expense tracking if needed
        return collect();
    }
}

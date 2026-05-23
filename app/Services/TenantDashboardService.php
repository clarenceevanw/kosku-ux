<?php

namespace App\Services;

use App\Enum\ContractStatus;
use App\Enum\PaymentStatus;
use App\Enum\TicketStatus;
use App\Models\MaintenanceTicket;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Carbon;

/**
 * TenantDashboardService — the ONLY layer allowed to touch Eloquent for the tenant dashboard.
 *
 * All database queries are encapsulated here following the
 * "Fat Service, Skinny Controller" clean architecture pattern.
 */
class TenantDashboardService
{
    /**
     * Retrieve the full dashboard summary for an authenticated tenant.
     *
     * Returns a structured array containing:
     *   - upcoming_payment: The most recent pending transaction for selected contract.
     *   - recent_tickets:   The 3 most recently created maintenance tickets for selected contract.
     *   - ticket_stats:     Counts of active and resolved tickets for selected contract.
     *
     * @param  \App\Models\User  $tenant  The authenticated tenant user.
     * @param  \App\Models\Transaction|null  $selectedContract  The selected active contract.
     */
    public function getDashboardData(User $tenant, ?Transaction $selectedContract = null): array
    {
        $upcomingPayment = $selectedContract ? $this->getUpcomingPaymentForContract($selectedContract) : null;
        $recentTickets   = $selectedContract ? $this->getRecentTicketsForContract($tenant, $selectedContract) : collect();
        $ticketStats     = $selectedContract ? $this->getTicketStatsForContract($tenant, $selectedContract) : ['total' => 0, 'active' => 0, 'resolved' => 0];

        return compact('upcomingPayment', 'recentTickets', 'ticketStats');
    }

    /**
     * Get the tenant's active contract with all eager-loaded relationships needed
     * for the dashboard overview card (room, boarding house, owner).
     */
    public function getActiveContract(User $tenant): ?Transaction
    {
        return $tenant->transactions()
            ->with([
                'room:id,boarding_house_id,type_name,price_per_month,size,image_url',
                'room.boardingHouse:id,owner_id,name,address,city,province',
                'room.boardingHouse.owner:id,name,phone_number',
                'room.boardingHouse.rules:id,boarding_house_id,category,rule_text',
                'contract',
            ])
            ->whereHas('contract', function ($query) {
                $query->where('status', ContractStatus::ACTIVE->value);
            })
            ->latest()
            ->first();
    }

    /**
     * Get all active contracts for the tenant.
     */
    public function getAllActiveContracts(User $tenant): \Illuminate\Database\Eloquent\Collection
    {
        return $tenant->transactions()
            ->with([
                'room:id,boarding_house_id,type_name,price_per_month,size,image_url',
                'room.boardingHouse:id,owner_id,name,address,city,province',
                'contract',
            ])
            ->whereHas('contract', function ($query) {
                $query->where('status', ContractStatus::ACTIVE->value);
            })
            ->latest()
            ->get();
    }

    /**
     * Get all transactions (payment history) for the tenant, ordered by latest.
     * Only returns monthly bills (transactions with billing_month set).
     */
    public function getPaymentHistory(User $tenant): \Illuminate\Database\Eloquent\Collection
    {
        return $tenant->transactions()
            ->whereNotNull('billing_month')
            ->with([
                'room:id,boarding_house_id,type_name,price_per_month',
                'room.boardingHouse:id,name,city',
                'contract:id,transaction_id,contract_number,start_date,end_date,status',
            ])
            ->orderBy('due_date', 'desc')
            ->get();
    }

    /**
     * Get payment history for a specific contract.
     * Only returns monthly bills for the selected contract.
     */
    public function getPaymentHistoryForContract(Transaction $contract): \Illuminate\Database\Eloquent\Collection
    {
        // Get the actual contract from the transaction
        $actualContract = $contract->contract;
        
        if (!$actualContract) {
            return collect();
        }
        
        return Transaction::where('contract_id', $actualContract->id)
            ->whereNotNull('billing_month')
            ->with([
                'room:id,boarding_house_id,type_name,price_per_month',
                'room.boardingHouse:id,name,city',
                'contract:id,transaction_id,contract_number,start_date,end_date,status',
            ])
            ->orderBy('due_date', 'desc')
            ->get();
    }

    /**
     * Get the upcoming/most recent pending payment for the tenant.
     * Returns the next due monthly bill.
     * Only returns bills due within 31 days.
     */
    public function getUpcomingPayment(User $tenant): ?Transaction
    {
        $thirtyOneDaysFromNow = Carbon::now()->addDays(31);
        
        return $tenant->transactions()
            ->whereNotNull('billing_month')
            ->with([
                'room:id,boarding_house_id,type_name,price_per_month',
                'room.boardingHouse:id,name',
                'contract:id,transaction_id,contract_number,monthly_fee,start_date,end_date',
            ])
            ->where('payment_status', PaymentStatus::PENDING->value)
            ->where('due_date', '<=', $thirtyOneDaysFromNow)
            ->orderBy('due_date', 'asc')
            ->first();
    }

    /**
     * Get the upcoming payment for a specific contract.
     * Returns the next due monthly bill for this contract.
     * Only returns bills due within 31 days.
     */
    public function getUpcomingPaymentForContract(Transaction $contract): ?Transaction
    {
        // Get the actual contract from the transaction
        $actualContract = $contract->contract;
        
        if (!$actualContract) {
            return null;
        }
        
        $thirtyOneDaysFromNow = Carbon::now()->addDays(31);
        
        return Transaction::where('contract_id', $actualContract->id)
            ->whereNotNull('billing_month')
            ->with([
                'room:id,boarding_house_id,type_name,price_per_month',
                'room.boardingHouse:id,name',
                'contract:id,transaction_id,contract_number,monthly_fee,start_date,end_date',
            ])
            ->where('payment_status', PaymentStatus::PENDING->value)
            ->where('due_date', '<=', $thirtyOneDaysFromNow)
            ->orderBy('due_date', 'asc')
            ->first();
    }

    /**
     * Get all maintenance tickets for the tenant, ordered by most recent.
     */
    public function getAllTickets(User $tenant, ?string $status = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = $tenant->maintenanceTickets()
            ->with(['room:id,boarding_house_id,type_name', 'room.boardingHouse:id,name']);
        
        if ($status === 'process') {
            $query->whereIn('status', [
                TicketStatus::REPORTED->value,
                TicketStatus::IN_PROGRESS->value,
            ]);
        } elseif ($status === 'resolved') {
            $query->where('status', TicketStatus::RESOLVED->value);
        }
        
        return $query->latest()->get();
    }

    /**
     * Get maintenance tickets for a specific contract.
     */
    public function getTicketsForContract(User $tenant, Transaction $contract, ?string $status = null): \Illuminate\Database\Eloquent\Collection
    {
        $query = $tenant->maintenanceTickets()
            ->where('room_id', $contract->room_id)
            ->with(['room:id,boarding_house_id,type_name', 'room.boardingHouse:id,name']);
        
        if ($status === 'process') {
            $query->whereIn('status', [
                TicketStatus::REPORTED->value,
                TicketStatus::IN_PROGRESS->value,
            ]);
        } elseif ($status === 'resolved') {
            $query->where('status', TicketStatus::RESOLVED->value);
        }
        
        return $query->latest()->get();
    }

    /**
     * Get the 3 most recent maintenance tickets for the dashboard overview.
     */
    public function getRecentTickets(User $tenant): \Illuminate\Database\Eloquent\Collection
    {
        return $tenant->maintenanceTickets()
            ->with(['room:id,type_name'])
            ->latest()
            ->limit(3)
            ->get();
    }

    /**
     * Get the 3 most recent maintenance tickets for a specific contract.
     */
    public function getRecentTicketsForContract(User $tenant, Transaction $contract): \Illuminate\Database\Eloquent\Collection
    {
        return $tenant->maintenanceTickets()
            ->where('room_id', $contract->room_id)
            ->with(['room:id,type_name'])
            ->latest()
            ->limit(3)
            ->get();
    }

    /**
     * Get aggregated ticket statistics for the dashboard quick-stats row.
     */
    public function getTicketStats(User $tenant): array
    {
        $base = $tenant->maintenanceTickets();

        return [
            'total'    => (clone $base)->count(),
            'active'   => (clone $base)->whereIn('status', [
                TicketStatus::REPORTED->value,
                TicketStatus::IN_PROGRESS->value,
            ])->count(),
            'resolved' => (clone $base)->where('status', TicketStatus::RESOLVED->value)->count(),
        ];
    }

    /**
     * Get aggregated ticket statistics for a specific contract.
     */
    public function getTicketStatsForContract(User $tenant, Transaction $contract): array
    {
        $base = $tenant->maintenanceTickets()->where('room_id', $contract->room_id);

        return [
            'total'    => (clone $base)->count(),
            'active'   => (clone $base)->whereIn('status', [
                TicketStatus::REPORTED->value,
                TicketStatus::IN_PROGRESS->value,
            ])->count(),
            'resolved' => (clone $base)->where('status', TicketStatus::RESOLVED->value)->count(),
        ];
    }

    /**
     * Create a new maintenance ticket for the given tenant.
     *
     * @param  \App\Models\User  $tenant
     * @param  array{title: string, description: string, priority: string, photo_url: string|null, room_id: string}  $data
     */
    public function createTicket(User $tenant, array $data): MaintenanceTicket
    {
        return $tenant->maintenanceTickets()->create($data);
    }

    /**
     * Calculate the remaining time on a contract in a human-readable format.
     * Returns null if no active contract exists.
     */
    public function getRemainingTime(?Transaction $transaction): ?string
    {
        if (! $transaction?->contract) {
            return null;
        }

        $endDate = Carbon::parse($transaction->contract->end_date);
        $now     = Carbon::now();

        if ($endDate->isPast()) {
            return 'Kontrak Berakhir';
        }

        $months = (int) $now->diffInMonths($endDate);
        $days   = (int) $now->copy()->addMonths($months)->diffInDays($endDate);

        if ($months > 0) {
            return "{$months} Bulan {$days} Hari";
        }

        return "{$days} Hari";
    }

    /**
     * Calculate the total duration of a contract in months.
     * Uses ceiling to count partial months as full months.
     */
    public function getContractDurationMonths(?Transaction $transaction): ?int
    {
        if (! $transaction?->contract) {
            return null;
        }

        $start = Carbon::parse($transaction->contract->start_date);
        $end   = Carbon::parse($transaction->contract->end_date);

        // Add 1 day to end date to include the last day in calculation
        $end = $end->copy()->addDay();
        
        // Calculate months and round up to include partial months
        $months = $start->diffInMonths($end);
        
        // If there are remaining days after full months, count as additional month
        $remainingDays = $start->copy()->addMonths($months)->diffInDays($end);
        if ($remainingDays > 0) {
            $months++;
        }

        return (int) $months;
    }

    /**
     * Calculate days until a payment is due.
     * Returns null if no upcoming payment exists.
     */
    public function getDaysUntilDue(?Transaction $transaction): ?int
    {
        if (! $transaction || ! $transaction->due_date) {
            return null;
        }

        $dueDate = Carbon::parse($transaction->due_date);
        $now     = Carbon::now();

        if ($dueDate->isPast()) {
            return 0;
        }

        return (int) $now->diffInDays($dueDate);
    }
}

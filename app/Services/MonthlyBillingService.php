<?php

namespace App\Services;

use App\Enum\PaymentStatus;
use App\Models\Contract;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * MonthlyBillingService
 * 
 * Handles automatic generation of monthly billing transactions
 * for rental contracts.
 */
class MonthlyBillingService
{
    /**
     * Generate monthly billing transactions for a contract.
     * 
     * Creates separate transaction records for each month of the rental period.
     * First month is due immediately, subsequent months are due on the start date
     * of each billing period.
     *
     * @param Contract $contract The rental contract
     * @return array Array of created transactions
     */
    public function generateMonthlyBills(Contract $contract): array
    {
        $startDate = Carbon::parse($contract->start_date);
        $endDate = Carbon::parse($contract->end_date);
        $monthlyFee = $contract->monthly_fee;
        
        // Calculate total months - add 1 day to end date to include the last day
        $endDatePlusOne = $endDate->copy()->addDay();
        $totalMonths = $startDate->diffInMonths($endDatePlusOne);
        
        // If there are remaining days after full months, count as additional month
        $remainingDays = $startDate->copy()->addMonths($totalMonths)->diffInDays($endDatePlusOne);
        if ($remainingDays > 0) {
            $totalMonths++;
        }
        
        // Minimum 1 month
        if ($totalMonths === 0) {
            $totalMonths = 1;
        }
        
        $transactions = [];
        
        DB::beginTransaction();
        try {
            for ($month = 1; $month <= $totalMonths; $month++) {
                // Calculate billing period for this month
                $billingStart = $startDate->copy()->addMonths($month - 1);
                $billingEnd = $startDate->copy()->addMonths($month)->subDay();
                
                // Ensure billing end doesn't exceed contract end
                if ($billingEnd->greaterThan($endDate)) {
                    $billingEnd = $endDate->copy();
                }
                
                // Due date is the start of the billing period
                // First month is due immediately (start date)
                // Subsequent months are due on their respective start dates
                $dueDate = $billingStart->copy();
                
                // Get the initial transaction to link tenant and room
                $initialTransaction = $contract->transaction;
                
                $transaction = Transaction::create([
                    'tenant_id' => $initialTransaction->tenant_id,
                    'room_id' => $initialTransaction->room_id,
                    'contract_id' => $contract->id,
                    'start_date' => $billingStart,
                    'end_date' => $billingEnd,
                    'billing_month' => $month,
                    'due_date' => $dueDate,
                    'total_amount' => $monthlyFee,
                    'payment_status' => PaymentStatus::PENDING->value,
                    'payment_method' => null,
                    'paid_at' => null,
                ]);
                
                $transactions[] = $transaction;
            }
            
            DB::commit();
            return $transactions;
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    /**
     * Get upcoming bills for a contract.
     * Returns unpaid bills ordered by due date.
     *
     * @param Contract $contract
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUpcomingBills(Contract $contract)
    {
        return $contract->monthlyBills()
            ->whereIn('payment_status', [PaymentStatus::PENDING->value])
            ->orderBy('due_date', 'asc')
            ->get();
    }
    
    /**
     * Get the next due bill for a contract.
     *
     * @param Contract $contract
     * @return Transaction|null
     */
    public function getNextDueBill(Contract $contract): ?Transaction
    {
        return $contract->monthlyBills()
            ->where('payment_status', PaymentStatus::PENDING->value)
            ->orderBy('due_date', 'asc')
            ->first();
    }
    
    /**
     * Mark a bill as paid.
     *
     * @param Transaction $transaction
     * @param string $paymentMethod
     * @return Transaction
     */
    public function markAsPaid(Transaction $transaction, string $paymentMethod): Transaction
    {
        $transaction->update([
            'payment_status' => PaymentStatus::PAID_TO_ESCROW->value,
            'payment_method' => $paymentMethod,
            'paid_at' => now(),
        ]);
        
        return $transaction->fresh();
    }
    
    /**
     * Check if contract has overdue bills.
     *
     * @param Contract $contract
     * @return bool
     */
    public function hasOverdueBills(Contract $contract): bool
    {
        return $contract->monthlyBills()
            ->where('payment_status', PaymentStatus::PENDING->value)
            ->where('due_date', '<', now())
            ->exists();
    }
    
    /**
     * Get payment statistics for a contract.
     *
     * @param Contract $contract
     * @return array
     */
    public function getPaymentStats(Contract $contract): array
    {
        $allBills = $contract->monthlyBills;
        
        return [
            'total_bills' => $allBills->count(),
            'paid_bills' => $allBills->whereIn('payment_status', [
                PaymentStatus::PAID_TO_ESCROW->value,
                PaymentStatus::RELEASED_TO_OWNER->value
            ])->count(),
            'pending_bills' => $allBills->where('payment_status', PaymentStatus::PENDING->value)->count(),
            'overdue_bills' => $allBills->where('payment_status', PaymentStatus::PENDING->value)
                ->where('due_date', '<', now())->count(),
            'total_paid_amount' => $allBills->whereIn('payment_status', [
                PaymentStatus::PAID_TO_ESCROW->value,
                PaymentStatus::RELEASED_TO_OWNER->value
            ])->sum('total_amount'),
            'total_pending_amount' => $allBills->where('payment_status', PaymentStatus::PENDING->value)
                ->sum('total_amount'),
        ];
    }
}

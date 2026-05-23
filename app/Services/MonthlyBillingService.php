<?php

namespace App\Services;

use App\Enum\PaymentStatus;
use App\Models\Contract;
use App\Models\MonthlyPayment;
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
     * Generate monthly payment records for a contract.
     * 
     * Creates separate monthly_payment records for each month of the rental period.
     * First month is due immediately, subsequent months are due on the start date
     * of each billing period.
     *
     * @param Contract $contract The rental contract
     * @return array Array of created monthly payments
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
        
        $payments = [];
        
        DB::beginTransaction();
        try {
            for ($month = 1; $month <= $totalMonths; $month++) {
                // Calculate billing period for this month
                $billingStart = $startDate->copy()->addMonths($month - 1);
                
                // Due date is the start of the billing period
                $dueDate = $billingStart->copy();
                
                $payment = MonthlyPayment::create([
                    'contract_id' => $contract->id,
                    'billing_month' => $month,
                    'due_date' => $dueDate,
                    'amount' => $monthlyFee,
                    'payment_status' => PaymentStatus::PENDING->value,
                    'payment_method' => null,
                    'paid_at' => null,
                ]);
                
                $payments[] = $payment;
            }
            
            DB::commit();
            return $payments;
            
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
        return $contract->monthlyPayments()
            ->whereIn('payment_status', [PaymentStatus::PENDING->value])
            ->orderBy('due_date', 'asc')
            ->get();
    }
    
    /**
     * Get the next due bill for a contract.
     *
     * @param Contract $contract
     * @return MonthlyPayment|null
     */
    public function getNextDueBill(Contract $contract): ?MonthlyPayment
    {
        return $contract->monthlyPayments()
            ->where('payment_status', PaymentStatus::PENDING->value)
            ->orderBy('due_date', 'asc')
            ->first();
    }
    
    /**
     * Mark a bill as paid.
     *
     * @param MonthlyPayment $payment
     * @param string $paymentMethod
     * @return MonthlyPayment
     */
    public function markAsPaid(MonthlyPayment $payment, string $paymentMethod): MonthlyPayment
    {
        $payment->update([
            'payment_status' => PaymentStatus::PAID_TO_ESCROW->value,
            'payment_method' => $paymentMethod,
            'paid_at' => now(),
        ]);
        
        return $payment->fresh();
    }
    
    /**
     * Check if contract has overdue bills.
     *
     * @param Contract $contract
     * @return bool
     */
    public function hasOverdueBills(Contract $contract): bool
    {
        return $contract->monthlyPayments()
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
        $allBills = $contract->monthlyPayments;
        
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
            ])->sum('amount'),
            'total_pending_amount' => $allBills->where('payment_status', PaymentStatus::PENDING->value)
                ->sum('amount'),
        ];
    }
}

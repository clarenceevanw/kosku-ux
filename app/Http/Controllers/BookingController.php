<?php

namespace App\Http\Controllers;

use App\Enum\ContractStatus;
use App\Enum\PaymentStatus;
use App\Http\Resources\BoardingHouseResource;
use App\Models\BoardingHouse;
use App\Models\Contract;
use App\Models\Room;
use App\Models\Transaction;
use App\Services\BoardingHouseService;
use App\Services\MonthlyBillingService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        private readonly BoardingHouseService $boardingHouseService,
        private readonly MonthlyBillingService $billingService
    ) {}

    public function show(string $id): View
    {
        $boardingHouse = new BoardingHouseResource(
            $this->boardingHouseService->getBoardingHouseDetails($id)
        );

        return view('booking', [
            'boardingHouse' => $boardingHouse->resolve(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        $validated = $request->validate([
            'boarding_house_id' => 'required|uuid|exists:boarding_houses,id',
            'room_id' => 'required|uuid|exists:rooms,id',
            'duration_months' => 'required|integer|min:1|max:12',
            'start_date' => 'required|date|after:today',
        ]);

        $tenant = Auth::user();
        $room = Room::with('boardingHouse')->findOrFail($validated['room_id']);

        // Check room availability
        if ($room->stock <= 0) {
            return back()->with('error', 'Kamar tidak tersedia.');
        }

        // Calculate dates and amounts
        $startDate = Carbon::parse($validated['start_date']);
        $durationMonths = (int) $validated['duration_months'];
        $endDate = $startDate->copy()->addMonths($durationMonths)->subDay();
        $monthlyFee = $room->price_per_month;
        $depositFee = $monthlyFee; // 1 month deposit

        DB::beginTransaction();
        try {
            // Create initial transaction (for contract reference only, not for payment)
            $initialTransaction = Transaction::create([
                'tenant_id' => $tenant->id,
                'room_id' => $room->id,
                'contract_id' => null, // Will be updated after contract creation
                'start_date' => $startDate,
                'end_date' => $endDate,
                'billing_month' => null,
                'due_date' => null,
                'total_amount' => $monthlyFee * $durationMonths,
                'payment_status' => PaymentStatus::PENDING->value,
                'payment_method' => null,
            ]);

            // Create contract
            $contractNumber = 'KOS-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6));
            
            $contract = Contract::create([
                'transaction_id' => $initialTransaction->id,
                'contract_number' => $contractNumber,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'monthly_fee' => $monthlyFee,
                'deposit_fee' => $depositFee,
                'status' => ContractStatus::ACTIVE->value,
                'tenant_signature_date' => now(),
                'owner_signature_date' => null,
            ]);

            // Generate monthly billing transactions
            $monthlyBills = $this->billingService->generateMonthlyBills($contract);
            
            // Get first month bill for payment
            $firstMonthBill = $monthlyBills[0] ?? null;

            // Decrease room stock
            $room->decrement('stock');

            DB::commit();

            // Redirect to payment checkout for first month
            return redirect()
                ->route('tenant.payment.checkout', $firstMonthBill->id)
                ->with('success', 'Booking berhasil! Silakan bayar tagihan bulan pertama.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat booking. Silakan coba lagi.');
        }
    }
}

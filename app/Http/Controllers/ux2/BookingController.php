<?php

namespace App\Http\Controllers\ux2;

use App\Enum\ContractStatus;
use App\Http\Resources\BoardingHouseResource;
use App\Models\Contract;
use App\Models\Room;
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

        return view('ux2.booking', [
            'boardingHouse' => $boardingHouse->resolve(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if (!Auth::check()) {
            return redirect()->route('ux2.login')->with('error', 'Silakan login terlebih dahulu.');
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
            $contractNumber = 'KOS-' . date('Y') . '-' . strtoupper(substr(uniqid(), -6));
            
            $contract = Contract::create([
                'tenant_id' => $tenant->id,
                'room_id' => $room->id,
                'contract_number' => $contractNumber,
                'monthly_fee' => $monthlyFee,
                'deposit_fee' => $depositFee,
                'status' => ContractStatus::PENDING->value,
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

            // Redirect to UX2 payment checkout
            return redirect()
                ->route('ux2.booking.checkout', ['payment_id' => $firstMonthBill?->id])
                ->with('success', 'Booking berhasil! Silakan selesaikan pembayaran.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan saat booking. Silakan coba lagi.');
        }
    }

    public function checkout(Request $request): View
    {
        $tenant = Auth::user();
        $paymentId = $request->query('payment_id');
        
        $payment = \App\Models\MonthlyPayment::with(['contract.room.boardingHouse'])->findOrFail($paymentId);

        if ($payment->contract->tenant_id !== $tenant->id) {
            abort(403, 'Unauthorized access');
        }

        return view('ux2.payment-checkout', [
            'tenant'  => $tenant,
            'payment' => $payment,
        ]);
    }
}

<?php

namespace App\Http\Controllers\ux2;

use App\Http\Requests\StoreMaintananceTicketRequest;
use App\Models\MaintenanceTicket;
use App\Models\MonthlyPayment;
use App\Services\MonthlyBillingService;
use App\Services\TenantDashboardService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class TenantDashboardController extends Controller
{
    public function __construct(
        private readonly TenantDashboardService $tenantService,
        private readonly MonthlyBillingService $billingService
    ) {}

    public function index(): View
    {
        $tenant = Auth::user();
        $allActiveContracts = $this->tenantService->getAllActiveContracts($tenant);
        
        // Get selected contract from query param or session or use first active
        $selectedKosId = request('kos');
        
        if ($selectedKosId) {
            // Save to session when explicitly selected
            session(['selected_kos_id' => $selectedKosId]);
        } else {
            // Try to get from session
            $selectedKosId = session('selected_kos_id');
        }
        
        $activeContract = $selectedKosId 
            ? $allActiveContracts->firstWhere('id', $selectedKosId)
            : $allActiveContracts->first();
        
        // If contract not found in active contracts, use first and clear session
        if (!$activeContract && $allActiveContracts->isNotEmpty()) {
            $activeContract = $allActiveContracts->first();
            session(['selected_kos_id' => $activeContract->id]);
        }
        
        $data = $this->tenantService->getDashboardData($tenant, $activeContract);

        return view('ux2.tenant.dashboard', [
            'tenant'             => $tenant,
            'allActiveContracts' => $allActiveContracts,
            'activeContract'     => $activeContract,
            'upcomingPayment'    => $data['upcomingPayment'],
            'recentTickets'      => $data['recentTickets'],
            'ticketStats'        => $data['ticketStats'],
            'remainingTime'      => $this->tenantService->getRemainingTime($activeContract),
            'durationMonths'     => $this->tenantService->getContractDurationMonths($activeContract),
            'daysUntilDue'       => $this->tenantService->getDaysUntilDue($data['upcomingPayment']),
        ]);
    }

    public function payments(): View
    {
        $tenant   = Auth::user();
        $allActiveContracts = $this->tenantService->getAllActiveContracts($tenant);
        
        // Get selected contract from query param or session or use first active
        $selectedKosId = request('kos');
        
        if ($selectedKosId) {
            // Save to session when explicitly selected
            session(['selected_kos_id' => $selectedKosId]);
        } else {
            // Try to get from session
            $selectedKosId = session('selected_kos_id');
        }
        
        $activeContract = $selectedKosId 
            ? $allActiveContracts->firstWhere('id', $selectedKosId)
            : $allActiveContracts->first();
        
        // If contract not found in active contracts, use first and clear session
        if (!$activeContract && $allActiveContracts->isNotEmpty()) {
            $activeContract = $allActiveContracts->first();
            session(['selected_kos_id' => $activeContract->id]);
        }
        
        // Get payments for selected contract only
        $payments = $activeContract 
            ? $this->tenantService->getPaymentHistoryForContract($activeContract)
            : collect();

        return view('ux2.tenant.payments', [
            'tenant'   => $tenant,
            'payments' => $payments,
            'activeContract' => $activeContract,
            'allActiveContracts' => $allActiveContracts,
        ]);
    }

    public function paymentCheckout(MonthlyPayment $payment): View
    {
        $tenant = Auth::user();
        
        if ($payment->contract->tenant_id !== $tenant->id) {
            abort(403, 'Unauthorized access');
        }

        return view('ux2.payment-checkout', [
            'tenant'  => $tenant,
            'payment' => $payment->load(['contract.room.boardingHouse']),
        ]);
    }

    public function processPayment(MonthlyPayment $payment): RedirectResponse
    {
        $tenant = Auth::user();
        
        if ($payment->contract->tenant_id !== $tenant->id) {
            abort(403, 'Unauthorized access');
        }

        $paymentMethod = request('payment_method');
        
        // Use billing service to mark as paid
        $this->billingService->markAsPaid($payment, $paymentMethod);

        return redirect()
            ->route('ux2.tenant.payments')
            ->with('success', 'Pembayaran berhasil! Dana Anda telah diamankan dalam escrow.');
    }

    public function tickets(): View
    {
        $tenant  = Auth::user();
        $allActiveContracts = $this->tenantService->getAllActiveContracts($tenant);
        
        $selectedKosId = request('kos');
        
        if ($selectedKosId) {
            session(['selected_kos_id' => $selectedKosId]);
        } else {
            $selectedKosId = session('selected_kos_id');
        }
        
        $activeTransaction = $selectedKosId 
            ? $allActiveContracts->firstWhere('id', $selectedKosId)
            : $allActiveContracts->first();
        
        if (!$activeTransaction && $allActiveContracts->isNotEmpty()) {
            $activeTransaction = $allActiveContracts->first();
            session(['selected_kos_id' => $activeTransaction->id]);
        }
        
        $status  = request('status');
        $tickets = $activeTransaction 
            ? $this->tenantService->getTicketsForContract($tenant, $activeTransaction, $status)
            : collect();

        return view('ux2.tenant.tickets', [
            'tenant'             => $tenant,
            'tickets'            => $tickets,
            'allActiveContracts' => $allActiveContracts,
            'activeTransaction'  => $activeTransaction,
        ]);
    }

    public function showTicket(MaintenanceTicket $ticket): View
    {
        $tenant = Auth::user();
        
        if ($ticket->tenant_id !== $tenant->id) {
            abort(403, 'Unauthorized access');
        }

        $activeTransaction = $this->tenantService->getActiveContract($tenant);

        return view('ux2.tenant.ticket-detail', [
            'tenant'            => $tenant,
            'ticket'            => $ticket->load(['room.boardingHouse']),
            'activeTransaction' => $activeTransaction,
        ]);
    }

    public function createTicket(): View
    {
        $tenant = Auth::user();
        $allActiveContracts = $this->tenantService->getAllActiveContracts($tenant);
        
        $selectedKosId = request('kos');
        
        if ($selectedKosId) {
            session(['selected_kos_id' => $selectedKosId]);
        } else {
            $selectedKosId = session('selected_kos_id');
        }
        
        $activeTransaction = $selectedKosId 
            ? $allActiveContracts->firstWhere('id', $selectedKosId)
            : $allActiveContracts->first();
        
        if (!$activeTransaction && $allActiveContracts->isNotEmpty()) {
            $activeTransaction = $allActiveContracts->first();
            session(['selected_kos_id' => $activeTransaction->id]);
        }

        if (!$activeTransaction) {
            abort(403, 'Anda harus memiliki hunian aktif untuk membuat laporan.');
        }

        return view('ux2.tenant.ticket-create', [
            'tenant'            => $tenant,
            'activeTransaction' => $activeTransaction,
        ]);
    }

    public function storeTicket(StoreMaintananceTicketRequest $request): RedirectResponse
    {
        $tenant = Auth::user();
        
        $data = $request->validated();
        
        // Handle file upload
        if ($request->hasFile('photo')) {
            $path = $request->file('photo')->store('tickets', 'public');
            $data['photo_url'] = $path;
        }

        $this->tenantService->createTicket($tenant, $data);

        return redirect()
            ->route('ux2.tenant.tickets')
            ->with('success', 'Laporan kerusakan berhasil dikirim!');
    }

    public function contract(): View
    {
        $tenant          = Auth::user();
        $allActiveContracts = $this->tenantService->getAllActiveContracts($tenant);
        
        $selectedKosId = request('kos');
        
        if ($selectedKosId) {
            session(['selected_kos_id' => $selectedKosId]);
        } else {
            $selectedKosId = session('selected_kos_id');
        }
        
        $activeContract = $selectedKosId 
            ? $allActiveContracts->firstWhere('id', $selectedKosId)
            : $allActiveContracts->first();
        
        if (!$activeContract && $allActiveContracts->isNotEmpty()) {
            $activeContract = $allActiveContracts->first();
            session(['selected_kos_id' => $activeContract->id]);
        }
        
        $allPayments     = $this->tenantService->getPaymentHistory($tenant);

        return view('ux2.tenant.contract', [
            'tenant'             => $tenant,
            'allActiveContracts' => $allActiveContracts,
            'activeContract'     => $activeContract,
            'allPayments'        => $allPayments,
            'remainingTime'      => $this->tenantService->getRemainingTime($activeContract),
            'durationMonths'     => $this->tenantService->getContractDurationMonths($activeContract),
        ]);
    }

    public function rules(): View
    {
        $tenant         = Auth::user();
        $allActiveContracts = $this->tenantService->getAllActiveContracts($tenant);
        
        $selectedKosId = request('kos');
        
        if ($selectedKosId) {
            session(['selected_kos_id' => $selectedKosId]);
        } else {
            $selectedKosId = session('selected_kos_id');
        }
        
        $activeContract = $selectedKosId 
            ? $allActiveContracts->firstWhere('id', $selectedKosId)
            : $allActiveContracts->first();
        
        if (!$activeContract && $allActiveContracts->isNotEmpty()) {
            $activeContract = $allActiveContracts->first();
            session(['selected_kos_id' => $activeContract->id]);
        }

        return view('ux2.tenant.rules', [
            'tenant'             => $tenant,
            'allActiveContracts' => $allActiveContracts,
            'activeContract'     => $activeContract,
        ]);
    }

    public function settings(): View
    {
        $tenant = Auth::user();
        $activeContract = $this->tenantService->getActiveContract($tenant);

        return view('ux2.tenant.settings', [
            'tenant'         => $tenant,
            'activeContract' => $activeContract,
        ]);
    }
}

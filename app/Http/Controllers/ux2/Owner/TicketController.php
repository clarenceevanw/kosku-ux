<?php

namespace App\Http\Controllers\ux2\Owner;

use App\Http\Controllers\Controller;
use App\Services\Owner\TicketService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TicketController extends Controller
{
    public function __construct(
        protected TicketService $ticketService
    ) {}

    public function index(Request $request): View
    {
        $data = $this->ticketService->getTicketsData(auth()->id(), $request);

        return view('ux2.owner.tickets.index', $data);
    }

    public function show(string $id): View
    {
        $ticket = $this->ticketService->getTicketDetails(auth()->id(), $id);

        return view('ux2.owner.tickets.show', compact('ticket'));
    }

    public function updateStatus(Request $request, string $id)
    {
        $request->validate([
            'status' => ['required', new \Illuminate\Validation\Rules\Enum(\App\Enum\TicketStatus::class)]
        ]);

        $this->ticketService->updateTicketStatus(auth()->id(), $id, $request->status);

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }
}

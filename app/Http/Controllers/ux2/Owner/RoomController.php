<?php

namespace App\Http\Controllers\ux2\Owner;

use App\Http\Controllers\Controller;
use App\Services\Owner\RoomService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RoomController extends Controller
{
    public function __construct(
        protected RoomService $roomService
    ) {}

    public function index(Request $request): View
    {
        $data = $this->roomService->getRoomsData(auth()->id(), $request);

        return view('ux2.owner.rooms.index', $data);
    }

    public function store(Request $request): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'boarding_house_id' => 'required|exists:boarding_houses,id',
            'type_name'         => 'required|string|max:255',
            'price_per_month'   => 'required|numeric|min:0',
            'stock'             => 'required|integer|min:1',
            'size'              => 'nullable|string|max:255',
            'facilities'        => 'nullable|array',
            'facilities.*'      => 'exists:facilities,id',
        ]);

        $this->roomService->createRoom(auth()->id(), $validated, $request->input('facilities'));

        return back()->with('success', 'Kamar berhasil ditambahkan.');
    }

    public function show(string $id): View
    {
        $room = $this->roomService->getRoomDetails(auth()->id(), $id);

        return view('ux2.owner.rooms.show', compact('room'));
    }

    public function update(Request $request, string $id): \Illuminate\Http\RedirectResponse
    {
        $validated = $request->validate([
            'type_name'         => 'required|string|max:255',
            'price_per_month'   => 'required|numeric|min:0',
            'stock'             => 'required|integer|min:1',
            'size'              => 'nullable|string|max:255',
            'facilities'        => 'nullable|array',
            'facilities.*'      => 'exists:facilities,id',
        ]);

        $this->roomService->updateRoom(auth()->id(), $id, $validated, $request->input('facilities'));

        return back()->with('success', 'Informasi kamar berhasil diperbarui.');
    }

    public function destroy(string $id): \Illuminate\Http\RedirectResponse
    {
        try {
            $this->roomService->deleteRoom(auth()->id(), $id);
            return back()->with('success', 'Kamar berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}

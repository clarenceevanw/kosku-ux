<?php

namespace App\Http\Controllers\ux2\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Owner\StoreKosRequest;
use App\Http\Requests\Owner\UpdateKosRequest;
use App\Services\Owner\KosService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class KosController extends Controller
{
    public function __construct(private KosService $kosService)
    {
    }

    public function index(Request $request): View
    {
        $ownerId = auth()->id();
        $filters = $request->only(['search', 'type']);
        
        $kosList = $this->kosService->getAllKos($ownerId, $filters);

        $masterFacilities = \App\Models\Facility::where('type', 'bersama')->get();
        $masterRules = \App\Models\Rule::all()->groupBy('category');

        return view('ux2.owner.kos.index', compact('kosList', 'masterFacilities', 'masterRules'));
    }

    public function store(StoreKosRequest $request): RedirectResponse
    {
        $ownerId = auth()->id();
        $this->kosService->createKos($ownerId, $request->validated());

        return redirect()->route('ux2.owner.kos.index')->with('success', 'Kos berhasil ditambahkan.');
    }

    public function update(UpdateKosRequest $request, string $id): RedirectResponse
    {
        $ownerId = auth()->id();
        $this->kosService->updateKos($id, $ownerId, $request->validated());

        return redirect()->route('ux2.owner.kos.index')->with('success', 'Kos berhasil diperbarui.');
    }

    public function destroy(string $id): RedirectResponse
    {
        $ownerId = auth()->id();
        $this->kosService->deleteKos($id, $ownerId);

        return redirect()->route('ux2.owner.kos.index')->with('success', 'Kos berhasil dihapus.');
    }
}

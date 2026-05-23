@props(['activeContracts', 'selectedContract'])

@if($activeContracts->count() > 1)
<div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-6 shadow-sm mb-8">
    <div class="flex items-center gap-4 mb-4">
        <span class="material-symbols-outlined text-primary">home_work</span>
        <h3 class="font-headline text-lg font-semibold text-on-surface">Pilih Kos yang Ingin Dilihat</h3>
    </div>
    <p class="font-body text-sm text-on-surface-variant mb-6">Anda memiliki {{ $activeContracts->count() }} kos aktif. Pilih salah satu untuk melihat detailnya.</p>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($activeContracts as $contract)
        <a href="?kos={{ $contract->id }}" 
           class="group relative flex items-center gap-4 p-4 border-2 {{ $selectedContract?->id === $contract->id ? 'border-primary bg-primary/5' : 'border-outline-variant hover:border-outline' }} rounded-xl transition-all cursor-pointer">
            <div class="w-16 h-16 rounded-lg overflow-hidden bg-surface-container flex-shrink-0">
                @if($contract->room?->image_url)
                <img src="{{ $contract->room->image_url }}" alt="{{ $contract->room->boardingHouse->name }}" class="w-full h-full object-cover">
                @else
                <div class="w-full h-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-on-surface-variant">bed</span>
                </div>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="font-headline text-base font-semibold text-on-surface truncate">{{ $contract->room->boardingHouse->name }}</h4>
                <p class="font-body text-sm text-on-surface-variant">{{ $contract->room->type_name }}</p>
                <p class="font-label text-xs text-on-surface-variant mt-1">{{ $contract->room->boardingHouse->city }}</p>
            </div>
            @if($selectedContract?->id === $contract->id)
            <div class="w-6 h-6 rounded-full bg-primary flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-on-primary text-[16px]" style="font-variation-settings: 'FILL' 1;">check</span>
            </div>
            @endif
        </a>
        @endforeach
    </div>
</div>
@endif

@extends('layouts.tenant', ['activeContract' => $activeTransaction ?? null])

@section('title', 'Buat Laporan Perbaikan')

@section('content')

<div class="max-w-3xl md:mx-0">
    {{-- Header Section --}}
    <div class="mb-12">
        <a href="{{ route('tenant.tickets') }}" class="inline-flex items-center gap-2 text-on-surface-variant hover:text-primary mb-6 transition-colors">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            <span class="font-label text-sm font-semibold tracking-wide">Kembali ke Laporan</span>
        </a>
        <h1 class="font-headline text-4xl font-extrabold tracking-tight mb-2 text-primary">Buat Laporan Perbaikan</h1>
        <p class="font-body text-lg text-on-surface-variant">Berikan detail masalah agar kami dapat segera menyelesaikannya.</p>
    </div>

    {{-- Form Card --}}
    <div class="bg-surface-container-lowest rounded-[2rem] border border-outline-variant/50 p-8 md:p-12 shadow-sm">
        <form action="{{ route('tenant.tickets.store') }}" method="POST" class="flex flex-col gap-8">
            @csrf
            <input type="hidden" name="room_id" value="{{ $activeTransaction->room_id }}">

            {{-- Title Input --}}
            <div class="flex flex-col gap-2">
                <label class="font-label text-sm font-semibold tracking-wide text-on-surface" for="title">Judul Masalah</label>
                <input class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-3 text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all duration-300" 
                       id="title" name="title" placeholder="Cth. Keran Bocor di Kamar Mandi Utama" type="text" required>
                @error('title') <span class="text-error text-xs font-semibold">{{ $message }}</span> @enderror
            </div>

            {{-- Priority Input --}}
            <div class="flex flex-col gap-2">
                <label class="font-label text-sm font-semibold tracking-wide text-on-surface" for="location">Properti / Unit</label>
                <div class="relative">
                    <input type="text" class="w-full bg-surface-container-highest border-none rounded-xl px-4 py-3 text-on-surface font-semibold focus:outline-none cursor-not-allowed" 
                           value="{{ $activeTransaction->room->boardingHouse->name }} - Kamar {{ $activeTransaction->room->type_name }}" disabled>
                </div>
            </div>

            {{-- Priority Pills --}}
            <div class="flex flex-col gap-3">
                <label class="font-label text-sm font-semibold tracking-wide text-on-surface">Tingkat Prioritas</label>
                <div class="flex flex-wrap gap-4">
                    <label class="cursor-pointer">
                        <input class="peer sr-only" name="priority" type="radio" value="normal" checked>
                        <div class="px-6 py-2 rounded-full border border-outline-variant/50 text-on-surface font-label text-sm font-semibold peer-checked:bg-primary peer-checked:text-on-primary peer-checked:border-primary transition-all duration-300 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-surface-tint peer-checked:bg-on-primary"></span>
                            Biasa
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input class="peer sr-only" name="priority" type="radio" value="urgent">
                        <div class="px-6 py-2 rounded-full border border-outline-variant/50 text-on-surface font-label text-sm font-semibold peer-checked:bg-primary peer-checked:text-on-primary peer-checked:border-primary transition-all duration-300 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-error peer-checked:bg-on-primary"></span>
                            Urgent
                        </div>
                    </label>
                </div>
                @error('priority') <span class="text-error text-xs font-semibold">{{ $message }}</span> @enderror
            </div>

            {{-- Description Textarea --}}
            <div class="flex flex-col gap-2">
                <label class="font-label text-sm font-semibold tracking-wide text-on-surface" for="description">Deskripsi Masalah</label>
                <textarea class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-3 text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all duration-300 resize-none" 
                          id="description" name="description" placeholder="Mohon jelaskan masalah secara detail. Kapan mulai terjadi? Apa yang sebenarnya terjadi?" rows="4" required></textarea>
                @error('description') <span class="text-error text-xs font-semibold">{{ $message }}</span> @enderror
            </div>

            {{-- Dashed Upload Area --}}
            <div class="flex flex-col gap-2">
                <label class="font-label text-sm font-semibold tracking-wide text-on-surface" for="photo_url">Foto / Video (Opsional)</label>
                {{-- Just an input text for URL to simulate for now since we don't have file upload implemented fully --}}
                <input class="w-full bg-surface-container-low border border-outline-variant/50 rounded-xl px-4 py-3 text-on-surface placeholder:text-on-surface-variant focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition-all duration-300" 
                       id="photo_url" name="photo_url" placeholder="URL Foto (opsional)" type="url">
                
                <div class="w-full border-2 border-dashed border-outline-variant/50 rounded-2xl p-8 flex flex-col items-center justify-center gap-4 bg-surface-container-lowest mt-2 opacity-50 cursor-not-allowed">
                    <div class="w-12 h-12 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface">
                        <span class="material-symbols-outlined text-2xl">cloud_upload</span>
                    </div>
                    <div class="text-center">
                        <p class="font-label text-sm font-semibold text-on-surface-variant">Unggah berkas belum tersedia.</p>
                        <p class="font-body text-xs text-on-surface-variant mt-1">Silakan gunakan link URL foto di atas.</p>
                    </div>
                </div>
                @error('photo_url') <span class="text-error text-xs font-semibold">{{ $message }}</span> @enderror
            </div>

            {{-- Submit Button --}}
            <div class="pt-6 mt-2">
                <button type="submit" class="w-full bg-primary text-on-primary rounded-xl px-8 py-4 font-label text-sm font-semibold tracking-wide hover:bg-inverse-surface transition-colors active:scale-95 flex items-center justify-center gap-2 shadow-sm">
                    <span>Kirim Laporan</span>
                    <span class="material-symbols-outlined text-[20px]">send</span>
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@extends('layouts.tenant', ['activeContract' => $activeContract ?? null])

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
        <form action="{{ route('tenant.tickets.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-8">
            @csrf
            <input type="hidden" name="room_id" value="{{ $activeContract->room_id }}">

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
                           value="{{ $activeContract->room->boardingHouse->name }} - Kamar {{ $activeContract->room->type_name }}" disabled>
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

            {{-- Photo Upload --}}
            <div class="flex flex-col gap-2">
                <label class="font-label text-sm font-semibold tracking-wide text-on-surface" for="photo">Foto / Video (Opsional)</label>
                
                <div class="w-full border-2 border-dashed border-outline-variant/50 rounded-2xl p-8 flex flex-col items-center justify-center gap-4 bg-surface-container-lowest hover:border-primary transition-colors cursor-pointer" id="upload-area">
                    <input type="file" name="photo" id="photo" accept="image/jpeg,image/jpg,image/png,image/webp" class="hidden">
                    <div class="w-12 h-12 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface">
                        <span class="material-symbols-outlined text-2xl">cloud_upload</span>
                    </div>
                    <div class="text-center">
                        <p class="font-label text-sm font-semibold text-on-surface-variant">Klik untuk unggah foto</p>
                        <p class="font-body text-xs text-on-surface-variant mt-1">Format: JPG, PNG, WEBP (Maks. 5MB)</p>
                    </div>
                </div>
                
                <div id="preview-container" class="hidden mt-4">
                    <div class="relative inline-block">
                        <img id="preview-image" src="" alt="Preview" class="max-w-full h-auto rounded-xl max-h-64 object-cover">
                        <button type="button" id="remove-image" class="absolute -top-2 -right-2 w-8 h-8 bg-error text-on-error rounded-full flex items-center justify-center hover:bg-error/80 transition-colors">
                            <span class="material-symbols-outlined text-[18px]">close</span>
                        </button>
                    </div>
                </div>
                
                @error('photo') <span class="text-error text-xs font-semibold">{{ $message }}</span> @enderror
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

<script>
    // File upload preview
    const uploadArea = document.getElementById('upload-area');
    const photoInput = document.getElementById('photo');
    const previewContainer = document.getElementById('preview-container');
    const previewImage = document.getElementById('preview-image');
    const removeImageBtn = document.getElementById('remove-image');

    uploadArea.addEventListener('click', () => {
        photoInput.click();
    });

    photoInput.addEventListener('change', (e) => {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImage.src = e.target.result;
                previewContainer.classList.remove('hidden');
                uploadArea.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    });

    removeImageBtn.addEventListener('click', () => {
        photoInput.value = '';
        previewImage.src = '';
        previewContainer.classList.add('hidden');
        uploadArea.classList.remove('hidden');
    });
</script>

@endsection

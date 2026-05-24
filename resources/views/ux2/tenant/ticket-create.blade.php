@extends('layouts.ux2.tenant')

@section('title', 'Buat Laporan Kerusakan - KosKu')

@section('content')
<div class="px-6 py-8 max-w-3xl">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('ux2.tenant.tickets') }}" class="inline-flex items-center gap-1 text-on-surface-variant hover:text-primary transition-colors font-label-md text-label-md mb-4">
            <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            Kembali ke Daftar Laporan
        </a>
        <h1 class="font-headline-md text-headline-md font-bold text-primary mb-2">Buat Laporan Baru</h1>
        <p class="font-body-md text-body-md text-on-surface-variant">Sampaikan keluhan atau laporan kerusakan fasilitas kos Anda.</p>
    </div>

    <!-- Form -->
    <div class="bg-surface-container-lowest border border-outline-variant/50 rounded-2xl p-6 shadow-sm">
        <form action="{{ route('ux2.tenant.tickets.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-6">
            @csrf
            
            <input type="hidden" name="room_id" value="{{ $activeTransaction->room_id }}">

            <!-- Title Input -->
            <div class="flex flex-col gap-2">
                <label for="title" class="font-label-md text-label-md text-primary">Judul Laporan</label>
                <input type="text" id="title" name="title" placeholder="Contoh: AC Kamar Bocor" class="w-full bg-surface border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary text-primary font-body-md transition-all" required />
                @error('title') <span class="text-error text-xs font-semibold">{{ $message }}</span> @enderror
            </div>

            <!-- Properti / Unit -->
            <div class="flex flex-col gap-2">
                <label class="font-label-md text-label-md text-primary">Properti / Unit</label>
                <input type="text" class="w-full bg-surface-container border-none rounded-xl px-4 py-3 text-on-surface-variant font-body-md focus:outline-none cursor-not-allowed" 
                       value="{{ $activeTransaction->room->boardingHouse->name }} - Kamar {{ $activeTransaction->room->type_name }}" disabled>
            </div>

            <!-- Priority -->
            <div class="flex flex-col gap-3">
                <label class="font-label-md text-label-md text-primary">Tingkat Prioritas</label>
                <div class="flex flex-wrap gap-4">
                    <label class="cursor-pointer">
                        <input class="peer sr-only" name="priority" type="radio" value="normal" checked>
                        <div class="px-6 py-2.5 rounded-full border border-outline-variant text-on-surface-variant font-label-md text-label-md peer-checked:bg-primary peer-checked:text-on-primary peer-checked:border-primary transition-all duration-300 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-surface-variant peer-checked:bg-on-primary"></span>
                            Biasa
                        </div>
                    </label>
                    <label class="cursor-pointer">
                        <input class="peer sr-only" name="priority" type="radio" value="urgent">
                        <div class="px-6 py-2.5 rounded-full border border-outline-variant text-on-surface-variant font-label-md text-label-md peer-checked:bg-error peer-checked:text-on-error peer-checked:border-error transition-all duration-300 flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full bg-error peer-checked:bg-on-error"></span>
                            Urgent
                        </div>
                    </label>
                </div>
                @error('priority') <span class="text-error text-xs font-semibold">{{ $message }}</span> @enderror
            </div>

            <!-- Description -->
            <div class="flex flex-col gap-2">
                <label for="description" class="font-label-md text-label-md text-primary">Deskripsi Detail</label>
                <textarea id="description" name="description" rows="4" placeholder="Jelaskan secara detail kerusakan atau keluhan yang Anda alami..." class="w-full bg-surface border border-outline-variant rounded-xl px-4 py-3 focus:outline-none focus:border-secondary focus:ring-1 focus:ring-secondary text-primary font-body-md transition-all resize-y" required></textarea>
                @error('description') <span class="text-error text-xs font-semibold">{{ $message }}</span> @enderror
            </div>

            <!-- Photo Attachment -->
            <div class="flex flex-col gap-2">
                <label class="font-label-md text-label-md text-primary" for="photo">Lampirkan Foto (Opsional)</label>
                <div class="border-2 border-dashed border-outline-variant rounded-xl p-8 text-center hover:bg-surface-container transition-colors cursor-pointer group" id="upload-area">
                    <div class="w-12 h-12 rounded-full bg-surface-variant text-on-surface-variant flex items-center justify-center mx-auto mb-3 group-hover:bg-secondary-container group-hover:text-on-secondary-container transition-colors">
                        <span class="material-symbols-outlined">add_a_photo</span>
                    </div>
                    <p class="font-label-md text-label-md text-primary mb-1">Klik untuk unggah foto</p>
                    <p class="font-label-sm text-label-sm text-on-surface-variant">Maksimal 5MB. Format: JPG, PNG, WEBP</p>
                    <input type="file" id="photo" name="photo" class="hidden" accept="image/jpeg,image/jpg,image/png,image/webp" />
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

            <div class="pt-4 border-t border-outline-variant/30 flex justify-end gap-3">
                <a href="{{ route('ux2.tenant.tickets') }}" class="px-6 py-2.5 rounded-xl font-label-md text-label-md text-on-surface-variant hover:bg-surface-container transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 bg-primary text-on-primary rounded-xl font-label-md text-label-md font-medium hover:bg-inverse-surface transition-colors shadow-sm flex items-center gap-2">
                    Kirim Laporan <span class="material-symbols-outlined text-[18px]">send</span>
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

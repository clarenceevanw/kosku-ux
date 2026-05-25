@extends('layouts.ux2.bot')

@section('title', 'KosBot AI Chat')

@section('content')
        <!-- Chat Header -->
        <header
            class="flex items-center justify-between px-6 py-4 border-b border-outline-variant/30 bg-surface-container-lowest z-10 shrink-0">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-full bg-tertiary-fixed flex items-center justify-center text-on-tertiary-fixed">
                    <span class="material-symbols-outlined">smart_toy</span>
                </div>
                <div>
                    <h1 class="font-headline-md text-headline-md text-primary">KosBot AI</h1>
                    <p class="font-label-sm text-label-sm text-on-surface-variant flex items-center gap-1">
                        <span class="w-2 h-2 rounded-full bg-secondary-container inline-block"></span>
                        Online
                    </p>
                </div>
            </div>
            <button class="p-2 rounded-full hover:bg-surface-container-high text-on-surface-variant transition-colors">
                <span class="material-symbols-outlined">more_vert</span>
            </button>
        </header>
        <!-- Chat History Area -->
        <div class="flex-1 overflow-y-auto p-6 flex flex-col gap-6 no-scrollbar">
            <!-- Date Divider -->
            <div class="flex items-center justify-center">
                <span
                    class="px-3 py-1 bg-surface-container-high rounded-full font-label-sm text-label-sm text-on-surface-variant">{{ now()->translatedFormat('d M Y') }}</span>
            </div>
            <!-- AI Message -->
            <div class="flex items-start gap-4 max-w-[85%]">
                <div class="w-8 h-8 rounded-full bg-tertiary-fixed flex items-center justify-center shrink-0 mt-1">
                    <span class="material-symbols-outlined text-sm text-on-tertiary-fixed">smart_toy</span>
                </div>
                <div
                    class="bg-surface-container-high text-on-surface rounded-2xl rounded-tl-sm p-4 shadow-[0_4px_20px_rgba(15,23,42,0.02)]">
                    <p class="font-body-md text-body-md">Halo! Saya KosBot AI. Ada yang bisa saya bantu hari ini untuk
                        mencari kos yang sesuai dengan kebutuhanmu?</p>
                </div>
            </div>
                        
            <!-- AI Typing Indicator -->
            <div class="flex items-start gap-4 max-w-[85%]">
                <div class="w-8 h-8 rounded-full bg-tertiary-fixed flex items-center justify-center shrink-0 mt-1">
                    <span class="material-symbols-outlined text-sm text-on-tertiary-fixed">smart_toy</span>
                </div>
                <div
                    class="bg-surface-container-high text-on-surface rounded-2xl rounded-tl-sm p-4 shadow-[0_4px_20px_rgba(15,23,42,0.02)] flex gap-1 items-center h-[56px]">
                    <div class="w-2 h-2 bg-on-surface-variant rounded-full animate-bounce"></div>
                    <div class="w-2 h-2 bg-on-surface-variant rounded-full animate-bounce"
                        style="animation-delay: 0.2s"></div>
                    <div class="w-2 h-2 bg-on-surface-variant rounded-full animate-bounce"
                        style="animation-delay: 0.4s"></div>
                </div>
            </div>
        </div>
        <!-- Chat Input Area -->
        <div class="px-6 pb-6 pt-2 bg-surface-container-lowest shrink-0 border-t border-outline-variant/20">
            <!-- Quick Prompts -->
            <div class="flex gap-2 overflow-x-auto no-scrollbar mb-4">
                <button
                    class="px-4 py-2 rounded-full border border-outline-variant/50 text-on-surface-variant font-label-sm text-label-sm hover:bg-surface-container-low hover:text-primary transition-colors whitespace-nowrap">
                    Ada dapur bersama?
                </button>
                <button
                    class="px-4 py-2 rounded-full border border-outline-variant/50 text-on-surface-variant font-label-sm text-label-sm hover:bg-surface-container-low hover:text-primary transition-colors whitespace-nowrap">
                    Cari yang boleh bawa hewan
                </button>
                <button
                    class="px-4 py-2 rounded-full border border-outline-variant/50 text-on-surface-variant font-label-sm text-label-sm hover:bg-surface-container-low hover:text-primary transition-colors whitespace-nowrap">
                    Budget 2 juta
                </button>
            </div>
            <!-- Input Box -->
            <div
                class="relative flex items-end gap-2 bg-surface rounded-2xl border border-outline-variant/50 focus-within:border-secondary-container focus-within:shadow-[0_0_0_2px_rgba(108,248,187,0.3)] transition-all p-2">
                <button class="p-3 text-on-surface-variant hover:text-primary transition-colors shrink-0">
                    <span class="material-symbols-outlined">attach_file</span>
                </button>
                <textarea
                    class="w-full bg-transparent border-none focus:ring-0 resize-none font-body-md text-body-md text-on-surface placeholder:text-on-surface-variant/60 py-3 max-h-[120px] overflow-y-auto no-scrollbar"
                    placeholder="Tanya KosBot..." rows="1" style="min-height: 48px;"></textarea>
                <button
                    class="p-3 bg-tertiary-fixed text-on-tertiary-fixed rounded-xl hover:bg-tertiary-fixed-dim transition-colors shrink-0 mb-1 flex items-center justify-center">
                    <span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">send</span>
                </button>
            </div>
            <div class="text-center mt-2">
                <p class="font-label-sm text-label-sm text-on-surface-variant/60 text-[10px]">KosBot AI may produce
                    inaccurate information about properties or locations.</p>
            </div>
        </div>
@endsection

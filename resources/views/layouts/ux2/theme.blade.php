<style id="ux2-theme-system">
    :root {
        --ux2-ink: #17211f;
        --ux2-muted: #5f6f6a;
        --ux2-primary: #143c3a;
        --ux2-primary-soft: #dff1ec;
        --ux2-primary-deep: #0c2628;
        --ux2-secondary: #2f8f79;
        --ux2-secondary-soft: #bdebd8;
        --ux2-accent: #f2bd5e;
        --ux2-accent-soft: #fff1cd;
        --ux2-violet: #6b68d8;
        --ux2-violet-soft: #e6e4ff;
        --ux2-coral: #d95f55;
        --ux2-coral-soft: #ffe2dd;
        --ux2-paper: #f7faf8;
        --ux2-card: #ffffff;
        --ux2-panel: #edf5f1;
        --ux2-panel-strong: #dfece7;
        --ux2-line: #d5dfdc;
        --ux2-shadow: 0 18px 45px rgba(15, 42, 39, 0.09);
        --ux2-shadow-soft: 0 10px 24px rgba(15, 42, 39, 0.07);
    }

    html {
        scroll-behavior: smooth;
    }

    body {
        background: var(--ux2-paper) !important;
        color: var(--ux2-ink) !important;
        font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif !important;
    }

    body::before {
        content: "";
        position: fixed;
        inset: 0;
        z-index: -1;
        pointer-events: none;
        background-image:
            linear-gradient(rgba(20, 60, 58, 0.045) 1px, transparent 1px),
            linear-gradient(90deg, rgba(20, 60, 58, 0.045) 1px, transparent 1px);
        background-size: 34px 34px;
        mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.9), transparent 82%);
    }

    * {
        letter-spacing: 0 !important;
    }

    [x-cloak] {
        display: none !important;
    }

    .material-symbols-outlined {
        vertical-align: middle;
    }

    .rounded-lg,
    .rounded-xl,
    .rounded-2xl,
    .rounded-3xl,
    .rounded-\[20px\],
    .rounded-\[24px\],
    .rounded-\[32px\] {
        border-radius: 8px !important;
    }

    .bg-background,
    .bg-surface {
        background-color: var(--ux2-paper) !important;
    }

    .bg-surface-container-lowest {
        background-color: rgba(255, 255, 255, 0.96) !important;
    }

    .bg-surface-container-low,
    .bg-surface-container,
    .bg-surface-container-high,
    .bg-surface-container-highest,
    .bg-surface-variant {
        background-color: var(--ux2-panel) !important;
    }

    .bg-primary,
    .bg-primary-container,
    .bg-inverse-surface,
    .bg-tertiary-container {
        background-color: var(--ux2-primary) !important;
    }

    .bg-primary-fixed,
    .bg-primary-fixed-dim {
        background-color: var(--ux2-primary-soft) !important;
    }

    .bg-secondary,
    .bg-secondary-container,
    .bg-secondary-fixed {
        background-color: var(--ux2-secondary-soft) !important;
    }

    .bg-tertiary-fixed,
    .bg-tertiary-fixed-dim {
        background-color: var(--ux2-violet-soft) !important;
    }

    .bg-error-container {
        background-color: var(--ux2-coral-soft) !important;
    }

    .bg-error {
        background-color: var(--ux2-coral) !important;
    }

    .text-primary,
    .text-on-background,
    .text-on-surface {
        color: var(--ux2-ink) !important;
    }

    .text-on-surface-variant,
    .text-outline,
    .text-outline-variant,
    .text-on-primary-container {
        color: var(--ux2-muted) !important;
    }

    .text-secondary,
    .text-on-secondary-container,
    .text-on-secondary-fixed,
    .text-on-secondary-fixed-variant {
        color: var(--ux2-primary) !important;
    }

    .text-on-primary,
    .text-on-tertiary,
    .text-on-error {
        color: #ffffff !important;
    }

    .text-on-tertiary-container,
    .text-on-tertiary-fixed,
    .text-on-tertiary-fixed-variant {
        color: var(--ux2-violet) !important;
    }

    .text-error,
    .text-on-error-container {
        color: var(--ux2-coral) !important;
    }

    .border-outline,
    .border-outline-variant {
        border-color: var(--ux2-line) !important;
    }

    .shadow-sm,
    .shadow-md,
    .shadow-lg,
    .shadow-xl,
    .shadow-2xl {
        box-shadow: var(--ux2-shadow-soft) !important;
    }

    input:not([type="checkbox"]):not([type="radio"]):not([type="file"]),
    select,
    textarea {
        border-color: var(--ux2-line) !important;
        border-radius: 8px !important;
        color: var(--ux2-ink) !important;
    }

    input:focus,
    select:focus,
    textarea:focus {
        border-color: var(--ux2-secondary) !important;
        --tw-ring-color: rgba(47, 143, 121, 0.22) !important;
        box-shadow: 0 0 0 3px rgba(47, 143, 121, 0.16) !important;
    }

    table thead,
    thead.bg-surface-container {
        background-color: var(--ux2-panel) !important;
    }

    tbody tr {
        transition: background-color 160ms ease, box-shadow 160ms ease, transform 160ms ease;
    }

    tbody tr:hover {
        background-color: rgba(237, 245, 241, 0.72) !important;
    }

    a,
    button {
        transition-duration: 180ms !important;
    }

    .hover\:bg-surface-container:hover,
    .hover\:bg-surface-container-high:hover,
    .hover\:bg-surface-container-low:hover {
        background-color: var(--ux2-panel-strong) !important;
    }

    .hover\:bg-inverse-surface:hover,
    .hover\:bg-primary\/90:hover,
    .hover\:bg-opacity-90:hover {
        background-color: var(--ux2-primary-deep) !important;
    }

    .hover\:text-secondary:hover,
    .hover\:text-secondary-fixed:hover,
    .hover\:text-primary:hover {
        color: var(--ux2-secondary) !important;
    }

    .ux2-shell-card {
        background: rgba(255, 255, 255, 0.92);
        border: 1px solid var(--ux2-line);
        box-shadow: var(--ux2-shadow-soft);
        backdrop-filter: blur(18px);
    }

    .ux2-dark-panel {
        background:
            linear-gradient(135deg, rgba(20, 60, 58, 0.98), rgba(12, 38, 40, 0.98)),
            var(--ux2-primary);
        color: #ffffff;
    }

    .ux2-chip {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        min-height: 30px;
        padding: 6px 10px;
        border-radius: 999px;
        background: rgba(189, 235, 216, 0.72);
        color: var(--ux2-primary);
        font-size: 12px;
        font-weight: 700;
    }

    .ux2-mobile-menu summary {
        list-style: none;
    }

    .ux2-mobile-menu summary::-webkit-details-marker {
        display: none;
    }

    .nav-item-active {
        background-color: var(--ux2-secondary-soft) !important;
        color: var(--ux2-primary) !important;
        box-shadow: inset 3px 0 0 var(--ux2-secondary);
    }
</style>

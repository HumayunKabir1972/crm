<style>
    .filament-footer-content {
        text-align: center;
        padding: 20px;
        border-top: 1px solid #e2e8f0;
        margin-top: 20px;
        display: flex;
        flex-direction: column;
        justify-content: center; /* Center horizontally */
    }
    .filament-footer-brand-info {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 10px;
    }
    .filament-footer-logo-img {
        max-height: 61px;
        width: auto;
        margin-bottom: 10px;
    }
    .filament-footer-logo-text {
        font-size: 20px;
        font-weight: 700;
        color: #1e293b;
        margin: 0 0 10px 0;
    }
    .filament-footer-powered-by {
        font-size: 14px;
        color: #64748b;
        margin: 0;
    }
</style>

<div class="filament-footer-content">
    <div class="filament-footer-brand-info">
        @php
            $logo = App\Helpers\AppHelper::getAppLogo();
        @endphp
        @if ($logo)
            <img src="{{ $logo }}" alt="CRM Logo" class="filament-footer-logo-img">
        @else
            <h3 class="filament-footer-logo-text">Enterprise CRM</h3>
        @endif
        <p class="filament-footer-powered-by">Powered by: FutureLink IT (+8801752790529)</p>
    </div>
</div>
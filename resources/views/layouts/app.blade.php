<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Enterprise CRM</title>

    {{-- Styles --}}
    <link rel="stylesheet" href="{{ asset('css/crm.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @livewireStyles
    @stack('styles')
</head>
<body>
    @yield('content')

    {{-- Notification Container --}}
    <div class="crm-notifications"></div>

    {{-- Scripts --}}
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="{{ asset('js/crm.js') }}"></script>
    @livewireScripts

    <script>
        // Livewire notification listener
        window.addEventListener('notify', event => {
            CRM.showNotification(
                event.detail.type === 'success' ? 'Success!' : 'Error!',
                event.detail.message,
                event.detail.type
            );
        });

        // Modal listeners
        window.addEventListener('open-modal', event => {
            const modal = document.getElementById(event.detail.modal);
            if (modal) CRM.openModal(modal);
        });

        window.addEventListener('close-modal', () => {
            const activeModal = document.querySelector('.crm-modal.active');
            if (activeModal) CRM.closeModal(activeModal);
        });
    </script>

    @stack('scripts')
</body>
</html>
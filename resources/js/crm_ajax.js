
window.archiveCustomer = async function (customerId) {
    if (!confirm('Are you sure you want to archive this customer?')) {
        return;
    }

    try {
        const response = await fetch(`/api/customers/${customerId}/archive`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
        });

        const data = await response.json();

        if (response.ok) {
            // Display success notification (Filament's built-in notification system)
            window.Livewire.dispatch('filament::notify', {
                title: 'Success',
                body: data.message,
                color: 'success',
            });

            // Optionally, refresh the table or update the row visually
            // This will refresh the entire Livewire component (the table)
            window.Livewire.dispatch('refreshComponent', { component: 'customer-resource.list-customers' });

        } else {
            // Display error notification
            window.Livewire.dispatch('filament::notify', {
                title: 'Error',
                body: data.message || 'An unknown error occurred.',
                color: 'danger',
            });
        }
    } catch (error) {
        console.error('Error archiving customer:', error);
        window.Livewire.dispatch('filament::notify', {
            title: 'Error',
            body: 'Network error or server unreachable.',
            color: 'danger',
        });
    }
};

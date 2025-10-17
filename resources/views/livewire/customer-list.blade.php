<div>
    <div class="crm-table-header">
        <h2 class="crm-table-header__title">Customers</h2>
        <div class="crm-table-header__actions">
            <input type="search"
                   wire:model.debounce.300ms="search"
                   placeholder="Search customers..."
                   class="crm-form__input">

            <select wire:model="filterStatus" class="crm-form__select">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="prospect">Prospect</option>
            </select>

            <a href="{{ route('filament.resources.customers.create') }}" class="crm-btn crm-btn--primary">
                <i class="fas fa-plus"></i> Add Customer
            </a>
        </div>
    </div>

    <div class="crm-table-container">
        <table class="crm-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Company</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Assigned To</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($customers as $customer)
                <tr>
                    <td data-label="Name">{{ $customer->full_name }}</td>
                    <td data-label="Email">{{ $customer->email }}</td>
                    <td data-label="Company">{{ $customer->company_name ?? 'N/A' }}</td>
                    <td data-label="Status">
                        <span class="crm-badge crm-badge--{{ $customer->status === 'active' ? 'success' : ($customer->status === 'prospect' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($customer->status) }}
                        </span>
                    </td>
                    <td data-label="Priority">
                        <span class="crm-badge crm-badge--{{ $customer->priority === 'critical' ? 'danger' : ($customer->priority === 'high' ? 'warning' : 'primary') }}">
                            {{ ucfirst($customer->priority) }}
                        </span>
                    </td>
                    <td data-label="Assigned To">{{ $customer->assignedUser->name ?? 'Unassigned' }}</td>
                    <td data-label="Actions">
                        <a href="{{ route('filament.resources.customers.edit', $customer) }}" class="crm-btn crm-btn--sm crm-btn--outline">
                            <i class="fas fa-edit"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="crm-pagination">
            {{ $customers->links() }}
        </div>
    </div>
</div>
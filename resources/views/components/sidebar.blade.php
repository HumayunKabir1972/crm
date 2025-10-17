<nav class="crm-nav">
    <div class="crm-nav__logo">
        <h2>Enterprise CRM</h2>
    </div>

    <ul class="crm-nav__list">
        <li class="crm-nav__item">
            <a href="{{ route('dashboard') }}" class="crm-nav__link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="crm-nav__icon fas fa-home"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="crm-nav__item">
            <a href="{{ route('filament.futureLinkIT.resources.customers.index') }}" class="crm-nav__link">
                <i class="crm-nav__icon fas fa-users"></i>
                <span>Customers</span>
            </a>
        </li>
        <li class="crm-nav__item">
            <a href="{{ route('filament.futureLinkIT.resources.leads.index') }}" class="crm-nav__link">
                <i class="crm-nav__icon fas fa-chart-bar"></i>
                <span>Leads</span>
            </a>
        </li>
        <li class="crm-nav__item">
            <a href="{{ route('filament.futureLinkIT.resources.deals.index') }}" class="crm-nav__link">
                <i class="crm-nav__icon fas fa-handshake"></i>
                <span>Deals</span>
            </a>
        </li>
        <li class="crm-nav__item">
            <a href="{{ route('filament.futureLinkIT.resources.tasks.index') }}" class="crm-nav__link">
                <i class="crm-nav__icon fas fa-tasks"></i>
                <span>Tasks</span>
            </a>
        </li>
        <li class="crm-nav__item">
            <a href="{{ route('filament.futureLinkIT.resources.contacts.index') }}" class="crm-nav__link">
                <i class="crm-nav__icon fas fa-address-book"></i>
                <span>Contacts</span>
            </a>
        </li>
        <li class="crm-nav__item">
            <a href="{{ route('filament.futureLinkIT.resources.companies.index') }}" class="crm-nav__link">
                <i class="crm-nav__icon fas fa-building"></i>
                <span>Companies</span>
            </a>
        </li>
        <li class="crm-nav__item">
            <a href="{{ route('filament.futureLinkIT.resources.products.index') }}" class="crm-nav__link">
                <i class="crm-nav__icon fas fa-box"></i>
                <span>Products</span>
            </a>
        </li>
        <li class="crm-nav__item">
            <a href="{{ route('filament.futureLinkIT.resources.invoices.index') }}" class="crm-nav__link">
                <i class="crm-nav__icon fas fa-file-invoice"></i>
                <span>Invoices</span>
            </a>
        </li>
        <li class="crm-nav__item">
            <a href="{{ route('filament.futureLinkIT.resources.quotes.index') }}" class="crm-nav__link">
                <i class="crm-nav__icon fas fa-file-contract"></i>
                <span>Quotes</span>
            </a>
        </li>
        <li class="crm-nav__item">
            <a href="{{ route('filament.futureLinkIT.resources.tickets.index') }}" class="crm-nav__link">
                <i class="crm-nav__icon fas fa-ticket-alt"></i>
                <span>Tickets</span>
            </a>
        </li>

        {{-- Administration --}}
        <li class="crm-nav__item">
            <a href="{{ route('reports') }}" class="crm-nav__link">
                <i class="crm-nav__icon fas fa-chart-pie"></i>
                <span>Reports</span>
            </a>
        </li>
        <li class="crm-nav__item">
            <a href="{{ route('settings') }}" class="crm-nav__link">
                <i class="crm-nav__icon fas fa-cog"></i>
                <span>Settings</span>
            </a>
        </li>
    </ul>
</nav>
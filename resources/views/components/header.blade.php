<header class="crm-header">
    <div class="crm-header__logo">
        @php
            $logo = App\Helpers\AppHelper::getAppLogo();
        @endphp
        @if ($logo)
            <img src="{{ $logo }}" alt="CRM Logo" class="crm-header__logo-img">
        @else
            <h2>Enterprise CRM</h2>
        @endif
    </div>
    @php
    $companyName = '';
    if (auth()->check()) {
        $contact = App\Models\Contact::where('email', auth()->user()->email)->first();
        if ($contact && $contact->company) {
            $companyName = $contact->company->name;
        }
    }
@endphp
@if ($companyName)
    <h2 class="crm-header__company-name">{{ $companyName }}</h2>
@endif
    <button class="crm-header__menu-toggle">
        <i class="fas fa-bars"></i>
    </button>

    <div class="crm-header__search">
        <div class="crm-search">
            <input type="search" class="crm-search__input crm-form__input" placeholder="Search...">
            <div class="crm-search__results"></div>
        </div>
    </div>

    <div class="crm-header__actions">
        <button class="crm-header__action" title="Notifications">
            <i class="fas fa-bell"></i>
            <span class="crm-badge crm-badge--danger">5</span>
        </button>

        <div class="crm-header__user">
            @auth
                <img src="https://ui-avatars.com/api/?name={{ auth()->user()->name }}" alt="User" class="crm-header__avatar">
                <span class="crm-header__username">{{ auth()->user()->name }}</span>
            @else
                <a href="{{ route('filament.futureLinkIT.auth.login') }}" class="crm-btn crm-btn--sm crm-btn--outline">Login</a>
            @endauth
        </div>

        @auth
        <form action="{{ route('filament.futureLinkIT.auth.logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="crm-btn crm-btn--sm crm-btn--outline">
                <i class="fas fa-sign-out-alt"></i> Logout
            </button>
        </form>
        @endauth
    </div>
</header>

@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="crm-dashboard">
    <aside class="crm-sidebar">
        @include('components.sidebar')
    </aside>

    <main class="crm-main">
        @include('components.header')

        <div class="crm-dashboard__content">
            {{-- Page Header --}}
            <div class="crm-page-header">
                <div class="crm-page-header__content">
                    <div class="crm-page-header__text">
                        <h1 class="crm-page-header__title">Dashboard Overview</h1>
                                                <p class="crm-page-header__subtitle">@auth Welcome back, <span class="crm-highlight">{{ auth()->user()->name }}</span> @else Welcome to the Dashboard @endauth</p>
                    </div>
                    <div class="crm-page-header__meta">
                        <div class="crm-date-badge">
                            <i class="far fa-calendar"></i>
                            <span>{{ now()->format('F j, Y') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Stats Overview Cards --}}
            <div class="crm-stats-container">
                @include('livewire.dashboard-stats', ['stats' => $stats])
            </div>

            {{-- Charts Section --}}
            <div class="crm-charts-section">
                <div class="crm-chart-card crm-chart-card--primary">
                    <div class="crm-chart-card__header">
                        <div class="crm-chart-card__title-group">
                            <h3 class="crm-chart-card__title">Revenue Analytics</h3>
                            <p class="crm-chart-card__subtitle">Monthly performance tracking</p>
                        </div>
                        <div class="crm-chart-card__actions">
                            <select class="crm-select crm-select--sm">
                                <option>Last 6 Months</option>
                                <option>Last Year</option>
                                <option>All Time</option>
                            </select>
                        </div>
                    </div>
                    <div class="crm-chart-card__body">
                        <canvas id="revenueChart" class="crm-chart__canvas"></canvas>
                    </div>
                </div>

                <div class="crm-chart-card crm-chart-card--secondary">
                    <div class="crm-chart-card__header">
                        <div class="crm-chart-card__title-group">
                            <h3 class="crm-chart-card__title">Weekly Sales</h3>
                            <p class="crm-chart-card__subtitle">Current week overview</p>
                        </div>
                        <div class="crm-chart-card__actions">
                            <button class="crm-btn-icon" title="Refresh">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
                    </div>
                    <div class="crm-chart-card__body">
                        <canvas id="salesChart" class="crm-chart__canvas"></canvas>
                    </div>
                </div>
            </div>

            {{-- Activity Feed and Quick Actions --}}
            <div class="crm-grid crm-grid--layout-1">
                <div class="crm-grid__item crm-grid__item--main">
                    <div class="crm-activity-card">
                        <div class="crm-activity-card__header">
                            <h3 class="crm-activity-card__title">
                                <i class="fas fa-bell"></i>
                                Activity Feed
                            </h3>
                            <a href="#" class="crm-link">View All</a>
                        </div>
                        <div class="crm-activity-card__body">
                            @include('livewire.activity-feed', ['activities' => $stats['activity_feed']])
                        </div>
                    </div>
                </div>

                <div class="crm-grid__item crm-grid__item--sidebar">
                    <div class="crm-quick-actions-card">
                        <div class="crm-quick-actions-card__header">
                            <h3 class="crm-quick-actions-card__title">
                                <i class="fas fa-bolt"></i>
                                Quick Actions
                            </h3>
                        </div>
                        <div class="crm-quick-actions-card__body">
                            <div class="crm-quick-actions__list">
                                <a href="{{ route('filament.futureLinkIT.resources.customers.create') }}" class="crm-quick-action-btn">
                                    <div class="crm-quick-action-btn__icon crm-quick-action-btn__icon--blue">
                                        <i class="fas fa-user-plus"></i>
                                    </div>
                                    <div class="crm-quick-action-btn__content">
                                        <span class="crm-quick-action-btn__title">Add Customer</span>
                                        <span class="crm-quick-action-btn__desc">Create new customer</span>
                                    </div>
                                    <i class="fas fa-chevron-right crm-quick-action-btn__arrow"></i>
                                </a>

                                <a href="{{ route('filament.futureLinkIT.resources.leads.create') }}" class="crm-quick-action-btn">
                                    <div class="crm-quick-action-btn__icon crm-quick-action-btn__icon--green">
                                        <i class="fas fa-chart-line"></i>
                                    </div>
                                    <div class="crm-quick-action-btn__content">
                                        <span class="crm-quick-action-btn__title">New Lead</span>
                                        <span class="crm-quick-action-btn__desc">Add sales lead</span>
                                    </div>
                                    <i class="fas fa-chevron-right crm-quick-action-btn__arrow"></i>
                                </a>

                                <a href="{{ route('filament.futureLinkIT.resources.deals.create') }}" class="crm-quick-action-btn">
                                    <div class="crm-quick-action-btn__icon crm-quick-action-btn__icon--purple">
                                        <i class="fas fa-handshake"></i>
                                    </div>
                                    <div class="crm-quick-action-btn__content">
                                        <span class="crm-quick-action-btn__title">Create Deal</span>
                                        <span class="crm-quick-action-btn__desc">Start new deal</span>
                                    </div>
                                    <i class="fas fa-chevron-right crm-quick-action-btn__arrow"></i>
                                </a>

                                <a href="{{ route('filament.futureLinkIT.resources.tasks.create') }}" class="crm-quick-action-btn">
                                    <div class="crm-quick-action-btn__icon crm-quick-action-btn__icon--orange">
                                        <i class="fas fa-tasks"></i>
                                    </div>
                                    <div class="crm-quick-action-btn__content">
                                        <span class="crm-quick-action-btn__title">Add Task</span>
                                        <span class="crm-quick-action-btn__desc">Create new task</span>
                                    </div>
                                    <i class="fas fa-chevron-right crm-quick-action-btn__arrow"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Products and Recent Activities --}}
            <div class="crm-grid crm-grid--layout-2">
                <div class="crm-grid__item">
                    <div class="crm-data-card">
                        <div class="crm-data-card__header">
                            <div class="crm-data-card__title-group">
                                <h3 class="crm-data-card__title">
                                    <i class="fas fa-star"></i>
                                    Top Performing Products
                                </h3>
                                <span class="crm-badge crm-badge--success">Live</span>
                            </div>
                            <a href="#" class="crm-link crm-link--sm">See All Products</a>
                        </div>
                        <div class="crm-data-card__body">
                            @include('livewire.top-products', ['products' => $stats['topPerformingProducts']])
                        </div>
                    </div>
                </div>

                <div class="crm-grid__item">
                    <div class="crm-data-card">
                        <div class="crm-data-card__header">
                            <div class="crm-data-card__title-group">
                                <h3 class="crm-data-card__title">
                                    <i class="fas fa-history"></i>
                                    Recent Activities
                                </h3>
                                <span class="crm-badge crm-badge--info">{{ count($stats['recentActivities']) }} New</span>
                            </div>
                            <a href="#" class="crm-link crm-link--sm">View All</a>
                        </div>
                        <div class="crm-data-card__body">
                            @include('livewire.recent-activities', ['activities' => $stats['recentActivities']])
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="crm-footer">
        <div class="crm-footer__content">
            <div class="crm-footer__brand-info">
                @php
                    $logo = App\Helpers\AppHelper::getAppLogo();
                @endphp
                @if ($logo)
                    <img src="{{ $logo }}" alt="CRM Logo" class="crm-footer__logo-img">
                @else
                    <h3 class="crm-footer__logo-text">Enterprise CRM</h3>
                @endif
                <p class="crm-footer__powered-by">Powered by: FutureLink IT (+8801752790529)</p>
            </div>
        </div>
    </footer>
</div>

<style>
/* ============================================
   MODERN BUSINESS CLASS CRM DASHBOARD STYLES
   ============================================ */

@import url('https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300;400;500;600;700&display=swap');

:root {
    --crm-primary: #2563eb;
    --crm-primary-dark: #1e40af;
    --crm-secondary: #64748b;
    --crm-success: #10b981;
    --crm-warning: #f59e0b;
    --crm-danger: #ef4444;
    --crm-info: #06b6d4;
    --crm-purple: #8b5cf6;
    --crm-orange: #f97316;
    --crm-pink: #ec4899;
    --crm-teal: #14b8a6;
    --crm-indigo: #6366f1;

    --crm-bg: #f8fafc;
    --crm-surface: #ffffff;
    --crm-border: #e2e8f0;
    --crm-text: #1e293b;
    --crm-text-light: #64748b;
    --crm-text-lighter: #94a3b8;

    --crm-shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --crm-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
    --crm-shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
    --crm-shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);

    --crm-radius: 12px;
    --crm-radius-sm: 8px;
    --crm-radius-lg: 16px;

    --crm-spacing: 24px;
}

* {
    font-family: 'Roboto Condensed', sans-serif;
}

/* CRM Header Logo */
.crm-header__logo {
    display: flex;
    align-items: center;
    padding: 0 24px;
}

.crm-header__logo-img {
    max-height: 61px; /* Adjust as needed */
    width: auto;
}

/* Dashboard Layout */
.crm-dashboard {
    display: flex;
    min-height: 100vh;
    background: var(--crm-bg);
}

.crm-main {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.crm-dashboard__content {
    padding: var(--crm-spacing);
    max-width: 1600px;
    margin: 0 auto;
    width: 100%;
}

/* Page Header */
.crm-page-header {
    margin-bottom: 32px;
}

.crm-page-header__content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
}

.crm-page-header__title {
    font-size: 32px;
    font-weight: 700;
    color: var(--crm-text);
    margin: 0 0 8px 0;
    letter-spacing: -0.02em;
}

.crm-page-header__subtitle {
    font-size: 16px;
    color: var(--crm-text-light);
    margin: 0;
}

.crm-highlight {
    color: var(--crm-primary);
    font-weight: 600;
}

.crm-date-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 10px 16px;
    background: var(--crm-surface);
    border: 1px solid var(--crm-border);
    border-radius: var(--crm-radius-sm);
    font-size: 14px;
    color: var(--crm-text);
    box-shadow: var(--crm-shadow-sm);
}

.crm-date-badge i {
    color: var(--crm-primary);
}

/* Stats Container */
.crm-stats-container {
    margin-bottom: 32px;
}

/* Charts Section */
.crm-charts-section {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
    margin-bottom: 32px;
}

.crm-chart-card {
    background: var(--crm-surface);
    border-radius: var(--crm-radius);
    border: 1px solid var(--crm-border);
    box-shadow: var(--crm-shadow);
    overflow: hidden;
    transition: all 0.3s ease;
}

.crm-chart-card:hover {
    box-shadow: var(--crm-shadow-md);
    transform: translateY(-2px);
}

.crm-chart-card__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid var(--crm-border);
    background: linear-gradient(to bottom, var(--crm-surface), #fafbfc);
}

.crm-chart-card__title-group {
    flex: 1;
}

.crm-chart-card__title {
    font-size: 18px;
    font-weight: 600;
    color: var(--crm-text);
    margin: 0 0 4px 0;
}

.crm-chart-card__subtitle {
    font-size: 13px;
    color: var(--crm-text-light);
    margin: 0;
}

.crm-chart-card__body {
    padding: 24px;
}

.crm-chart__canvas {
    max-height: 300px;
}

/* Select Dropdown */
.crm-select {
    padding: 8px 32px 8px 12px;
    border: 1px solid var(--crm-border);
    border-radius: var(--crm-radius-sm);
    background: var(--crm-surface);
    color: var(--crm-text);
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%2364748b' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 10px center;
}

.crm-select--sm {
    padding: 6px 28px 6px 10px;
    font-size: 13px;
}

.crm-select:hover {
    border-color: var(--crm-primary);
}

.crm-select:focus {
    outline: none;
    border-color: var(--crm-primary);
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

/* Button Icon */
.crm-btn-icon {
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border: 1px solid var(--crm-border);
    background: var(--crm-surface);
    border-radius: var(--crm-radius-sm);
    color: var(--crm-text-light);
    cursor: pointer;
    transition: all 0.2s ease;
}

.crm-btn-icon:hover {
    background: var(--crm-bg);
    color: var(--crm-primary);
    border-color: var(--crm-primary);
}

/* Grid Layouts */
.crm-grid {
    display: grid;
    gap: 24px;
    margin-bottom: 32px;
}

.crm-grid--layout-1 {
    grid-template-columns: 1fr 380px;
}

.crm-grid--layout-2 {
    grid-template-columns: repeat(2, 1fr);
}

/* Activity Card */
.crm-activity-card {
    background: var(--crm-surface);
    border: 1px solid var(--crm-border);
    border-radius: var(--crm-radius);
    box-shadow: var(--crm-shadow);
    height: 100%;
}

.crm-activity-card__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid var(--crm-border);
}

.crm-activity-card__title {
    font-size: 18px;
    font-weight: 600;
    color: var(--crm-text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.crm-activity-card__title i {
    color: var(--crm-primary);
    font-size: 16px;
}

.crm-activity-card__body {
    padding: 0;
}

/* Quick Actions Card */
.crm-quick-actions-card {
    background: var(--crm-surface);
    border: 1px solid var(--crm-border);
    border-radius: var(--crm-radius);
    box-shadow: var(--crm-shadow);
    height: 100%;
}

.crm-quick-actions-card__header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--crm-border);
}

.crm-quick-actions-card__title {
    font-size: 18px;
    font-weight: 600;
    color: var(--crm-text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.crm-quick-actions-card__title i {
    color: var(--crm-warning);
    font-size: 16px;
}

.crm-quick-actions-card__body {
    padding: 16px;
}

.crm-quick-actions__list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

/* Quick Action Button */
.crm-quick-action-btn {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 14px 16px;
    background: var(--crm-surface);
    border: 1px solid var(--crm-border);
    border-radius: var(--crm-radius-sm);
    text-decoration: none;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.crm-quick-action-btn::before {
    content: '';
    position: absolute;
    left: 0;
    top: 0;
    height: 100%;
    width: 3px;
    background: var(--crm-primary);
    transform: scaleY(0);
    transition: transform 0.3s ease;
}

.crm-quick-action-btn:hover {
    background: #f8fafc;
    border-color: var(--crm-primary);
    transform: translateX(4px);
    box-shadow: var(--crm-shadow-sm);
}

.crm-quick-action-btn:hover::before {
    transform: scaleY(1);
}

.crm-quick-action-btn__icon {
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: var(--crm-radius-sm);
    font-size: 18px;
    color: white;
    flex-shrink: 0;
}

.crm-quick-action-btn__icon--blue {
    background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
}

.crm-quick-action-btn__icon--green {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
}

.crm-quick-action-btn__icon--purple {
    background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
}

.crm-quick-action-btn__icon--orange {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
}

.crm-quick-action-btn__content {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.crm-quick-action-btn__title {
    font-size: 15px;
    font-weight: 600;
    color: var(--crm-text);
}

.crm-quick-action-btn__desc {
    font-size: 12px;
    color: var(--crm-text-light);
}

.crm-quick-action-btn__arrow {
    font-size: 12px;
    color: var(--crm-text-lighter);
    transition: all 0.3s ease;
}

.crm-quick-action-btn:hover .crm-quick-action-btn__arrow {
    color: var(--crm-primary);
    transform: translateX(4px);
}

/* Data Card */
.crm-data-card {
    background: var(--crm-surface);
    border: 1px solid var(--crm-border);
    border-radius: var(--crm-radius);
    box-shadow: var(--crm-shadow);
    height: 100%;
}

.crm-data-card__header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid var(--crm-border);
}

.crm-data-card__title-group {
    display: flex;
    align-items: center;
    gap: 12px;
}

.crm-data-card__title {
    font-size: 18px;
    font-weight: 600;
    color: var(--crm-text);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.crm-data-card__title i {
    color: var(--crm-warning);
    font-size: 16px;
}

.crm-data-card__body {
    padding: 0;
}

/* Badge */
.crm-badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.crm-badge--success {
    background: rgba(16, 185, 129, 0.1);
    color: var(--crm-success);
}

.crm-badge--info {
    background: rgba(6, 182, 212, 0.1);
    color: var(--crm-info);
}

/* Link */
.crm-link {
    font-size: 14px;
    color: var(--crm-primary);
    text-decoration: none;
    font-weight: 500;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.crm-link--sm {
    font-size: 13px;
}

.crm-link:hover {
    color: var(--crm-primary-dark);
    text-decoration: underline;
}

.crm-link::after {
    content: '→';
    transition: transform 0.2s ease;
}

.crm-link:hover::after {
    transform: translateX(3px);
}

/* Responsive Design */
@media (max-width: 1200px) {
    .crm-charts-section {
        grid-template-columns: 1fr;
    }

    .crm-grid--layout-1 {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .crm-dashboard__content {
        padding: 16px;
    }

    .crm-page-header__title {
        font-size: 24px;
    }

    .crm-grid--layout-2 {
        grid-template-columns: 1fr;
    }

    .crm-charts-section,
    .crm-grid {
        gap: 16px;
        margin-bottom: 24px;
    }
}

/* CRM Footer */
.crm-footer {
    background: var(--crm-surface);
    border-top: 1px solid var(--crm-border);
    padding: 20px var(--crm-spacing);
    text-align: center;
    margin-top: auto; /* Pushes footer to the bottom */
}

.crm-footer__content {
    display: flex;
    flex-direction: column;
    justify-content: center; /* Center horizontally */
    gap: 10px;
}

.crm-footer__brand-info {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}

.crm-footer__logo-img {
    max-height: 61px; /* Same as header logo */
    width: auto;
}

.crm-footer__logo-text {
    font-size: 20px;
    font-weight: 700;
    color: var(--crm-text);
    margin: 0;
}

.crm-footer__powered-by {
    font-size: 14px;
    color: var(--crm-text-light);
    margin: 0;
}
</style>
@endsection
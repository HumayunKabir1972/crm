<div class="crm-stats">
    <div class="crm-stat-card">
        <div class="crm-stat-card__header">
            <div class="crm-stat-card__icon crm-stat-card__icon--primary">
                <i class="fas fa-users"></i>
            </div>
        </div>
        <div class="crm-stat-card__value">{{ number_format($stats['total_customers']) }}</div>
        <div class="crm-stat-card__label">Total Customers</div>
        <div class="crm-stat-card__trend crm-stat-card__trend--up">
            <i class="fas fa-arrow-up"></i> 12.5%
        </div>
    </div>

    <div class="crm-stat-card">
        <div class="crm-stat-card__header">
            <div class="crm-stat-card__icon crm-stat-card__icon--success">
                <i class="fas fa-handshake"></i>
            </div>
        </div>
        <div class="crm-stat-card__value">{{ number_format($stats['active_deals']) }}</div>
        <div class="crm-stat-card__label">Active Deals</div>
        <div class="crm-stat-card__trend crm-stat-card__trend--up">
            <i class="fas fa-arrow-up"></i> 8.2%
        </div>
    </div>

    <div class="crm-stat-card">
        <div class="crm-stat-card__header">
            <div class="crm-stat-card__icon crm-stat-card__icon--warning">
                <i class="fas fa-dollar-sign"></i>
            </div>
        </div>
        <div class="crm-stat-card__value">${{ number_format($stats['monthly_revenue'], 2) }}</div>
        <div class="crm-stat-card__label">Monthly Revenue</div>
        <div class="crm-stat-card__trend {{ $stats['revenueGrowth'] >= 0 ? 'crm-stat-card__trend--up' : 'crm-stat-card__trend--down' }}">
            <i class="fas fa-arrow-{{ $stats['revenueGrowth'] >= 0 ? 'up' : 'down' }}"></i> {{ number_format(abs($stats['revenueGrowth']), 1) }}%
        </div>
    </div>

    <div class="crm-stat-card">
        <div class="crm-stat-card__header">
            <div class="crm-stat-card__icon crm-stat-card__icon--danger">
                <i class="fas fa-ticket-alt"></i>
            </div>
        </div>
        <div class="crm-stat-card__value">{{ number_format($stats['open_tickets']) }}</div>
        <div class="crm-stat-card__label">Open Tickets</div>
    </div>

    <div class="crm-stat-card">
        <div class="crm-stat-card__header">
            <div class="crm-stat-card__icon crm-stat-card__icon--info">
                <i class="fas fa-chart-line"></i>
            </div>
        </div>
        <div class="crm-stat-card__value">{{ number_format($stats['newLeadsThisMonth']) }}</div>
        <div class="crm-stat-card__label">New Leads This Month</div>
    </div>

    <div class="crm-stat-card">
        <div class="crm-stat-card__header">
            <div class="crm-stat-card__icon crm-stat-card__icon--secondary">
                <i class="fas fa-funnel-dollar"></i>
            </div>
        </div>
        <div class="crm-stat-card__value">${{ number_format($stats['dealsInPipeline'], 2) }}</div>
        <div class="crm-stat-card__label">Deals in Pipeline</div>
    </div>
</div>
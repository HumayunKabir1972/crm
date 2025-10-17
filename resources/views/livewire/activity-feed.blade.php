<div class="crm-activity">
    <div class="crm-activity__header">
        <h3 class="crm-chart__title">Recent Activities</h3>
        <div class="crm-activity__filters">
            <select wire:model="filter" class="crm-form__select">
                <option value="all">All Activities</option>
                <option value="email">Emails</option>
                <option value="call">Calls</option>
                <option value="meeting">Meetings</option>
                <option value="note">Notes</option>
                <option value="status_change">Status Changes</option>
            </select>
        </div>
    </div>

    <div class="crm-activity__list">
        @forelse($activities as $activity)
        <div class="crm-activity__item" wire:key="activity-{{ $activity->id }}">
            <div class="crm-activity__icon crm-stat-card__icon--{{ $activity->type === 'email' ? 'primary' : ($activity->type === 'call' ? 'success' : 'warning') }}">
                <i class="fas fa-{{ $activity->type === 'email' ? 'envelope' : ($activity->type === 'call' ? 'phone' : 'sticky-note') }}"></i>
            </div>
            <div class="crm-activity__content">
                <div class="crm-activity__text">
                    <strong>{{ $activity->user->name }}</strong> {{ $activity->description }}
                </div>
                <div class="crm-activity__time">
                    {{ $activity->activity_date->diffForHumans() }}
                </div>
            </div>
        </div>
        @empty
        <div class="crm-empty-state">
            <div class="crm-empty-state__icon">
                <i class="fas fa-inbox"></i>
            </div>
            <div class="crm-empty-state__text">No activities found</div>
        </div>
        @endforelse
    </div>

    
</div>
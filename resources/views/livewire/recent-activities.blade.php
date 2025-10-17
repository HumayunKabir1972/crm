<div class="crm-recent-activities">
    <ul class="crm-recent-activities__list">
        @forelse($activities as $activity)
            <li class="crm-recent-activities__item">
                <div class="crm-recent-activities__activity-description">{{ $activity->description }}</div>
                <div class="crm-recent-activities__activity-time">{{ $activity->created_at->diffForHumans() }}</div>
            </li>
        @empty
            <li class="crm-recent-activities__empty">No recent activities found.</li>
        @endforelse
    </ul>
</div>

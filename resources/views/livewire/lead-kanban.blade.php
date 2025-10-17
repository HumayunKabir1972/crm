<div class="crm-kanban">
    @foreach($stages as $stageKey => $stageName)
    <div class="crm-kanban__column" data-stage="{{ $stageKey }}">
        <div class="crm-kanban__header">
            <h3 class="crm-kanban__title">{{ $stageName }}</h3>
            <span class="crm-kanban__count">{{ $leads[$stageKey]->count() ?? 0 }}</span>
        </div>

        <div class="crm-kanban__cards" wire:sortable="updateStage">
            @if(isset($leads[$stageKey]))
                @foreach($leads[$stageKey] as $lead)
                <div class="crm-kanban-card"
                     data-id="{{ $lead->id }}"
                     wire:sortable.item="{{ $lead->id }}"
                     wire:key="lead-{{ $lead->id }}">
                    <div class="crm-kanban-card__title">{{ $lead->full_name }}</div>
                    <div class="crm-kanban-card__company">{{ $lead->company }}</div>
                    <div class="crm-kanban-card__meta">
                        <span class="crm-badge crm-badge--{{ $lead->quality === 'hot' ? 'danger' : ($lead->quality === 'warm' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($lead->quality) }}
                        </span>
                        <span class="crm-kanban-card__score">Score: {{ $lead->lead_score }}</span>
                    </div>
                    @if($lead->estimated_value)
                    <div class="crm-kanban-card__value">${{ number_format($lead->estimated_value, 2) }}</div>
                    @endif
                    <div class="crm-kanban-card__footer">
                        @if($lead->assignedUser)
                        <span class="crm-kanban-card__assignee">{{ $lead->assignedUser->name }}</span>
                        @endif
                    </div>
                </div>
                @endforeach
            @endif
        </div>
    </div>
    @endforeach
</div>
<div class="crm-kanban">
    @foreach($stages as $stageKey => $stageName)
    <div class="crm-kanban__column" data-stage="{{ $stageKey }}">
        <div class="crm-kanban__header">
            <h3 class="crm-kanban__title">{{ $stageName }}</h3>
            <span class="crm-kanban__count">{{ $deals[$stageKey]->count() ?? 0 }}</span>
        </div>

        <div class="crm-kanban__cards">
            @if(isset($deals[$stageKey]))
                @foreach($deals[$stageKey] as $deal)
                <div class="crm-kanban-card"
                     data-id="{{ $deal->id }}"
                     wire:key="deal-{{ $deal->id }}"
                     wire:click="viewDeal({{ $deal->id }})"
                     style="cursor: pointer;">
                    <div class="crm-kanban-card__title">{{ $deal->title }}</div>
                    @if($deal->customer)
                    <div class="crm-kanban-card__customer">{{ $deal->customer->full_name }}</div>
                    @endif
                    <div class="crm-kanban-card__amount">${{ number_format($deal->amount, 2) }}</div>
                    <div class="crm-kanban-card__meta">
                        <span class="crm-badge crm-badge--{{ $deal->priority === 'critical' ? 'danger' : ($deal->priority === 'high' ? 'warning' : 'primary') }}">
                            {{ ucfirst($deal->priority) }}
                        </span>
                        <span class="crm-kanban-card__probability">{{ $deal->probability }}%</span>
                    </div>
                    @if($deal->expected_close_date)
                    <div class="crm-kanban-card__date">
                        <i class="fas fa-calendar"></i> {{ $deal->expected_close_date->format('M d, Y') }}
                    </div>
                    @endif
                    @if($deal->assignedUser)
                    <div class="crm-kanban-card__assignee">{{ $deal->assignedUser->name }}</div>
                    @endif
                </div>
                @endforeach
            @endif
        </div>
    </div>
    @endforeach
</div>
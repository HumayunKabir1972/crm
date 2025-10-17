<div>
    <div class="crm-table-header">
        <div class="crm-table-header__actions">
            <button class="crm-btn crm-btn--primary" data-modal-target="createTaskModal">
                <i class="fas fa-plus"></i> New Task
            </button>

            <div class="crm-form__group">
                <select wire:model="filterPriority" class="crm-form__select">
                    <option value="">All Priorities</option>
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>

            <label class="crm-form__checkbox">
                <input type="checkbox" wire:model="showCompleted">
                Show Completed
            </label>
        </div>
    </div>

    <div class="crm-table-container">
        <table class="crm-table">
            <thead>
                <tr>
                    <th>Task</th>
                    <th>Type</th>
                    <th>Priority</th>
                    <th>Assigned To</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($tasks as $task)
                <tr wire:key="task-{{ $task->id }}">
                    <td data-label="Task">
                        <strong>{{ $task->title }}</strong>
                        @if($task->description)
                        <br><small>{{ Str::limit($task->description, 50) }}</small>
                        @endif
                    </td>
                    <td data-label="Type">
                        <span class="crm-badge crm-badge--primary">{{ ucfirst($task->type) }}</span>
                    </td>
                    <td data-label="Priority">
                        <span class="crm-badge crm-badge--{{ $task->priority === 'urgent' ? 'danger' : ($task->priority === 'high' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($task->priority) }}
                        </span>
                    </td>
                    <td data-label="Assigned To">{{ $task->assignedUser->name ?? 'N/A' }}</td>
                    <td data-label="Due Date">
                        @if($task->due_date)
                        {{ $task->due_date->format('M d, Y') }}
                        @else
                        N/A
                        @endif
                    </td>
                    <td data-label="Status">
                        <span class="crm-badge crm-badge--{{ $task->status === 'completed' ? 'success' : ($task->status === 'in_progress' ? 'warning' : 'secondary') }}">
                            {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                        </span>
                    </td>
                    <td data-label="Actions">
                        @if($task->status !== 'completed')
                        <button wire:click="completeTask({{ $task->id }})" class="crm-btn crm-btn--sm crm-btn--success">
                            <i class="fas fa-check"></i>
                        </button>
                        @endif
                        <button wire:click="deleteTask({{ $task->id }})" class="crm-btn crm-btn--sm crm-btn--danger">
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="crm-pagination">
            {{ $tasks->links() }}
        </div>
    </div>

    {{-- Create Task Modal --}}
    <div class="crm-modal" id="createTaskModal">
        <div class="crm-modal__content">
            <div class="crm-modal__header">
                <h3 class="crm-modal__title">Create New Task</h3>
                <button class="crm-modal__close">&times;</button>
            </div>
            <div class="crm-modal__body">
                <form wire:submit.prevent="createTask">
                    <div class="crm-form__group">
                        <label class="crm-form__label">Title *</label>
                        <input type="text" wire:model="title" class="crm-form__input" required>
                        @error('title') <span class="crm-form__error">{{ $message }}</span> @enderror
                    </div>

                    <div class="crm-form__group">
                        <label class="crm-form__label">Description</label>
                        <textarea wire:model="description" class="crm-form__textarea"></textarea>
                    </div>

                    <div class="crm-form__row">
                        <div class="crm-form__group">
                            <label class="crm-form__label">Type *</label>
                            <select wire:model="type" class="crm-form__select" required>
                                <option value="todo">To Do</option>
                                <option value="call">Call</option>
                                <option value="meeting">Meeting</option>
                                <option value="email">Email</option>
                                <option value="deadline">Deadline</option>
                                <option value="follow_up">Follow Up</option>
                            </select>
                        </div>

                        <div class="crm-form__group">
                            <label class="crm-form__label">Priority *</label>
                            <select wire:model="priority" class="crm-form__select" required>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                            </select>
                        </div>
                    </div>

                    <div class="crm-form__group">
                        <label class="crm-form__label">Due Date</label>
                        <input type="datetime-local" wire:model="due_date" class="crm-form__input">
                    </div>

                    <div class="crm-modal__footer">
                        <button type="button" class="crm-btn crm-btn--outline" data-modal-close>Cancel</button>
                        <button type="submit" class="crm-btn crm-btn--primary">Create Task</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
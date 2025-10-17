<?php
namespace App\Http\Livewire;

use App\Models\Task;
use Livewire\Component;
use Livewire\WithPagination;

class TaskManager extends Component
{
    use WithPagination;

    public $showCompleted = false;
    public $filterPriority = '';
    public $filterAssignedTo = '';
    public $sortBy = 'due_date';
    public $sortDirection = 'asc';

    public $taskId;
    public $title;
    public $description;
    public $type = 'todo';
    public $priority = 'medium';
    public $due_date;
    public $assigned_to;

    protected $rules = [
        'title' => 'required|max:255',
        'description' => 'nullable',
        'type' => 'required|in:call,meeting,email,todo,deadline,follow_up',
        'priority' => 'required|in:low,medium,high,urgent',
        'due_date' => 'nullable|date',
        'assigned_to' => 'required|exists:users,id',
    ];

    public function mount()
    {
        $this->assigned_to = auth()->id();
    }

    public function createTask()
    {
        $this->validate();

        Task::create([
            'title' => $this->title,
            'description' => $this->description,
            'type' => $this->type,
            'priority' => $this->priority,
            'status' => 'pending',
            'due_date' => $this->due_date,
            'assigned_to' => $this->assigned_to,
            'created_by' => auth()->id(),
            'taskable_type' => null,
            'taskable_id' => null,
        ]);

        $this->reset(['title', 'description', 'type', 'priority', 'due_date']);

        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Task created successfully!'
        ]);

        $this->dispatchBrowserEvent('close-modal');
    }

    public function completeTask($taskId)
    {
        $task = Task::find($taskId);

        if ($task) {
            $task->status = 'completed';
            $task->completed_at = now();
            $task->progress = 100;
            $task->save();

            $this->dispatchBrowserEvent('notify', [
                'type' => 'success',
                'message' => 'Task marked as completed!'
            ]);
        }
    }

    public function deleteTask($taskId)
    {
        Task::find($taskId)?->delete();

        $this->dispatchBrowserEvent('notify', [
            'type' => 'success',
            'message' => 'Task deleted successfully!'
        ]);
    }

    public function render()
    {
        $query = Task::with(['assignedUser', 'creator', 'taskable'])
            ->when(!$this->showCompleted, function($q) {
                $q->where('status', '!=', 'completed');
            })
            ->when($this->filterPriority, function($q) {
                $q->where('priority', $this->filterPriority);
            })
            ->when($this->filterAssignedTo, function($q) {
                $q->where('assigned_to', $this->filterAssignedTo);
            })
            ->orderBy($this->sortBy, $this->sortDirection);

        return view('livewire.task-manager', [
            'tasks' => $query->paginate(15)
        ]);
    }
}

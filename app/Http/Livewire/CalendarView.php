<?php
namespace App\Http\Livewire;

use App\Models\Task;
use Livewire\Component;
use Carbon\Carbon;

class CalendarView extends Component
{
    public $currentMonth;
    public $currentYear;
    public $tasks;
    public $selectedDate;

    public function mount()
    {
        $this->currentMonth = now()->month;
        $this->currentYear = now()->year;
        $this->loadTasks();
    }

    public function loadTasks()
    {
        $startDate = Carbon::create($this->currentYear, $this->currentMonth, 1)->startOfMonth();
        $endDate = Carbon::create($this->currentYear, $this->currentMonth, 1)->endOfMonth();

        $this->tasks = Task::with('assignedUser')
            ->whereBetween('due_date', [$startDate, $endDate])
            ->get()
            ->groupBy(function($task) {
                return Carbon::parse($task->due_date)->format('Y-m-d');
            });
    }

    public function previousMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->subMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->loadTasks();
    }

    public function nextMonth()
    {
        $date = Carbon::create($this->currentYear, $this->currentMonth, 1)->addMonth();
        $this->currentMonth = $date->month;
        $this->currentYear = $date->year;
        $this->loadTasks();
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
        $this->dispatchBrowserEvent('date-selected', ['date' => $date]);
    }

    public function render()
    {
        return view('livewire.calendar-view');
    }
}
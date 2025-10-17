<?php
namespace App\Http\Livewire;

use App\Models\Lead;
use Livewire\Component;

class LeadKanban extends Component
{
    public $stages = [
        'new' => 'New',
        'contacted' => 'Contacted',
        'qualified' => 'Qualified',
        'converted' => 'Converted',
    ];

    public $leads;

    public function mount()
    {
        $this->loadLeads();
    }

    public function loadLeads()
    {
        $this->leads = Lead::with('assignedUser')
            ->whereIn('status', array_keys($this->stages))
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('status');
    }

    public function updateStage($leadId, $newStage)
    {
        $lead = Lead::find($leadId);

        if ($lead) {
            $lead->status = $newStage;
            $lead->save();

            $this->loadLeads();

            $this->dispatchBrowserEvent('notify', [
                'type' => 'success',
                'message' => 'Lead stage updated successfully!'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.lead-kanban');
    }
}
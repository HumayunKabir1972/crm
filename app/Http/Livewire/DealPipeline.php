<?php
namespace App\Http\Livewire;

use App\Models\Deal;
use Livewire\Component;

class DealPipeline extends Component
{
    public $stages = [
        'prospecting' => 'Prospecting',
        'qualification' => 'Qualification',
        'proposal' => 'Proposal',
        'negotiation' => 'Negotiation',
        'closed_won' => 'Closed Won',
    ];

    public $deals;
    public $selectedDeal = null;

    public function mount()
    {
        $this->loadDeals();
    }

    public function loadDeals()
    {
        $this->deals = Deal::with(['customer', 'assignedUser'])
            ->where('status', 'open')
            ->whereIn('stage', array_keys($this->stages))
            ->orderBy('expected_close_date', 'asc')
            ->get()
            ->groupBy('stage');
    }

    public function updateStage($dealId, $newStage)
    {
        $deal = Deal::find($dealId);

        if ($deal) {
            $deal->stage = $newStage;

            // Auto-update probability based on stage
            $probabilityMap = [
                'prospecting' => 10,
                'qualification' => 25,
                'proposal' => 50,
                'negotiation' => 75,
                'closed_won' => 100,
            ];

            $deal->probability = $probabilityMap[$newStage] ?? $deal->probability;

            if ($newStage === 'closed_won') {
                $deal->status = 'won';
                $deal->actual_close_date = now();
            }

            $deal->save();

            $this->loadDeals();

            $this->dispatchBrowserEvent('notify', [
                'type' => 'success',
                'message' => 'Deal moved to ' . $this->stages[$newStage]
            ]);
        }
    }

    public function viewDeal($dealId)
    {
        $this->selectedDeal = Deal::with(['customer', 'assignedUser'])->find($dealId);
        $this->dispatchBrowserEvent('open-modal', ['modal' => 'dealModal']);
    }

    public function render()
    {
        return view('livewire.deal-pipeline');
    }
}
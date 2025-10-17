<?php
namespace App\Http\Livewire;

use App\Models\Activity;
use Livewire\Component;

class ActivityFeed extends Component
{
    public $activities;

    public function render()
    {
        return view('livewire.activity-feed');
    }
}

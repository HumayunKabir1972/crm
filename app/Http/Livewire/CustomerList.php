<?php
namespace App\Http\Livewire;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class CustomerList extends Component
{
    use WithPagination;

    public $search = '';
    public $filterStatus = '';
    public $filterPriority = '';
    public $perPage = 10;

    protected $queryString = ['search', 'filterStatus', 'filterPriority'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $customers = Customer::with('assignedUser')
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                      ->orWhere('last_name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%')
                      ->orWhere('company_name', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filterStatus, function($query) {
                $query->where('status', $this->filterStatus);
            })
            ->when($this->filterPriority, function($query) {
                $query->where('priority', $this->filterPriority);
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.customer-list', [
            'customers' => $customers
        ]);
    }
}
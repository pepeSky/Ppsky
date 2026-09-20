<?php

namespace App\Livewire\Admin;

use App\Models\Activity;
use Livewire\Component;
use Livewire\WithPagination;

class ActivityIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search;

    public function updatingSearch(){
        $this->resetPage();
    }


    public function render()
    {
        $activities = Activity::all(); //where('user_id', auth()->user()->id)
                               // ->where('name','LIKE', '%' . $this->search . '%')
                               // ->latest('id')
                               // ->paginate();

        return view('livewire.admin.activity-index', compact('activities'));
    }
}

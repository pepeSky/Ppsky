<?php

namespace App\Livewire\Admin;

use App\Models\Entity;
use Livewire\Component;
use Livewire\WithPagination;

class EntityIndex extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search;

    public function updatingSearch(){
        $this->resetPage();
    }

    public function render()
    {
        $entities = Entity::all(); //where('user_id', auth()->user()->id)
                               // ->where('name','LIKE', '%' . $this->search . '%')
                               // ->latest('id')
                               // ->paginate();

        return view('livewire.admin.entity-index', compact('entities'));
    }
}

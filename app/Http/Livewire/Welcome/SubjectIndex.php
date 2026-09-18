<?php

namespace App\Http\Livewire\Welcome;

use App\Models\Level;
use App\Models\Modules\Subjects\Science;
use App\Models\Modules\Subjects\Subject;
use Livewire\Component;
use Livewire\WithPagination;

class SubjectIndex extends Component
{
    use WithPagination;

    public $science_id;
    public $level_id;

    public function render()
    {
        $sciences = Science::all();
        $levels = Level::all();
        $subjects = Subject::where('status', 3)
                            ->science( $this->science_id )
                            ->level( $this->level_id )
                            ->latest('id')
                            ->paginate(8);


        return view('livewire.welcome.subject-index', compact('sciences', 'levels', 'subjects'));
    }

    public function resetFilters(){
        $this->reset(['science_id', 'level_id']);
    }
}

<?php

namespace App\Livewire\Welcome;

use Livewire\Component;

class Welcome extends Component
{
    public $count = 0;

    public function increment()
    {
        $this->count++;
    }

    public function render()
    {
        return view('welcome');
    }
}

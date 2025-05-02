<?php

namespace App\Livewire;

use Livewire\Component;

class ProgressBarComponent extends Component
{
    public bool $status = false;


    public function render()
    {
        return view('livewire.progress-bar-component');
    }
}

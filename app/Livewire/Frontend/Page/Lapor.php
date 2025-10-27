<?php

namespace App\Livewire\Frontend\Page;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts-frontend.web')]
class Lapor extends Component
{
    public function render()
    {
        return view('livewire.frontend.page.lapor');
    }
}

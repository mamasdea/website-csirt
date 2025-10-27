<?php

namespace App\Livewire\Frontend\Page;

use App\Models\Page;
use App\Models\FilePanduan;
use App\Models\Setting;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts-frontend.web')]
class Detail extends Component
{
    public $page;
    public $setting;

    public function mount($slug)
    {
        $this->page = Page::where('slug', $slug)->firstOrFail();
        $this->setting = Setting::first();
    }

    public function render()
    {
        $filePanduans = [];
        if ($this->page->is_file) {
            $filePanduans = FilePanduan::all();
        }

        return view('livewire.frontend.page.detail', [
            'page' => $this->page,
            'filePanduans' => $filePanduans,
            'setting' => $this->setting,
        ]);
    }
}

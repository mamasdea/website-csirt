<?php

namespace App\Livewire\Frontend;

use App\Models\Article;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts-frontend.web')]
class Beranda extends Component
{
    public $postingan;

    public function mount()
    {
        // Ambil 4 artikel terbaru berdasarkan kolom created_at
        $this->postingan = Article::where('status', 'published')->latest()->take(4)->get();
    }

    public function render()
    {
        return view('livewire.frontend.beranda', [
            'postingan' => $this->postingan,
        ]);
    }
}

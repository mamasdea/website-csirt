<?php
// app/Livewire/Frontend/Article/GridBerita.php

namespace App\Livewire\Frontend\Article;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use App\Models\Article;

#[Title('Berita')]
#[Layout('components.layouts-frontend.web')]
class GridBerita extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 8;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $postingan = Article::with(['category:id,name,slug'])
            ->where('status', 'published')
            ->whereHas('category', fn($q) => $q->where('slug', 'berita')->orWhere('name', 'berita'))
            ->when($this->search, function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('content', 'like', "%{$this->search}%");
            })
            ->latest('updated_at')
            ->paginate($this->perPage);

        return view('livewire.frontend.article.grid-berita', compact('postingan'));
    }
}

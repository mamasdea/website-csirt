<?php
// app/Livewire/Frontend/Article/GridArticles.php

namespace App\Livewire\Frontend\Article;

use App\Models\Article;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Title('Artikel')]
#[Layout('components.layouts-frontend.web')]
class GridArticle extends Component
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
            ->whereHas('category', fn($q) => $q->where('slug', 'artikel')->orWhere('name', 'artikel'))
            ->when($this->search, function ($q) {
                $q->where('title', 'like', "%{$this->search}%")
                    ->orWhere('content', 'like', "%{$this->search}%");
            })
            ->latest('updated_at')
            ->paginate($this->perPage);

        return view('livewire.frontend.article.grid-article', compact('postingan'));
    }
}

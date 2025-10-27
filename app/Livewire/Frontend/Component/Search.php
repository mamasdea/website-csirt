<?php

namespace App\Livewire\Frontend\Component;

use Livewire\Component;
use App\Models\Article;

class Search extends Component
{
    public string $query = '';
    public $articles = [];

    public function updatedQuery(string $value)
    {
        if (empty($value)) {
            $this->articles = [];
            return;
        }

        $this->articles = Article::where('title', 'like', '%' . $this->query . '%')
            ->orWhere('content', 'like', '%' . $this->query . '%')
            ->get();
    }

    public function search()
    {
        $this->articles = Article::where('title', 'like', '%' . $this->query . '%')
            ->orWhere('content', 'like', '%' . $this->query . '%')
            ->get();
    }

    public function render()
    {
        return view('livewire.frontend.component.search');
    }
}

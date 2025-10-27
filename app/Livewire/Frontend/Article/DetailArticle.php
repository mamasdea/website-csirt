<?php

namespace App\Livewire\Frontend\Article;

use App\Models\Article;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Collection;

#[Layout('components.layouts-frontend.web')]
class DetailArticle extends Component
{
    public Article $article;

    public ?Article $prev = null;
    public ?Article $next = null;
    public Collection $related;

    public function mount(string $slug): void
    {
        $this->article = Article::with(['author:id,name', 'category:id,name,slug'])
        ->where('slug', $slug)
        ->where('status', 'published')
        ->firstOrFail();

        $this->dispatch('update-title', title: $this->article->title);
        $this->related = new Collection();

        // Increment views
        $this->incrementViews();

        // Prev/Next dalam kategori yang sama, fallback ke semua artikel
        $base = Article::query()
            ->where('status', 'published')
            ->when($this->article->category_id, fn($q) => $q->where('category_id', $this->article->category_id));

        $this->prev = (clone $base)->where('id', '<', $this->article->id)->orderBy('id', 'desc')->first()
            ?? Article::where('status', 'published')->where('id', '<', $this->article->id)->orderBy('id', 'desc')->first();

        $this->next = (clone $base)->where('id', '>', $this->article->id)->orderBy('id', 'asc')->first()
            ?? Article::where('status', 'published')->where('id', '>', $this->article->id)->orderBy('id', 'asc')->first();

        // Related 3 item (prioritas kategori sama)
        $this->related = Article::select('id', 'title', 'slug', 'image', 'updated_at')
            ->where('status', 'published')
            ->where('id', '!=', $this->article->id)
            ->when($this->article->category_id, fn($q) => $q->where('category_id', $this->article->category_id))
            ->latest('updated_at')
            ->take(3)
            ->get();

        if ($this->related->count() < 3) {
            $more = Article::select('id', 'title', 'slug', 'image', 'updated_at')
                ->where('status', 'published')
                ->whereNotIn('id', $this->related->pluck('id')->push($this->article->id))
                ->latest('updated_at')
                ->take(3 - $this->related->count())
                ->get();
            $this->related = $this->related->concat($more);
        }
    }

    private function incrementViews(): void
    {
        try {
            // Skip bot/crawler
            $ua = request()->userAgent() ?? '';
            if (preg_match('/bot|crawl|spider|slurp|facebookexternalhit|WhatsApp/i', $ua)) {
                return;
            }

            // Skip penulis sendiri
            if (Auth::check() && Auth::id() === $this->article->author_id) {
                return;
            }

            // Increment views di database (atomic) dan refresh model
            $this->article->increment('views');
            $this->article->refresh();
        } catch (\Exception $e) {
            // Log error tapi jangan break halaman
            Log::error('Failed to increment article views: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.frontend.article.detail-article');
    }
}

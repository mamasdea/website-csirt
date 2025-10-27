<div>
    <h3 class="text-lg font-bold text-accent mb-4 flex items-center transition duration-500">
        <i data-lucide="search" class="w-5 h-5 mr-2"></i> Cari Berita
    </h3>
    <form wire:submit.prevent="search" class="flex mb-4">
        <input type="search" wire:model.live="query" placeholder="Cari..."
            class="w-full p-2.5 rounded-l-lg bg-primary text-color border focus:outline-none focus:border-accent transition duration-500"
            style="border-color: color-mix(in oklab, var(--subtle-color) 70%, transparent);">
        <button type="submit"
            class="bg-accent text-primary p-2.5 rounded-r-lg font-bold hover:opacity-80 transition duration-200"
            aria-label="Cari" wire:loading.attr="disabled">
            <i data-lucide="search" class="w-4 h-4" wire:loading.remove></i>
            <span wire:loading>...</span>
        </button>
    </form>

    @if (!empty($query))
        <div class="mt-4">
            @if (count($articles) > 0)
                <h4 class="text-md font-bold text-accent mb-2">Hasil Pencarian</h4>
                <ul class="space-y-2">
                    @foreach ($articles as $article)
                        <li>
                            <a href="{{ route('articles.show', $article->slug) }}"
                                class="text-color hover:text-accent">{{ $article->title }}</a>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-color">
                    <p>Tidak ada hasil untuk "{{ $query }}".</p>
                </div>
            @endif
        </div>
    @endif
</div>

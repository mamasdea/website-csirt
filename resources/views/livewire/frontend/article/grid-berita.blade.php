{{-- resources/views/livewire/frontend/article/grid-berita.blade.php --}}
<div>
    {{-- Breadcrumb --}}
    <div class="bg-gray-400 bg-opacity-35 rounded-xl p-4 mb-2">
        <nav class="text-sm" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex items-center text-color">
                <li class="flex items-center">
                    <a href="{{ route('beranda') }}" class="hover:text-accent">Beranda</a>
                    <i data-lucide="chevron-right" class="w-4 h-4 mx-2 text-subtle"></i>
                </li>
                <li class="text-accent">Berita</li>
            </ol>
        </nav>
    </div>

    {{-- Title & Search --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold text-accent mb-2">Berita</h1>

        <div class="flex items-center gap-3">
            <input type="text" wire:model.live="search" placeholder="Cari berita..."
                class="rounded-lg border border-accent/20 bg-white px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-accent/40">
            <select wire:model.live="perPage"
                class="rounded-lg border border-accent/20 bg-white px-3 py-2 text-sm focus:outline-none">
                <option value="4">4</option>
                <option value="8">8</option>
                <option value="12">12</option>
            </select>
        </div>
    </div>
    <hr class="my-5 border-accent/1">
    {{-- Grid Berita --}}
    @if ($postingan->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
            @foreach ($postingan as $item)
                <a href="{{ route('articles.show', $item->slug) }}"
                    class="card-elev bg-secondary rounded-xl border p-4 transition duration-300 hover:shadow-lg block">
                    @if ($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}"
                            class="w-full h-40 object-cover rounded-lg mb-4 opacity-80 transition duration-300 group-hover:opacity-100">
                    @endif

                    <span
                        class="text-xs font-mono font-medium text-accent uppercase tracking-wider mb-2 block transition duration-500">
                        {{ $item->category->name ?? 'Umum' }}
                    </span>

                    <h3
                        class="text-lg font-bold text-color mb-2 leading-snug transition duration-500 hover:text-indigo-600">
                        {{ Str::limit($item->title, 80) }}
                    </h3>

                    <p class="text-color/80 text-sm mb-3 transition duration-500">
                        {{ Str::limit(strip_tags($item->content), 100) }}
                    </p>

                    <div
                        class="flex justify-between items-center text-xs text-subtle font-mono transition duration-500">
                        <span>
                            <i data-lucide="calendar" class="w-3 h-3 inline-block mr-1"></i>
                            {{ $item->created_at->translatedFormat('d F Y') }}
                        </span>
                        <span>
                            <i data-lucide="eye" class="w-3 h-3 inline-block mr-1"></i>
                            {{ number_format($item->views ?? 0) }}
                        </span>
                    </div>
                </a>
            @endforeach
        </div>

        <div class="mt-10">
            {{ $postingan->onEachSide(1)->links() }}
        </div>
    @else
        <div class="rounded-xl border border-accent/20 bg-white p-8 text-center text-subtle">
            Tidak ada berita ditemukan.
        </div>
    @endif
</div>

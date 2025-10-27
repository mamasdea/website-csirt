{{-- resources/views/livewire/frontend/article/detail-article.blade.php --}}
<div>
    {{-- Breadcrumb --}}


    <div class="bg-gray-400 bg-opacity-35 rounded-xl p-4 ">
        <nav class="text-sm " aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex items-center text-color transition duration-500">
                <li class="flex items-center">
                    <a href="{{ route('beranda') }}" class="hover:text-accent">Beranda</a>
                    <i data-lucide="chevron-right" class="w-4 h-4 mx-2 text-subtle"></i>
                </li>
                @if ($article->category)
                    <li class="flex items-center">
                        <a href="{{ url('/kategori/' . $article->category->slug) }}" class="hover:text-accent">
                            {{ $article->category->name }}
                        </a>
                        <i data-lucide="chevron-right" class="w-4 h-4 mx-2 text-subtle"></i>
                    </li>
                @endif
                <li class="text-accent" aria-current="page">{{ $article->title }}</li>
            </ol>
        </nav>

    </div>
    {{-- Title --}}
    <h1 class="text-2xl font-bold text-accent mt-2 mb-2">{{ $article->title }}</h1>

    {{-- meta ringkas --}}
    <div class="text-xs text-subtle mb-4 flex flex-wrap items-center gap-3">
        <span class="inline-flex items-center">
            <i data-lucide="user" class="w-3 h-3 mr-1"></i>{{ $article->author->name ?? 'Unknown' }}
        </span>
        <span class="inline-flex items-center">
            <i data-lucide="calendar" class="w-3 h-3 mr-1"></i>{{ $article->updated_at->translatedFormat('d F Y') }}
        </span>
        <span class="inline-flex items-center">
            <i data-lucide="eye" class="w-3 h-3 mr-1"></i>{{ number_format($article->views) }}
        </span>
    </div>

    <hr class="my-5 border-accent/1">

    {{-- Cover (opsional) --}}
    @if ($article->image)
        <div class="mb-6 rounded-xl overflow-hidden">
            <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}"
                class="w-full h-64 md:h-80 object-cover opacity-95">
        </div>
    @endif

    {{-- Content --}}
    <div class="prose max-w-none text-color transition duration-500 prose-img:rounded-xl prose-headings:text-accent">
        {!! $article->content !!}
    </div>

    {{-- Prev / Next --}}
    <div class="mt-6 grid grid-cols-2 gap-4 text-sm">
        {{-- Prev --}}
        <div class="justify-self-start">
            @if ($prev)
                <a href="{{ route('articles.show', $prev->slug) }}"
                    class="inline-flex items-center gap-2 text-accent hover:underline">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Previous Post
                </a>
            @else
                <span class="inline-flex items-center gap-2 text-subtle cursor-not-allowed opacity-60">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                    Previous Post
                </span>
            @endif
        </div>

        {{-- Next --}}
        <div class="justify-self-end">
            @if ($next)
                <a href="{{ route('articles.show', $next->slug) }}" wire:navigate
                    class="inline-flex items-center gap-2 text-accent hover:underline">
                    Next Post
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </a>
            @else
                <span class="inline-flex items-center gap-2 text-subtle cursor-not-allowed opacity-60">
                    Next Post
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </span>
            @endif
        </div>
    </div>


    {{-- Post Terkait --}}
    @if ($related->count())
        <div class="mt-4 card-elev bg-secondary p-6 rounded-xl border transition duration-500">
            <h2 class="text-2xl font-semibold text-accent mb-6">Post Terkait</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($related as $r)
                    <a href="{{ route('articles.show', $r->slug) }}"
                        class="group rounded-xl border border-accent/10 p-4 bg-secondary hover:shadow-md transition duration-300">
                        @if ($r->image)
                            <img src="{{ asset('storage/' . $r->image) }}" alt="{{ $r->title }}"
                                class="w-full h-40 object-cover rounded-lg mb-3 group-hover:opacity-100 opacity-90 transition" />
                        @endif
                        <h3 class="font-semibold text-color group-hover:text-accent transition line-clamp-2">
                            {{ $r->title }}
                        </h3>
                        <p class="mt-1 text-[12px] text-subtle">
                            {{ $r->updated_at->translatedFormat('d F Y') }}
                        </p>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>

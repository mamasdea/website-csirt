<div>
    <!-- BERITA -->
    <div class="lg:col-span-8">
        <h2 class="text-2xl font-bold text-accent mb-8 border-b-2 border-accent/50 pb-2 transition duration-500">
            BERITA TERBARU</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
            @foreach ($postingan as $item)
                <a href="{{ route('articles.show', $item->slug) }}"
                    class="card-elev bg-secondary rounded-xl border p-4 transition duration-300 hover:shadow-lg block">
                    <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->title }}"
                        class="w-full h-40 object-cover rounded-lg mb-4 opacity-80 transition duration-300 group-hover:opacity-100">

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


        <div class="text-center mt-10">
            <a href="{{ route('articles.grid') }}"
                class="inline-block  border-accent text-accent font-semibold pb-1 hover:text-color transition duration-300">
                --- Lihat Seluruh Arsip Berita ---
            </a>
        </div>
    </div>
</div>

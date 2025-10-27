@if (Route::is('beranda'))
    @include('components.layouts-frontend.part.hero')
@endif

<!-- MAIN -->
<main id="main-content" class="py-10 md:py-16">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-10">

        <!-- BERITA -->
        <div class="lg:col-span-8">
            {{ $slot }}
        </div>

        <!-- SIDEBAR -->
        <div class="lg:col-span-4 space-y-8">
            <!-- Cari + Lapor -->
            <div class="card-elev bg-secondary p-6 rounded-xl border transition duration-500">
                <livewire:frontend.component.search />
                <h3 class="text-lg font-bold text-accent mb-4 flex items-center mt-8 transition duration-500">
                    <i data-lucide="shield-alert" class="w-5 h-5 mr-2"></i> LAPOR INSIDEN
                </h3>
                <p class="text-sm text-color/80 mb-4 transition duration-500">Laporkan insiden keamanan siber segera
                    ke
                    tim kami.</p>
                <a href="{{ route('lapor-insiden') }}"
                    class="w-full block text-center bg-accent text-primary font-bold py-3 rounded-lg text-base hover:opacity-90">
                    LAPOR INSIDEN
                </a>
            </div>

            <!-- Video -->
            <div class="card-elev bg-secondary p-6 rounded-xl border transition duration-500">
                <h3 class="text-lg font-bold text-accent mb-4 flex items-center transition duration-500">
                    <i data-lucide="youtube" class="w-5 h-5 mr-2"></i> Video Youtube
                </h3>
                <div class="bg-primary p-4 rounded-lg text-center border transition duration-500"
                    style="border-color: color-mix(in oklab, var(--subtle-color) 70%, transparent);">
                    <i data-lucide="play-circle" class="w-10 h-10 text-accent mb-2 transition duration-500"></i>
                    <p class="text-sm text-color/70 transition duration-500">Video Tutorial Pengaduan (Placeholder)
                    </p>
                </div>
            </div>

            <!-- Polling -->
            <div class="card-elev bg-secondary p-6 rounded-xl border transition duration-500">
                <h3 class="text-lg font-bold text-accent mb-4 flex items-center transition duration-500">
                    <i data-lucide="bar-chart-3" class="w-5 h-5 mr-2"></i> Polling
                </h3>
                <livewire:frontend.component.poling />
            </div>

        </div> <!-- /SIDEBAR -->
    </div>
</main>

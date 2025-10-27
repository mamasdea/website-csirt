<!-- HERO (SLIDESHOW FULL SECTION) -->
<section id="hero-banner" class="relative text-white border-b-4 border-accent/20 overflow-hidden">

    <!-- Stage = seluruh section full-bleed -->
    <div id="hero-stage" class="relative w-full h-[60vh] sm:h-[68vh] md:h-[74vh] lg:h-[80vh] xl:h-[86vh]">

        <!-- Slides (full-bleed background) -->
        <div class="hero-slide absolute inset-0 opacity-0 pointer-events-none transition-opacity duration-700 ease-out">
            <img src="https://images.unsplash.com/photo-1518779578993-ec3579fee39f?q=80&w=2000&auto=format&fit=crop"
                alt="Keamanan Siber - Monitoring" class="w-full h-full object-cover">
            <div class="hero-overlay absolute inset-0"></div>

            <!-- Konten -->
            <div class="absolute inset-0 flex items-center">
                <div class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center gap-4 mb-4">
                        <img src="https://placehold.co/88x88/00FFFF/0A122A?text=CSIRT+WNS" alt="Logo CSIRT"
                            class="w-20 h-20 rounded-full p-1.5 border-4 border-accent/80 shadow-2xl shadow-accent/40">
                        <h1 class="text-3xl sm:text-4xl md:text-5xl font-extrabold leading-tight tracking-tight">
                            SEGOROARTO CYBER
                        </h1>
                    </div>
                    <p class="text-base sm:text-lg md:text-xl opacity-90 max-w-2xl">
                        Gaman Digital Soi Wonosobo Maju Ngayogyakarto
                    </p>
                    <a href="#lapor"
                        class="mt-6 inline-flex items-center gap-2 text-sm md:text-base font-semibold text-accent hover:underline">
                        <i data-lucide="shield-alert" class="w-4 h-4"></i> Ketahui Kerentanan Siber Anda
                    </a>
                </div>
            </div>
        </div>

        <div class="hero-slide absolute inset-0 opacity-0 pointer-events-none transition-opacity duration-700 ease-out">
            <img src="https://images.unsplash.com/photo-1510511459019-5dda7724fd87?q=80&w=2000&auto=format&fit=crop"
                alt="Incident Response" class="w-full h-full object-cover">
            <div class="hero-overlay absolute inset-0"></div>

            <div class="absolute inset-0 flex items-center">
                <div class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <span class="inline-block text-xs font-mono tracking-widest text-accent mb-2">RESPON CEPAT</span>
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold leading-tight mb-2">Computer Security
                        Incident Response</h2>
                    <p class="opacity-90 max-w-2xl">
                        Laporkan insiden keamanan 24/7. Tim kami siap triase, containment, eradication, dan recovery.
                    </p>
                    <a href="#lapor"
                        class="mt-6 inline-flex items-center gap-2 text-sm md:text-base font-semibold text-accent hover:underline">
                        <i data-lucide="send" class="w-4 h-4"></i> Laporkan Insiden Sekarang
                    </a>
                </div>
            </div>
        </div>

        <div class="hero-slide absolute inset-0 opacity-0 pointer-events-none transition-opacity duration-700 ease-out">
            <img src="https://images.unsplash.com/photo-1555949963-aa79dcee981d?q=80&w=2000&auto=format&fit=crop"
                alt="Edukasi Keamanan" class="w-full h-full object-cover">
            <div class="hero-overlay absolute inset-0"></div>

            <div class="absolute inset-0 flex items-center">
                <div class="w-full max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
                    <span class="inline-block text-xs font-mono tracking-widest text-accent mb-2">EDUKASI</span>
                    <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold leading-tight mb-2">Peningkatan Kapabilitas
                        Keamanan Siber</h2>
                    <p class="opacity-90 max-w-2xl">
                        Ikuti artikel, panduan, dan pelatihan untuk meningkatkan ketahanan siber organisasi Anda.
                    </p>
                    <a href="#artikel"
                        class="mt-6 inline-flex items-center gap-2 text-sm md:text-base font-semibold text-accent hover:underline">
                        <i data-lucide="book-open" class="w-4 h-4"></i> Baca Artikel
                    </a>
                </div>
            </div>
        </div>

        <!-- Controls: lebih bold & lebih ke dalam -->
        <button id="hero-prev" class="hero-nav absolute left-8 sm:left-10 md:left-12 top-1/2 -translate-y-1/2"
            aria-label="Sebelumnya">
            <i data-lucide="chevron-left" class="w-5 h-5"></i>
        </button>
        <button id="hero-next" class="hero-nav absolute right-8 sm:right-10 md:right-12 top-1/2 -translate-y-1/2"
            aria-label="Berikutnya">
            <i data-lucide="chevron-right" class="w-5 h-5"></i>
        </button>

        <!-- Indikator kotak -->
        <div class="absolute bottom-6 left-0 right-0 flex justify-center">
            <div id="hero-boxes" class="hero-boxes"></div>
        </div>
    </div>
</section>

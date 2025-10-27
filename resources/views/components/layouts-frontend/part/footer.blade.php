<!-- TAUTAN TERKAIT -->
{{-- <section class="py-10 bg-secondary border-t border-accent/30 transition duration-500">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-xl font-bold text-accent text-center mb-6 transition duration-500">TAUTAN TERKAIT</h2>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
            <a href="#" class="card-elev p-4 bg-primary rounded-xl text-center border transition duration-300">
                <i data-lucide="graduation-cap" class="w-8 h-8 text-accent mx-auto mb-2"></i>
                <p class="text-sm font-semibold text-color">KANAL EDUKASI</p>
            </a>
            <a href="#" class="card-elev p-4 bg-primary rounded-xl text-center border transition duration-300">
                <i data-lucide="building-2" class="w-8 h-8 text-accent mx-auto mb-2"></i>
                <p class="text-sm font-semibold text-color">PORTAL PEMDA 30GJA</p>
            </a>
            <a href="#" class="card-elev p-4 bg-primary rounded-xl text-center border transition duration-300">
                <i data-lucide="cloud-cog" class="w-8 h-8 text-accent mx-auto mb-2"></i>
                <p class="text-sm font-semibold text-color">JSS</p>
            </a>
            <a href="#" class="card-elev p-4 bg-primary rounded-xl text-center border transition duration-300">
                <i data-lucide="map-pin" class="w-8 h-8 text-accent mx-auto mb-2"></i>
                <p class="text-sm font-semibold text-color">JOGJA SMART CITY</p>
            </a>
            <a href="#lapor" class="card-elev p-4 bg-primary rounded-xl text-center border transition duration-300">
                <i data-lucide="fingerprint" class="w-8 h-8 text-accent mx-auto mb-2"></i>
                <p class="text-sm font-semibold text-color">LAPOR INSIDEN</p>
            </a>
        </div>
    </div>
</section> --}}

<!-- FOOTER -->
<footer class="bg-primary py-8 border-t-4 border-accent/20 transition duration-500">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-subtle transition duration-500">
        <div class="text-sm leading-relaxed mb-6 border-b pb-4 transition duration-500"
            style="border-color: color-mix(in oklab, var(--subtle-color) 70%, transparent);">
            <p>
                {{ ($setting->name ?? 'CSIRT Wonosobo') .
                    ' - ' .
                    ($setting->description ?? 'Computer Security Incident Response Team') }}
            </p>
            <p>Telepon: {{ $setting->no_telp ?? '(0286) XXX-XXXX (24 Jam)' }} | Email:
                {{ $setting->email ?? 'aduan@csirt-wonosobo.go.id' }}</p>
            <p>Alamat: {{ $setting->address ?? 'Jl. Jend. Sudirman No.1, Wonosobo Timur, Wonosobo, Jawa Tengah' }}</p>
        </div>
        <p class="font-mono text-xs">&copy; {{ date('Y') }} Pemerintah Kabupaten Wonosobo. Hak Cipta Dilindungi.</p>
    </div>
</footer>

<!-- TOMBOL UTILITAS -->
<div id="utility-buttons">
    <!-- Theme toggle -->
    <button id="theme-toggle" class="utility-btn" aria-label="Toggle theme">
        <i data-lucide="moon" id="theme-icon" class="w-6 h-6"></i>
    </button>

    <!-- Scroll To Top + Progres -->
    <div id="scroll-to-top-wrap" class="hidden" aria-hidden="true">
        <!-- SVG Progress Ring -->
        <svg id="progress-ring" width="56" height="56" viewBox="0 0 56 56">
            <!-- Lingkaran track -->
            <circle class="track" cx="28" cy="28" r="24" fill="none" stroke-width="4"></circle>
            <!-- Lingkaran progres -->
            <circle id="progress-circle" class="progress" cx="28" cy="28" r="24" fill="none"
                stroke-width="4" stroke-linecap="round" stroke-dasharray="150.796447" stroke-dashoffset="150.796447">
            </circle>
        </svg>

        <!-- Tombol -->
        <button id="scroll-to-top" class="utility-btn" style="position:absolute; inset:6px;"
            onclick="window.scrollTo({top:0, behavior:'smooth'})" aria-label="Scroll to top">
            <i data-lucide="arrow-up" class="w-6 h-6"></i>
        </button>
    </div>
</div>

<!-- SCRIPTS -->
<script>
    // 1) Inisialisasi ikon Lucide
    lucide.createIcons();

    // 2) Toggle Menu Mobile
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    if (mobileMenuButton) {
        mobileMenuButton.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));
    }
    document.querySelectorAll('#mobile-menu a').forEach(a =>
        a.addEventListener('click', () => mobileMenu.classList.add('hidden'))
    );

    // 3) Theme Switch (pakai class .light dan localStorage)
    const htmlEl = document.documentElement;
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');

    function applyTheme(isLight) {
        if (isLight) {
            htmlEl.classList.add('light');
            themeIcon.setAttribute('data-lucide', 'sun');
            localStorage.setItem('theme', 'light');
        } else {
            htmlEl.classList.remove('light');
            themeIcon.setAttribute('data-lucide', 'moon');
            localStorage.setItem('theme', 'dark');
        }
        lucide.createIcons(); // re-render ikon
    }

    let initialTheme = localStorage.getItem('theme');
    if (!initialTheme) {
        initialTheme = window.matchMedia('(prefers-color-scheme: light)').matches ? 'light' : 'dark';
    }
    applyTheme(initialTheme === 'light');

    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            const isLight = htmlEl.classList.contains('light');
            applyTheme(!isLight);
        });
    }

    // 4) Scroll-To-Top + Progress Ring
    const scrollWrap = document.getElementById('scroll-to-top-wrap');
    const progressCircle = document.getElementById('progress-circle');
    const circumference = 2 * Math.PI * 24; // r=24 → keliling
    if (progressCircle) {
        progressCircle.style.strokeDasharray = `${circumference}`;
        progressCircle.style.strokeDashoffset = `${circumference}`;
    }

    function updateScrollUI() {
        const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
        const docHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
        const progress = docHeight > 0 ? (scrollTop / docHeight) : 0;
        const offset = circumference * (1 - progress);
        if (progressCircle) progressCircle.style.strokeDashoffset = `${offset}`;

        // tampilkan tombol setelah 300px
        if (scrollTop > 300) {
            scrollWrap.classList.remove('hidden');
            scrollWrap.setAttribute('aria-hidden', 'false');
        } else {
            scrollWrap.classList.add('hidden');
            scrollWrap.setAttribute('aria-hidden', 'true');
        }
    }

    window.addEventListener('scroll', updateScrollUI, {
        passive: true
    });
    window.addEventListener('load', updateScrollUI);
</script>

<script>
    // === HERO SLIDESHOW: indikator kotak ===
    (function() {
        const slides = Array.from(document.querySelectorAll('#hero-stage .hero-slide'));
        if (!slides.length) return;

        let index = 0;
        let timer = null;
        const INTERVAL = 6000;

        const prevBtn = document.getElementById('hero-prev');
        const nextBtn = document.getElementById('hero-next');

        // Buat indikator kotak
        const boxesWrap = document.getElementById('hero-boxes');
        const boxes = slides.map((_, i) => {
            const b = document.createElement('button');
            b.className = 'hero-box';
            b.setAttribute('aria-label', 'Slide ' + (i + 1));
            b.addEventListener('click', () => goTo(i, true));
            boxesWrap.appendChild(b);
            return b;
        });

        function render() {
            slides.forEach((s, i) => {
                const active = i === index;
                s.style.opacity = active ? '1' : '0';
                s.style.pointerEvents = active ? 'auto' : 'none';
            });
            boxes.forEach((b, i) => b.classList.toggle('active', i === index));
        }

        function next(manual = false) {
            index = (index + 1) % slides.length;
            render();
            if (manual) restart();
        }

        function prev(manual = false) {
            index = (index - 1 + slides.length) % slides.length;
            render();
            if (manual) restart();
        }

        function goTo(i, manual = false) {
            index = (i + slides.length) % slides.length;
            render();
            if (manual) restart();
        }

        function start() {
            stop();
            timer = setInterval(next, INTERVAL);
        }

        function stop() {
            if (timer) {
                clearInterval(timer);
                timer = null;
            }
        }

        function restart() {
            start();
        }

        // Events
        prevBtn.addEventListener('click', () => prev(true));
        nextBtn.addEventListener('click', () => next(true));

        const stage = document.getElementById('hero-stage');
        stage.addEventListener('mouseenter', stop);
        stage.addEventListener('mouseleave', start);

        // Swipe
        let sx = 0,
            ex = 0;
        stage.addEventListener('touchstart', e => {
            sx = e.changedTouches[0].clientX;
        }, {
            passive: true
        });
        stage.addEventListener('touchend', e => {
            ex = e.changedTouches[0].clientX;
            const dx = ex - sx;
            if (Math.abs(dx) > 40)(dx < 0 ? next(true) : prev(true));
        }, {
            passive: true
        });

        // Init
        render();
        start();
    })();

    // render ulang ikon lucide (untuk chevron di tombol)
    lucide.createIcons();
</script>
@livewireScripts
@stack('js')
</body>

</html>

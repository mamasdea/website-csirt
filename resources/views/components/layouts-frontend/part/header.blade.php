<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{ $setting->name ?? 'CSIRT Wonosobo - Computer Security Incident Response Team' }}</title>
    <link rel="icon" type="image/png" href="{{ $logoUrl }}">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700;900&display=swap"
        rel="stylesheet" />

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>


    <style>
        /* === WARNA (pakai hex, bukan theme()) === */
        :root {
            /* DARK (default) */
            --bg-primary: #0A122A;
            --bg-secondary: #141E3A;
            --text-color: #E0E0E0;
            --accent-color: #00FFFF;
            --subtle-color: #708090;
        }

        /* LIGHT MODE */
        .light {
            --bg-primary: #FFFFFF;
            --bg-secondary: #F3F4F6;
            --text-color: #1F2937;
            --accent-color: #008C8C;
            --subtle-color: #9CA3AF;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-color);
            transition: background-color .5s, color .5s;
        }

        .bg-primary {
            background-color: var(--bg-primary) !important;
        }

        .bg-secondary {
            background-color: var(--bg-secondary) !important;
        }

        .text-color {
            color: var(--text-color);
        }

        .text-accent {
            color: var(--accent-color);
        }

        .border-accent {
            border-color: var(--accent-color);
        }

        .bg-accent {
            background-color: var(--accent-color);
        }

        .text-subtle {
            color: var(--subtle-color);
        }

        /* Neon pulse untuk logo (matikan di light) */
        @keyframes neon-pulse {

            0%,
            100% {
                text-shadow: 0 0 5px rgba(0, 255, 255, .5), 0 0 10px rgba(0, 255, 255, .2);
            }

            50% {
                text-shadow: 0 0 10px rgba(0, 255, 255, .8), 0 0 20px rgba(0, 255, 255, .4);
            }
        }

        .logo-pulse {
            animation: neon-pulse 4s infinite alternate ease-in-out;
        }

        .light .logo-pulse {
            animation: none;
            text-shadow: none;
        }

        /* Utility buttons */
        #utility-buttons {
            position: fixed;
            right: 2rem;
            bottom: 2rem;
            z-index: 100;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .utility-btn {
            padding: .75rem;
            border-radius: 9999px;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, .1), 0 4px 6px -4px rgba(0, 0, 0, .1);
            transition: transform .2s ease, opacity .2s ease;
            background-color: var(--accent-color);
            color: var(--bg-primary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .utility-btn:hover {
            transform: scale(1.1);
        }

        /* Dropdown hover */
        .dropdown-hover:hover .dropdown-menu {
            display: block;
        }

        .dropdown-hover:hover .icon-rotate {
            transform: rotate(180deg);
        }

        /* Polling bar */
        .polling-bar {
            height: 8px;
            border-radius: 9999px;
            background-color: var(--accent-color);
        }

        /* Scroll To Top dengan cincin progres (SVG) */
        #scroll-to-top-wrap {
            position: relative;
            width: 56px;
            height: 56px;
        }

        #progress-ring {
            position: absolute;
            inset: 0;
            pointer-events: none;
            transform: rotate(-90deg);
        }

        #progress-ring circle.track {
            stroke: rgba(255, 255, 255, .2);
        }

        #progress-ring circle.progress {
            stroke: var(--accent-color);
            transition: stroke-dashoffset .1s linear;
        }

        /* === Elevation khusus card agar hover terlihat di dark mode === */
        .card-elev {
            background-color: var(--bg-secondary);
            border: 1px solid color-mix(in oklab, var(--subtle-color) 70%, transparent);
            box-shadow: 0 1px 2px rgba(0, 0, 0, .35);
            transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease, background-color .2s ease;
            will-change: transform, box-shadow, border-color;
        }

        .card-elev:hover {
            border-color: var(--accent-color) !important;
            box-shadow:
                0 0 0 1px var(--accent-color),
                0 14px 28px rgba(0, 0, 0, .55);
            transform: translateY(-2px);
        }

        .light .card-elev:hover {
            box-shadow:
                0 0 0 1px var(--accent-color),
                0 14px 28px rgba(0, 0, 0, .18);
        }

        /* Overlay gradasi agar teks selalu kontras di atas gambar full-bleed */
        .hero-overlay {
            background-image: linear-gradient(90deg,
                    rgba(10, 18, 42, .85) 0%,
                    rgba(10, 18, 42, .58) 35%,
                    rgba(10, 18, 42, .28) 60%,
                    rgba(10, 18, 42, 0) 100%);
        }

        .light .hero-overlay {
            background-image: linear-gradient(90deg,
                    rgba(0, 0, 0, .45) 0%,
                    rgba(0, 0, 0, .25) 35%,
                    rgba(0, 0, 0, .10) 60%,
                    rgba(0, 0, 0, 0) 100%);
        }

        /* === Tombol navigasi bulat (lebih bold + lebih ke dalam) === */
        .hero-nav {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background: rgba(255, 255, 255, .14);
            border: 2px solid rgba(255, 255, 255, .50);
            /* bold */
            backdrop-filter: blur(8px);
            border-radius: 9999px;
            transition: background .2s ease, transform .2s ease, border-color .2s ease, opacity .2s ease;
        }

        .hero-nav:hover {
            background: rgba(255, 255, 255, .22);
            transform: scale(1.06);
        }

        .hero-nav:active {
            transform: scale(0.98);
        }

        .hero-nav svg {
            stroke-width: 2.8;
        }

        /* ikon lucide lebih tebal */
        .light .hero-nav {
            color: #1F2937;
            background: rgba(255, 255, 255, .8);
            border-color: rgba(0, 0, 0, .25);
        }

        .light .hero-nav:hover {
            background: rgba(255, 255, 255, .95);
        }

        /* === Indikator kotak === */
        .hero-boxes {
            display: flex;
            gap: .5rem;
            align-items: center;
            justify-content: center;
        }

        .hero-box {
            width: 34px;
            height: 8px;
            border-radius: 4px;
            background: rgba(255, 255, 255, .3);
            border: 1.6px solid rgba(255, 255, 255, .55);
            transition: background-color .25s ease, border-color .25s ease, transform .25s ease;
        }

        .hero-box.active {
            background: var(--accent-color);
            border-color: var(--accent-color);
            box-shadow: 0 0 8px color-mix(in oklab, var(--accent-color) 70%, transparent);
            transform: translateY(-1px);
        }

        .light .hero-box {
            background: rgba(0, 0, 0, .12);
            border-color: rgba(0, 0, 0, .25);
        }

        .light .hero-box.active {
            background: var(--accent-color);
            border-color: var(--accent-color);
            box-shadow: 0 0 8px color-mix(in oklab, var(--accent-color) 60%, white);
        }

        /* Responsif kecil: konten agak turun agar tidak mepet */
        @media (max-width: 640px) {
            #hero-stage .absolute.inset-0.flex.items-center {
                align-items: flex-end;
                padding-bottom: 2.5rem;
            }
        }
    </style>

    @livewireStyles
    @stack('css')

</head>

<body class="bg-primary text-color font-sans" @window-update-title.document="document.title = $event.detail.title">

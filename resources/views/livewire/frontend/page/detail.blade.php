<div>
    <!-- Breadcrumb -->
    <div class="bg-gray-400 bg-opacity-35 rounded-xl p-4">
        <nav class="text-sm" aria-label="Breadcrumb">
            <ol class="list-none p-0 inline-flex items-center text-color transition duration-500">
                <li class="flex items-center">
                    <a href="{{ route('beranda') }}" class="hover:text-accent">Beranda</a>
                    <i data-lucide="chevron-right" class="w-4 h-4 mx-2 text-subtle"></i>
                </li>
                <li class="text-accent" aria-current="page">
                    {{ $page->title }}
                </li>
            </ol>
        </nav>
    </div>

    <!-- Title -->
    <h1 class="text-2xl font-bold text-accent mt-2 mb-2">{{ $page->title }}</h1>

    <!-- Separator -->
    <hr class="my-5 border-accent/1">


    @if (!empty($page->image) && Storage::disk('gcs')->exists($page->image))
        <div class="mb-6 rounded-t-xl overflow-hidden">
            <img src="{{ Storage::disk('gcs')->url($page->image) }}" alt="{{ $page->title }}"
                class="w-full max-h-64 md:max-h-96 object-cover opacity-95">
        </div>
    @else
        {{-- Jika gambar tidak ada atau tidak bisa dimuat, jangan tampilkan apapun --}}
    @endif

    <!-- PDF File Viewer -->
    @if (!empty($page->file) && file_exists(public_path('storage/' . $page->file)))
        <div class="my-8">
            <div class="border-2 border-accent/20 rounded-xl overflow-hidden">
                {{-- <div class="bg-secondary p-4">
                    <h3 class="text-lg font-semibold text-accent">Dokumen Terlampir</h3>
                </div> --}}
                <div class="p-4" style="height: 80vh;">
                    <iframe src="{{ asset('storage/' . $page->file) }}" width="100%" height="100%">
                        <p>Browser Anda tidak mendukung pratinjau PDF. Anda bisa <a
                                href="{{ asset('storage/' . $page->file) }}"
                                class="text-accent hover:underline">mengunduh
                                file di sini</a>.</p>
                    </iframe>
                </div>
            </div>
        </div>
    @endif

    <!-- Page Content -->
    <div class="prose max-w-none text-color transition duration-500">
        {!! $page->content !!}
    </div>

    @if ($page->page_type == 'guide' && count($filePanduans) > 0)
        <div class="mt-8">
            {{-- <h2 class="text-xl font-bold text-accent mb-4">Daftar File Panduan</h2> --}}
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border border-gray-200 rounded-lg shadow-sm">
                    <thead>
                        <tr class="bg-gray-100 border-b">
                            <th
                                class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                No</th>
                            <th
                                class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Nama File</th>
                            <th
                                class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ukuran File</th>
                            <th
                                class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($filePanduans as $index => $filePanduan)
                            <tr>
                                <td class="px-4 py-2 text-center whitespace-nowrap text-sm text-gray-900">
                                    {{ $index + 1 }}</td>
                                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">{{ $filePanduan->title }}
                                </td>
                                <td class="px-4 py-2 text-center whitespace-nowrap text-sm text-gray-900">
                                    @if ($filePanduan->file && Storage::disk('public')->exists($filePanduan->file))
                                        {{ number_format(Storage::disk('public')->size($filePanduan->file) / 1024, 2) }}
                                        KB
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-center whitespace-nowrap text-sm font-medium">
                                    @if ($filePanduan->file && Storage::disk('public')->exists($filePanduan->file))
                                        <a href="{{ asset('storage/' . $filePanduan->file) }}" target="_blank"
                                            class="text-indigo-600 hover:text-indigo-900">Preview</a>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if ($page->page_type == 'contact')
        {{-- MEMPERBAIKI TYPO: 'contanct' menjadi 'contact' --}}
        <style>
            /* Custom styles if needed */
            .icon-circle {
                width: 80px;
                /* Lebar dan tinggi lingkaran */
                height: 80px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 50%;
                /* Membuat lingkaran */
                background-color: #09a780;
                /* Warna biru, sesuaikan jika perlu */
                color: white;
                /* Warna ikon */
                font-size: 2.5rem;
                /* Ukuran ikon */
                flex-shrink: 0;
                /* Mencegah ikon menyusut */
            }
        </style>
        <div class="max-w-6xl mx-auto bg-white shadow-lg rounded-lg p-4 lg:p-10">

            {{-- Bagian Informasi Kontak --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-y-8 gap-x-12 mb-10">

                {{-- Phone --}}
                <div class="flex items-center space-x-4">
                    <div class="icon-circle bg-[#1e88e5] text-white">
                        <i class="fas fa-phone"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-gray-700">Phone:</h3>
                        {{-- MENGGUNAKAN DATA DINAMIS DARI MODEL SETTING --}}
                        <p class="text-gray-600">{{ $setting->no_telp ?? 'N/A' }}</p>
                    </div>
                </div>

                {{-- Email --}}
                <div class="flex items-center space-x-4">
                    <div class="icon-circle bg-[#1e88e5] text-white">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-gray-700">Email:</h3>
                        {{-- MENGGUNAKAN DATA DINAMIS DARI MODEL SETTING --}}
                        <p class="text-gray-600">{{ $setting->email ?? 'N/A' }}</p>
                    </div>
                </div>

                {{-- Link Aduan (Mengganti Discord dengan link aduan) --}}
                <div class="flex items-center space-x-4">
                    <div class="icon-circle bg-[#1e88e5] text-white">
                        {{-- Menggunakan ikon yang sesuai untuk tautan/link --}}
                        <i class="fas fa-external-link-alt"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-gray-700">Link Aduan:</h3>
                        {{-- MENGGUNAKAN DATA DINAMIS DARI MODEL SETTING --}}
                        <p class="text-gray-600">
                            <a href="{{ $setting->link_aduan ?? '#' }}" class="text-[#1e88e5] hover:underline"
                                target="_blank">
                                {{ $setting->link_aduan ?? 'https://default-aduan.com' }}
                            </a>
                        </p>
                    </div>
                </div>

                {{-- Address --}}
                <div class="flex items-start space-x-4">
                    <div class="icon-circle bg-[#1e88e5] text-white">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-lg text-gray-700">Address:</h3>
                        {{-- MENGGUNAKAN DATA DINAMIS DARI MODEL SETTING --}}
                        <p class="text-gray-600">
                            {!! nl2br(e($setting->address ?? 'Alamat Belum Disetel')) !!}
                        </p>
                    </div>
                </div>

            </div>

            {{-- Bagian Peta Lokasi --}}
            <div class="mt-12">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Location Map</h2>
                <div class="aspect-w-16 aspect-h-9 w-full overflow-hidden rounded-lg shadow-md">
                    {{-- MENGGUNAKAN DATA DINAMIS DARI MODEL SETTING --}}
                    {!! $setting->maps_embed ?? '<p class="p-4 text-center text-red-500">Kode Embed Maps Belum Disetel!</p>' !!}
                </div>
            </div>
        </div>
    @endif
</div>

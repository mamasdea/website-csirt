<!-- HEADER/NAVIGASI -->
<header class="sticky top-0 z-50 bg-primary shadow-xl border-b border-accent/50 transition duration-500">
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            <!-- Logo -->
            <div class="flex-shrink-0">
                <a href="#"
                    class="flex items-center gap-x-2 text-xl font-bold text-color tracking-widest font-mono logo-pulse transition duration-500">
                    <img src="{{ $logoUrl }}" alt="Logo" class="h-10 w-auto">
                    {{ $setting->name ?? 'WONOSOBOKAB' }}
                    {{-- <span class="text-accent transition duration-500">-  {{ $setting->name ?? 'CSIRT' }}</span> --}}
                </a>
            </div>

            <!-- Menu Desktop -->
            <div class="hidden md:flex md:space-x-8 text-sm">
                @foreach ($menus as $menu)
                    @if ($menu->is_parent && !empty($menu->children))
                        <!-- Dropdown -->
                        <div class="relative dropdown-hover">
                            <button
                                class="flex items-center text-color hover:text-accent border-b-2 border-transparent hover:border-accent px-1 py-2 font-medium transition duration-200">
                                {{ $menu->name }}
                                <i data-lucide="chevron-down"
                                    class="w-4 h-4 ml-1 icon-rotate transition-transform duration-300"></i>
                            </button>
                            <div
                                class="dropdown-menu hidden absolute z-10 w-52 rounded-lg shadow-2xl bg-secondary ring-2 ring-accent/50 origin-top-right transition duration-500">
                                @foreach ($menu->children as $child)
                                    @php
                                        $url = '#';
                                        if ($child->type == 1 && Route::has($child->link)) {
                                            $url = route($child->link);
                                        } elseif ($child->type == 2) {
                                            $url = url('detail-pages/' . $child->link);
                                        } else {
                                            $url = $child->link;
                                        }
                                    @endphp
                                    <a href="{{ $url }}"
                                        class="block px-4 py-2 text-sm text-color hover:bg-primary transition duration-150 ease-in-out rounded-t-lg">{{ $child->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        @php
                            $url = '#';
                            if ($menu->type == 1 && Route::has($menu->link)) {
                                $url = route($menu->link);
                            } elseif ($menu->type == 2) {
                                $url = url('detail-pages/' . $menu->link);
                            } else {
                                $url = $menu->link;
                            }
                        @endphp
                        <a href="{{ $url }}"
                            class="text-color hover:text-accent border-b-2 border-transparent hover:border-accent px-1 py-2 font-medium transition duration-200">{{ $menu->name }}</a>
                    @endif
                @endforeach
            </div>

            <!-- Menu Mobile -->
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-color hover:text-accent focus:outline-none"
                    aria-label="Buka menu">
                    <i data-lucide="menu" class="w-6 h-6"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Drawer Mobile -->
    <div id="mobile-menu" class="md:hidden hidden bg-secondary border-t border-accent/50 transition duration-500">
        <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
            @foreach ($menus as $menu)
                @if ($menu->is_parent && !empty($menu->children))
                    <div class="border-t mt-2 pt-2" style="border-color: var(--bg-primary);">
                        <p class="px-3 py-2 text-sm font-semibold text-accent">{{ $menu->name }}</p>
                        @foreach ($menu->children as $child)
                            @php
                                $url = '#';
                                if ($child->type == 1 && Route::has($child->link)) {
                                    $url = route($child->link);
                                } elseif ($child->type == 2) {
                                    $url = url('detail-pages/' . $child->link);
                                } else {
                                    $url = $child->link;
                                }
                            @endphp
                            <a href="{{ $url }}"
                                class="block pl-6 pr-3 py-2 rounded-md text-sm font-medium text-color hover:bg-primary">{{ $child->name }}</a>
                        @endforeach
                    </div>
                @else
                    @php
                        $url = '#';
                        if ($menu->type == 1 && Route::has($menu->link)) {
                            $url = route($menu->link);
                        } elseif ($menu->type == 2) {
                            $url = url('detail-pages/' . $menu->link);
                        } else {
                            $url = $menu->link;
                        }
                    @endphp
                    <a href="{{ $url }}"
                        class="block px-3 py-2 rounded-md text-base font-medium text-color hover:bg-primary">{{ $menu->name }}</a>
                @endif
            @endforeach
        </div>
    </div>
</header>

<nav class="navbar navbar-expand-lg main_menu" id="main_menu_area">

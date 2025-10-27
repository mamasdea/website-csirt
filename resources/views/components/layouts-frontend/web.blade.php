@php
    use App\Models\Setting;
    use App\Models\Menu;

    $setting = Setting::first();
    $logoUrl = $setting && $setting->logo ? asset('storage/' . $setting->logo) : asset('assets/logo/csirt-logo.png');
    $menus = Menu::whereNull('parent_id')->with('children')->orderBy('order')->get();
@endphp
@include('components.layouts-frontend.part.header', ['logoUrl' => $logoUrl, 'setting' => $setting])
@include('components.layouts-frontend.part.navbar', [
    'menus' => $menus,
    'setting' => $setting,
    'logoUrl' => $logoUrl,
])
@include('components.layouts-frontend.part.main')
@include('components.layouts-frontend.part.footer')

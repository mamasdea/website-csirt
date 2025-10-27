@php
    use App\Models\Setting;

    $setting = Setting::first();
    $logoUrl = $setting && $setting->logo ? asset('storage/' . $setting->logo) : asset('assets/logo/csirt-logo.png');
    $appName = $setting && $setting->name ? $setting->name : 'CSIRT';
@endphp

@include('components.layouts-backend.part-layouts.header', ['logoUrl' => $logoUrl, 'setting' => $setting])
@include('components.layouts-backend.part-layouts.sidebar', [
    'logoUrl' => $logoUrl,
    'setting' => $setting,
    'appName' => $appName,
])
@include('components.layouts-backend.part-layouts.navbar')

@include('components.layouts-backend.part-layouts.content')

@include('components.layouts-backend.part-layouts.controllsidebar')
@include('components.layouts-backend.part-layouts.footer')

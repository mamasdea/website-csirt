@php
    use App\Models\Setting;

    $setting = Setting::first();
    $logoUrl = $setting && $setting->logo ? asset('storage/' . $setting->logo) : asset('assets/logo/csirt-logo.png');
@endphp

@include('components.layouts-backend.part-layouts.header', ['logoUrl' => $logoUrl, 'setting' => $setting])


<div class="content mt-3">
    <div class="container-fluid">
        {{ $slot }}
        {{-- @yield('content') --}}
    </div>
    <!-- /.container-fluid -->
</div>



<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="{{ asset('AdminLTE-3.2.0/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<!-- AdminLTE -->
<script src="{{ asset('AdminLTE-3.2.0/dist/js/adminlte.min.js') }}"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="{{ asset('AdminLTE-3.2.0/plugins/chart.js/Chart.min.js') }}" defer></script>

<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ asset('AdminLTE-3.2.0/dist/js/pages/dashboard3.js') }}" data-navigate></script>
{{-- @stack('js') --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Select2 -->
<script src="{{ asset('AdminLTE-3.2.0/plugins/select2/js/select2.full.min.js') }}"></script>


@livewireScripts
@stack('js')
</body>

</html>

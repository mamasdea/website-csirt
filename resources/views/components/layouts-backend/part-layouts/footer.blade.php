     <!-- Main Footer -->
     <footer class="main-footer">
         <strong>Copyright &copy; 2025 <a href="https://github.com/mamasdea">MAMAS DEA</a>.</strong>
         All rights reserved.
         <div class="float-right d-none d-sm-inline-block">
             <b>Siberuang V</b> 1.0
         </div>
     </footer>
     </div>
     <!-- ./wrapper -->

     <!-- REQUIRED SCRIPTS -->

     <!-- jQuery -->
     <script src="{{ asset('template/plugins/jquery/jquery.min.js') }}"></script>
     <!-- Bootstrap -->
     <script src="{{ asset('template/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
     <!-- AdminLTE -->
     <script src="{{ asset('template/dist/js/adminlte.min.js') }}"></script>

     <!-- OPTIONAL SCRIPTS -->
     <script src="{{ asset('template/plugins/chart.js/Chart.min.js') }}" defer></script>

     <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
     <script src="{{ asset('template/dist/js/pages/dashboard3.js') }}" data-navigate></script>
     {{-- @stack('js') --}}
     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
     <!-- Select2 -->
     <script src="{{ asset('template/plugins/select2/js/select2.full.min.js') }}"></script>


     <script src="{{ asset('assets/ckeditor/ckeditor.js') }}"></script>
     @livewireScripts
     @stack('js')
     </body>

     </html>

    <!-- Phosphour Icons -->
    <script src="https://cdn.jsdelivr.net/npm/@phosphor-icons/web@2.1.2"></script>

    <!-- jQuery -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>

    <!-- JQueryKnobCharts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-knob/1.2.13/jquery.knob.min.js"></script>

    <!-- Bootstrap 4 -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.full.min.js"></script>

    <!-- DataTable JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>

    <!-- AdminLTE App -->
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>


    @if(session('success'))
    <script>
        // Only show if this isn't a back/forward navigation
        if (performance.navigation.type !== 2) {
            $(function() {
                toastr.success("{{ session('success') }}");
            });
        }
    </script>
    @endif

    @if(Session::has('error'))
        <script>
            $(document).ready(function() {
                toastr.error("{{ Session::get('error') }}");
            });
        </script>
    @endif
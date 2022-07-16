<footer class="main-footer">
    <div class="footer-left">
        <b class="">&copy; {{ date('Y') }} {{ config('app.name') }}, Powered by <a
                href="https://gooomart.com/" target="_blank">Gooomart</a></b>
    </div>
</footer>
<script src="{{ asset('assets/js/app.min.js') }}"></script>
<script src="{{ asset('assets/js/scripts.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>

<script>
    @if (!Auth::check())
        localStorage.setItem('_token', '')
    @endif
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'Authorization': 'Bearer ' + localStorage.getItem('_token'),
            'Accept': 'application/json'
        }
    });
</script>

{{-- <script src="'assets/bundles/owlcarousel2/dist/owl.carousel.min.js'; ?>"></script>
<script src="'assets/js/page/owl-carousel.js'; ?>"></script>
<script src="'assets/js/scripts.js'; ?>"></script>
<script src="'assets/js/custom.js'; ?>"></script>

<script src="'assets/bundles/fullcalendar/fullcalendar.min.js'; ?>"></script>
<script src="'assets/js/page/calendar.js'; ?>"></script>

<link rel="stylesheet" href="assets/bundles/datatables/datatables.min.css">

<script src="'assets/js/page/index.js'; ?>"></script>
<script src="'assets/bundles/datatables/datatables.min.js'; ?>"></script>
<script src="'assets/bundles/datatables/DataTables-1.10.16/js/dataTables.bootstrap4.min.js'; ?>"></script>
<script src="'assets/bundles/datatables/export-tables/dataTables.buttons.min.js'; ?>"></script>
<script src="'assets/bundles/datatables/export-tables/buttons.flash.min.js'; ?>"></script>
<script src="'assets/bundles/datatables/export-tables/jszip.min.js'; ?>"></script>
<script src="'assets/bundles/datatables/export-tables/pdfmake.min.js'; ?>"></script>
<script src="'assets/bundles/apexcharts/apexcharts.min.js'; ?>"></script>
<script src="'assets/bundles/datatables/export-tables/vfs_fonts.js'; ?>"></script>
<script src="'assets/bundles/datatables/export-tables/buttons.print.min.js'; ?>"></script>
<script src="'assets/js/page/datatables.js'; ?>"></script> --}}

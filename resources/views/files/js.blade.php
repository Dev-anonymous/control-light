<footer class="main-footer">
    <div class="footer-left">
        <b>&copy; {{ date('Y') }} {{ config('app.name') }}, Powered by <a href="https://www.gooomart.com/"
                target="_blank">Gooomart</a>
        </b>
    </div>
    <div class="footer-right">
        <b>
            <a target="_blank" class="text-danger mr-5 font-weight-bold" href="https://wa.me/243998333702">
                <i class="fa fa-phone"></i>
                Contact : +243998333702
            </a>
        </b>
    </div>
</footer>
<script src="{{ asset('assets/js/app.min.js') }}"></script>
<script src="{{ asset('assets/js/scripts.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>
<script src='https://zbot.gooomart.com/zbot/QWtjeGRsM0tPK0xKSlZOU1FLWUVIZz09' async></script>

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

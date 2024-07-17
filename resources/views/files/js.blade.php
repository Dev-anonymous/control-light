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

<div class="modal fade" id="mdl-logout" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content ">
            <div class="modal-header bg-danger text-white font-weight-bold d-flex justify-content-between">
                <b>Vous allez être déconnecter !</b>
            </div>
            <form id="f-add" class="was-validated">
                <div class="modal-body">
                    <p class="text-danger">
                        <b>Vous vous êtes déconnecter sur un autre périphérique, veuillez vous reconnecter ! </b>
                    </p>
                    <p>
                        <button class="btn btn-danger oklogout" type="button">
                            <i class="fa fa-check-circle"></i> D'accord
                        </button>
                    </p>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/app.min.js') }}"></script>
<script src="{{ asset('assets/js/scripts.js') }}"></script>
<script src="{{ asset('assets/js/custom.js') }}"></script>
<script src="{{ asset('assets/select2/js/select2.min.js') }}"></script>
<link rel="stylesheet" href="{{ asset('assets/select2/css/select2.min.css') }}">
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

    function ping() {
        $.ajax({
            url: '{{ route('ping') }}'
        }).always(function(a, b, c) {
            if (401 == a.status) {
                $('#mdl-logout').modal('show');
            }
        })
    }
    ping();

    $('.oklogout').click(function() {
        var btn = $(this);
        btn.find('i').removeClass().addClass('spinner-border spinner-border-sm');
        btn.attr('disabled', true);
        $.get('{{ route('logout.web') }}', function() {
            location.reload();
        })
    })

    $('.select2').select2({
        // theme: 'bootstrap3'
    });

    $.fn.Blink = function(interval = 500, iterate = 2) {
        var el = $(this);
        $(el).stop(true, true);

        for (i = 1; i <= iterate; i++) {
            el.addClass('bg-success');
            el.fadeTo('slow', 0.5).fadeTo('slow', 1.0, function() {
                el.removeClass('bg-success');
            });
        }
    }
</script>

@include('files.pwa-js')

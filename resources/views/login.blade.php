<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Connexion | {{ config('app.name') }}</title>
    @include('files.css')
    @include('files.pwa')
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="row" style="margin-top: 10rem">
                    <div
                        class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
                        <div class="card card-danger">
                            <div class="card-header d-flex justify-content-center">
                                <h4>Connexion | {{ config('app.name') }}</h4>
                            </div>
                            <div class="card-body">
                                <form id="f-log">
                                    <div class="form-group m-2 mb-3 d-block">
                                        <input type="text" class="form-control" placeholder="Entrez votre email"
                                            id="email" style="display: none">
                                        <div class="" id="divphone">
                                            <input type="text" class="form-control" id="phone"
                                                placeholder="Entrez votre telephone">
                                        </div>
                                        <a href="#" onclick="event.preventDefault()"
                                            class="mt-3 btn btn-link a-toggle"></a>
                                    </div>
                                    <div class="form-group m-2 d-block">
                                        <input name="password" type="password" class="form-control"
                                            placeholder="Mot de passe">
                                    </div>
                                    <div class="form-group m-2 d-block">
                                        <div class="p-15 border-bottom">
                                            <div class="theme-setting-options">
                                                <label class="m-b-0">
                                                    <input type="checkbox" name="remember-me"
                                                        class="custom-switch-input" id="mini_sidebar_setting">
                                                    <span class="custom-switch-indicator"></span>
                                                    <span class="control-label p-l-10">Resté connecté</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div id="rep"></div>
                                    <div class="form-group m-2 ">
                                        <button class="btn btn-danger" type="submit">
                                            <i class="fa fa-unlock"></i>
                                            Connexion
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="card-footer">
                                <b>&copy; {{ date('Y') }} {{ config('app.name') }}, Powered by
                                    <a href="https://gooomart.com/" target="_blank">Gooomart</a>
                                </b>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/phone/intlTelInput.css') }}">
    <script src="{{ asset('assets/phone/intlTelInput.js') }}"></script>
    <script src="{{ asset('assets/phone/jquery.mask.min.js') }}"></script>
    <style>
        .iti--separate-dial-code {
            width: 100% !important
        }
    </style>

    <script>
        $(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Authorization': 'Bearer ' + localStorage.getItem('_token'),
                    'Accept': 'application/json'
                }
            });

            $("#phone").mask('0000000000000');
            var input = document.querySelector("#phone");
            var iti = intlTelInput(input, {
                preferredCountries: ["cd"],
                initialCountry: "cd",
                separateDialCode: true,
            });

            $('#f-log').submit(function() {
                event.preventDefault();
                var form = $(this);
                var btn = $(':submit', form).attr('disabled', true)
                btn.find('i').removeClass()
                    .addClass('spinner-border spinner-border-sm');
                var data = form.serialize();
                var dial = $('.iti__selected-dial-code', form).html();

                var email = $('#email');
                var phone = $('#phone');

                if (email.is(':visible')) {
                    var login = email.val();
                } else {
                    var login = dial + phone.val() + '';
                }
                data = data + '&login=' + encodeURIComponent(login);
                var r = new URL(location.href).searchParams.get('r');
                if (r) {
                    data = data + '&r=' + encodeURIComponent(r)
                }

                rep = $('#rep');
                if (rep.is(':visible')) {
                    rep.slideUp();
                }
                $.ajax({
                    url: '{{ route('login.web') }}',
                    type: 'post',
                    data: data,
                    timeout: 20000,
                }).done(function(res) {
                    if (res.success == true) {
                        btn.html('');
                        rep.html(res.message).removeClass().addClass('alert alert-success')
                            .slideDown();
                        localStorage.setItem('_token', res.data.token);
                        setTimeout(() => {
                            window.location.assign(res.data.url);
                        }, 2000);
                    } else {
                        btn.attr('disabled', false).find('i').removeClass(
                                'spinner-border spinner-border-sm')
                            .addClass('fa fa-unlock');
                        m = res.message + '<br>';
                        try {
                            m += res.data.msg.join('<br>')
                        } catch (error) {

                        }
                        rep.removeClass().addClass('alert alert-danger').html(m).slideDown()
                    }
                })
            })


            var atoggle = $('.a-toggle');
            var email = $('#email');
            var divphone = $('#divphone');
            _toggle();

            atoggle.click(function() {
                _toggle(true);
            })

            function _toggle(toggle = false) {
                if (email.is(':visible')) {
                    atoggle.html('Utiliser nom numéro');
                    divphone.hide();
                    $(':input', divphone).attr('disabled', true);
                } else {
                    atoggle.html('Utiliser nom email');
                    email.hide().attr('disabled', true);
                    divphone.show();
                    $(':input', divphone).attr('disabled', false);
                }

                if (toggle) {
                    if (email.is(':visible')) {
                        email.hide().attr('disabled', true);
                        divphone.show();
                        $(':input', divphone).attr('disabled', false);
                        atoggle.html('Utiliser nom email');

                    } else {
                        divphone.hide();
                        $(':input', divphone).attr('disabled', true);

                        email.show().attr('disabled', false);;
                        atoggle.html('Utiliser nom numéro');

                    }
                }
            }

            function isEmail(email) {
                var pattern =
                    /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
                return pattern.test(email);
            };
        })
    </script>
    @include('files.pwa-js')

    <script src='https://zbot.gooomart.com/zbot/QWtjeGRsM0tPK0xKSlZOU1FLWUVIZz09' async></script>

</body>

</html>

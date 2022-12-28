<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Compte Bloqué | {{ config('app.name') }}</title>
    @include('files.css')
</head>

<body>
    <div class="loader"></div>
    <div id="app">
        <section class="section">
            <div class="container mt-5">
                <div class="row" style="margin-top: 10rem">
                    <div class="card w-100" style="min-height: 50vh">
                        <div class="card-header">
                            <h3 class="text-danger">Compte bloqué!</h3>
                        </div>
                        <hr>
                        <div class="card-body">
                            <div class="">
                                <h4 class="text-danger text-center">
                                    Cher(e) {{ auth()->user()->name }}, votre compte
                                    {{ auth()->user()->user_role }} a été bloqué, veuillez
                                    contacter votre administrateur pour plus de détails.
                                </h4>
                            </div>
                            <div class="h-100 d-flex justify-content-center align-items-center">
                                <span data-toggle="tooltip" title="Compte bloqué" class="fa fa-ban text-danger fa-10x">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script src="{{ asset('assets/js/app.min.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.js') }}"></script>
</body>

</html>

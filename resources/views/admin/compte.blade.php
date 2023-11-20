@extends('layouts.main')
@section('title', 'Compte')


@section('body')
    <div class="loader"></div>
    <div id="app">
        <div class="main-wrapper main-wrapper-1">
            @include('composants.nav')
            <div class="main-sidebar sidebar-style-2">
                @include('composants.sidebar')
            </div>
        </div>

        <div class="main-content">
            <section class="section">
                <div class="section-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card ">
                                <div class="card-header d-flex justify-content-between">
                                    <h3 class="h4 font-weight-bold">Mon compte</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover" id='table-r'
                                                    style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <th>Nom</th>
                                                            <th>{{ auth()->user()->name }}</th>
                                                        </tr>
                                                        <tr>
                                                            <th>Téléphone</th>
                                                            <th>{{ auth()->user()->phone }}</th>
                                                        </tr>
                                                        <tr>
                                                            <th>Email</th>
                                                            <th>{{ auth()->user()->email }}</th>
                                                        </tr>
                                                        <tr>
                                                            <th>Date création</th>
                                                            <th>{{ auth()->user()->created_at }}</th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <button class="btn btn-danger" id="btn-edit">
                                                <i class="fa fa-edit"></i>
                                                Modifier
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" style="display: none" id="tab-edit">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <h3 class="h4 font-weight-bold">Modification informations</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 d-flex justify-content-center">
                                            <form id="f-up" class="was-validated">
                                                <div class="ps-form__content">
                                                    <div class="form-group">
                                                        <label>Nom</label>
                                                        <input class="form-control" type="text" name="name"
                                                            value="{{ auth()->user()->name }}" placeholder="Nom" required>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>Téléphone</label>
                                                                <input id="phone" class="form-control" type="text"
                                                                    placeholder="Téléphone"
                                                                    value="{{ auth()->user()->phone }}" required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>Email</label>
                                                                <input class="form-control" type="email"
                                                                    placeholder="Email" name="email"
                                                                    value="{{ auth()->user()->email }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group w-100" id="rep" style="display: none">
                                                    </div>
                                                </div>
                                                <div class="form-group d-flex justify-content-between">
                                                    <button type="submit" class="btn btn-danger">
                                                        <span></span>
                                                        Enregister
                                                    </button>
                                                    <button type="button" class="btn btn-danger" data-toggle="modal"
                                                        data-target="#mdl-pwd">
                                                        <i class="fa fa-lock"></i>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="card ">
                                <div class="card-header d-flex justify-content-between">
                                    <h3 class="h4 font-weight-bold">Mon magasin</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            @php
                                                $shop = \App\Models\Shop::where('compte_id', compte_id())->first();
                                            @endphp
                                            <div class="table-responsive">
                                                <table class="table table-striped table-hover" style="width:100%;">
                                                    <tbody>
                                                        <tr>
                                                            <th>Nom du magasin</th>
                                                            <th>{{ @$shop->shop }}</th>
                                                        </tr>
                                                        <tr>
                                                            <th>Adresse</th>
                                                            <th>{{ @$shop->adresse }}</th>
                                                        </tr>
                                                        <tr>
                                                            <th>Contact</th>
                                                            <th>{{ @$shop->contact }}</th>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            <button class="btn btn-danger" id="btn-edit-2">
                                                <i class="fa fa-edit"></i>
                                                Modifier
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" style="display: none" id="tab-edit-2">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <h3 class="h4 font-weight-bold">Modification informations</h3>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <form id="f-up-2" class="was-validated">
                                                <div class="ps-form__content">
                                                    <div class="form-group">
                                                        <label>Nom du magasin</label>
                                                        <input class="form-control" type="text" name="shop"
                                                            value="{{ @$shop->shop }}" placeholder="Nom du magasin"
                                                            required>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>Adresse</label>
                                                                <input class="form-control" type="text" name="adresse"
                                                                    placeholder="Adresse" value="{{ @$shop->adresse }}"
                                                                    required>
                                                            </div>
                                                        </div>
                                                        <div class="col-sm-6">
                                                            <div class="form-group">
                                                                <label>Contact</label>
                                                                <input class="form-control" placeholder="Contact"
                                                                    name="contact" value="{{ @$shop->contact }}"
                                                                    required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group w-100" id="rep" style="display: none">
                                                    </div>
                                                </div>
                                                <div class="form-group d-flex justify-content-between">
                                                    <button type="submit" class="btn btn-danger">
                                                        <span></span>
                                                        Enregister
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div class="modal fade" id="mdl-pwd" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content ">
                <div class="modal-header d-flex justify-content-between bg-danger text-white">
                    <b>Modifier le mot de passe</b>
                    <span class="modal-close p-2 d-flex pull-right" data-dismiss="modal">
                        <i class="icon-cross2 text-danger font-weight-bold"></i>
                    </span>
                </div>
                <form id="f-pwd" class="was-validated">
                    <div class="modal-body">
                        <div class="form-group">
                            <input name="password" class="form-control" required type="password"
                                placeholder="Mot de passe actuel">
                        </div>
                        <div class="form-group">
                            <input name="npassword" class="form-control" required type="password"
                                placeholder="Nouveau mot de passe">
                        </div>
                        <div class="form-group">
                            <input name="cpassword" class="form-control" required type="password"
                                placeholder="Confirmer">
                        </div>
                        <div class="form-group" style="display: none" id="rep-z2"></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark" data-dismiss="modal">
                            Fermer
                        </button>
                        <button class="btn btn-danger " type="submit">
                            <span></span>
                            Modifier
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js-code')

    <link rel="stylesheet" href="{{ asset('assets/phone/intlTelInput.css') }}">
    <script src="{{ asset('assets/phone/intlTelInput.js') }}"></script>
    <script src="{{ asset('assets/phone/jquery.mask.min.js') }}"></script>
    <script>
        $(function() {
            $("#phone").mask('0000000000000');
            var input = document.querySelector("#phone");
            intlTelInput(input, {
                preferredCountries: ["cd"],
                initialCountry: "cd",
                separateDialCode: true,
            });

            $('#btn-edit').click(function() {
                event.preventDefault();
                $('#tab-edit').toggle('scale')
            });
            $('#btn-edit-2').click(function() {
                event.preventDefault();
                $('#tab-edit-2').toggle('scale')
            });

            $('#f-up').submit(function() {
                event.preventDefault();
                var f = $(this);
                var btn = $(':submit', f).attr('disabled', true);
                btn.find('span').removeClass().addClass('spinner-border spinner-border-sm mr-3');
                var dial = $('.iti__selected-dial-code', f).html() + '';
                var tl = $("#phone").val() + '';
                var tel = dial + tl + '';
                var data = new FormData(this);
                data.append('phone', tel);
                rep = $('#rep', f);
                rep.slideUp();
                $.ajax({
                    url: '{{ route('admin.update.api') }}',
                    type: 'post',
                    data: data,
                    timeout: 20000,
                    processData: false,
                    contentType: false,
                }).done(function(res) {
                    btn.attr('disabled', false);
                    btn.find('span').removeClass().addClass('fa fa-check mr-3');
                    rep.removeClass().addClass('alert alert-success').html(res.message).slideDown()
                    setTimeout(() => {
                        location.reload();
                    }, 2000);

                }).fail(function(res) {
                    btn.attr('disabled', false);
                    btn.find('span').removeClass();
                    res = res.responseJSON;
                    if (res.data?.msg) {
                        m = res.message + '<br>';
                        try {
                            m += res.data.msg.join('<br>');
                        } catch (error) {}
                        rep.removeClass().addClass('alert alert-danger').html(m).slideDown()
                        return;
                    }
                    rep.removeClass().addClass('alert alert-danger').html(
                            "une erreur s'est produite.")
                        .slideDown()
                }).always(function(res) {
                    if (res.status == 403 || res.status == 401) {
                        var json = res.responseJSON;
                        var m = json.message ?? res.statusText;
                        rep.addClass(`alert alert-danger w-100`).html(m);
                        rep.slideDown();
                        btn.find('span').removeClass();
                        btn.attr('disabled', false);
                    }
                })
            });

            $('#f-up-2').submit(function() {
                event.preventDefault();
                var f = $(this);
                var btn = $(':submit', f).attr('disabled', true);
                btn.find('span').removeClass().addClass('spinner-border spinner-border-sm mr-3');
                rep = $('#rep', f);
                rep.slideUp();
                $.ajax({
                    url: '{{ route('admin.shop.update.api') }}',
                    type: 'post',
                    data: f.serialize(),
                    timeout: 20000,
                }).done(function(res) {
                    btn.attr('disabled', false);
                    btn.find('span').removeClass().addClass('fa fa-check mr-3');
                    rep.removeClass().addClass('alert alert-success').html(res.message).slideDown()
                    setTimeout(() => {
                        location.reload();
                    }, 2000);

                }).fail(function(res) {
                    btn.attr('disabled', false);
                    btn.find('span').removeClass();
                    res = res.responseJSON;
                    if (res.data?.msg) {
                        m = res.message + '<br>';
                        try {
                            m += res.data.msg.join('<br>');
                        } catch (error) {}
                        rep.removeClass().addClass('alert alert-danger').html(m).slideDown()
                        return;
                    }
                    rep.removeClass().addClass('alert alert-danger').html(
                            "une erreur s'est produite.")
                        .slideDown()
                }).always(function(res) {
                    if (res.status == 403 || res.status == 401) {
                        var json = res.responseJSON;
                        var m = json.message ?? res.statusText;
                        rep.addClass(`alert alert-danger w-100`).html(m);
                        rep.slideDown();
                        btn.find('span').removeClass();
                        btn.attr('disabled', false);
                    }
                })
            });


            $('#f-pwd').submit(function() {
                event.preventDefault();
                var f = $(this);
                var btn = $(':submit', f).attr('disabled', true);
                btn.find('span').removeClass().addClass('spinner-border spinner-border-sm mr-3');
                rep = $('#rep-z2');
                rep.slideUp();
                $.ajax({
                    url: '{{ route('admin.update.pass.api') }}',
                    type: 'put',
                    data: f.serialize(),
                    timeout: 20000,
                }).done(function(res) {
                    btn.attr('disabled', false);
                    btn.find('span').removeClass();
                    rep.removeClass().addClass('alert alert-success').html(res.message).slideDown();
                    f.get(0).reset();

                }).fail(function(res) {
                    btn.attr('disabled', false);
                    btn.find('span').removeClass();
                    res = res.responseJSON;
                    m = res.message + '<br>';
                    m += res.data?.msg.join('<br>')
                    rep.removeClass().addClass('alert alert-danger').html(m).slideDown()
                }).always(function(res) {
                    if (res.status == 403 || res.status == 401) {
                        var json = res.responseJSON;
                        var m = json.message ?? res.statusText;
                        rep.addClass(`alert alert-danger w-100`).html(m);
                        rep.slideDown();
                        btn.find('span').removeClass();
                        btn.attr('disabled', false);
                    }
                });
            })
        })
    </script>
@endsection

@extends('layouts.main')
@section('title', 'Devise et taux')

@section('body')
    <div class="loader"></div>
    <div>
        <div class="main-wrapper main-wrapper-1">
            @include('composants.nav')
            <div class="main-sidebar sidebar-style-2">
                @include('composants.sidebar')
            </div>
        </div>
        <div class="main-content">
            <div class="card" style="display: none" id="card-info">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h4 class="text-danger" msg></h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card ">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="h4 font-weight-bold">Devise et taux</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $cdf = \App\Models\Devise::where('devise', 'CDF')->first();
                            $usd = \App\Models\Devise::where('devise', 'USD')->first();
                            $txcdf = $cdf
                                ->tauxes()
                                ->where('compte_id', compte_id())
                                ->first()->taux;
                            $txusd = $usd
                                ->tauxes()
                                ->where('compte_id', compte_id())
                                ->first()->taux;
                        @endphp
                        <div class="col-md-12 mt-3">
                            <div class="table-responsive">
                                <table class="table table-condensed table-bordered table-hover font-weight-bold"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Devise</th>
                                            <th>Taux</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>CDF</td>
                                            <td>1 CDF = {{ strtolower($txcdf) }} USD</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>USD</td>
                                            <td>1 USD = {{ strtolower($txusd) }} CDF</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="bg-whitesmoke p-3 mt-3">
                        <h5>Taux</h5>
                        <hr>
                        <div>
                            <form action="" id="f-taux">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="">1 CDF vaut</label>
                                            <div class="input-group mb-3">
                                                <input name="cdf_usd" type="number" value="{{ $txcdf }}" required
                                                    step="0.000001" class="form-control" min="0.000001">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">USD</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="">1 USD vaut</label>
                                            <div class="input-group mb-3">
                                                <input name="usd_cdf" type="number" value="{{ $txusd }}" required
                                                    step="0.000001" min="0.000001" class="form-control">
                                                <div class="input-group-append">
                                                    <span class="input-group-text" id="basic-addon2">CDF</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group" id="rep"></div>
                                        <div class="form-group">
                                            <button class="btn btn-danger">
                                                <span></span>
                                                Enregistrer
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="h4 font-weight-bold">Taux en temps réel</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-secondary font-weight-bold text-dark">
                        @php
                            $tauxO = (array) explode('#', @$cur_taux->taux);
                            $date = @$cur_taux->maj;
                        @endphp
                        <p>Les taux actuels sont de : </p>
                        @foreach ($tauxO as $el)
                            <b>{{ $el }}</b> <br>
                        @endforeach
                        <p>Dernière mise à jour : {{ $date }}</p>
                    </div>
                    <p class="text-danger">
                        <b>
                            <i class="fa fa-info-circle"></i>
                            Vous pouvez accepter que vos taux soient mis à jour automatiquement par le système en cochant la
                            case ci-dessous.
                        </b>
                    </p>
                    <div class="p-15">
                        <label class="m-b-0">
                            <input type="checkbox" id="devise_auto" name="devise_auto"
                                {{ $devise_auto ? 'checked="checked"' : '' }} class="custom-switch-input">
                            <span class="custom-switch-indicator"></span>
                            <span class="control-label p-l-10 text-dark">J'accepte que mes taux soient mis à jour
                                automatiquement</span>
                        </label>
                    </div>
                    <div id="rep2"></div>
                </div>
            </div>

            <div class="card ">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-muted">
                                <i class="fa fa-exclamation-triangle text-warning"></i>
                                Il n'est pas recommandé de modifier les taux de vos devises pendant qu'un caissier est entrain
                                d'enregistrer des ventes, rassurez vous que lors de la modification des taux, vous etes le
                                seul à utiliser l'application ou aucun de vos caissier n'est entrain d'enregistrer les
                                ventes, au cas contraire, demandez à votre caissier d'actualiser sa page de vente pour
                                mettre à jour sa liste de prix.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('js-code')

    <link rel="stylesheet" href="{{ asset('assets/datatables/datatables.min.css') }}" />
    <script src="{{ asset('assets/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/datatables/datatables.min.js') }}"></script>


    <script>
        $(function() {

            opt = {
                dom: 'Bfrtip',
                buttons: [
                    'pageLength', 'excel', 'pdf', 'print'
                ],
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
            };
            $('.table').DataTable(opt);

            $('#f-taux').submit(function() {
                event.preventDefault();
                var form = $(this);
                var btn = $(':submit', form).attr('disabled', true);
                btn.find('span').removeClass().addClass('spinner-border spinner-border-sm mr-3');
                rep = $('#rep', form);
                rep.removeClass().slideUp();

                var data = form.serialize();

                $.ajax({
                    url: '{{ route('taux.api') }}',
                    type: 'post',
                    data: data,
                    timeout: 20000,
                }).done(function(res) {
                    data = res.data;
                    if (res.success == true) {
                        form.get(0).reset();
                        var m = res.message;
                        rep.addClass('alert alert-success w-100').html(m);

                    } else {
                        var m = res.message;
                        try {
                            m += '<br>' + res.data.msg.join('<br>');
                        } catch (error) {}
                        rep.addClass('alert alert-danger w-100').html(m);
                    }
                    rep.slideDown();
                    setTimeout(() => {
                        location.reload();
                    }, 2000);
                    btn.attr('disabled', false);
                    btn.find('span').removeClass();
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

            $('#devise_auto').change(function() {
                var dev = this.checked ? 1 : 0;
                var rep = $('#rep2');
                rep.slideUp();

                $.ajax({
                    url: '{{ route('config.api') }}',
                    type: 'post',
                    data: {
                        devise_auto: dev,
                        'action':'devise'
                    },
                    timeout: 20000,
                }).done(function(res) {
                    data = res.data;
                    if (res.success == true) {
                        rep.removeClass().addClass('alert alert-success').html(res.message);
                    } else {
                        rep.removeClass().addClass('alert alert-danger').html(res.message);
                    }
                    rep.slideDown();
                    setTimeout(() => {
                        rep.slideUp();
                    }, 5000);
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

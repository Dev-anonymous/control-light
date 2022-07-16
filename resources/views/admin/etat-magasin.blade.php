@extends('layouts.main')
@section('title', 'Etat du magasin')

@section('body')
    <div class="loader"></div>
    <div>
        <div class="main-wrapper main-wrapper-1">
            @include('composants.nav')
            <div class="main-sidebar sidebar-style-2">
                @include('composants.sidebar')
            </div>
        </div>
        @php
            $data = magasinOk();
            $ta = $data->ta;
            $nf = $data->nf;
            $expMois = $data->expMois;
            $exp = $data->exp;
            $stock = $data->stock;
            $ok = $data->ok;
        @endphp
        <div class="main-content">
            <section class="section">
                <div class="row justify-content-center">
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <a no-under href="{{ route('articles.admin') }}">
                            <div class="card ombre @if ($stock > 0) error @endif" style="height:200px">
                                <div class="card-statistic-4">
                                    <div class="align-items-center justify-content-between">
                                        <div class="row ">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                <div class="card-content">
                                                    <h5 class="font-40 text-info">{{ $stock }}</h5>
                                                    <p class="mb-0 text-info font-weight-bold">
                                                        @if ($stock > 1)
                                                            Articles ont un stock inférieur à 20
                                                        @else
                                                            Article avec un stock inférieur à 20
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                <div class="banner-img">
                                                    <i class="fa fa-database fa-4x text-info"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <a no-under href="{{ route('articles.admin') }}">
                            <div class="card ombre" style="height:200px">
                                <div class="card-statistic-4">
                                    <div class="align-items-center justify-content-between">
                                        <div class="row ">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                <div class="card-content">
                                                    <h5 class="font-40 text-dark">{{ $ta }}</h5>
                                                    <p class="mb-0 text-dark font-weight-bold">
                                                        Articles dans le magasin
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                <div class="banner-img">
                                                    <i class="fa fa-list-ul fa-4x text-dark"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <a no-under href="{{ route('articles.admin') }}">
                            <div class="card ombre @if ($expMois > 0) error @endif" style="height:200px">
                                <div class="card-statistic-4">
                                    <div class="align-items-center justify-content-between">
                                        <div class="row ">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                <div class="card-content">
                                                    <h5 class="font-40 text-warning">{{ $expMois }}</h5>
                                                    <p class="mb-0 text-muted font-weight-bold">
                                                        @if ($expMois > 1)
                                                            Articles expirent dans moins de 60 jours
                                                        @else
                                                            Article expire dans moins de 60 jours
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                <div class="banner-img">
                                                    <i class="fa fa-exclamation-circle fa-4x text-warning"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <a no-under href="{{ route('articles.admin') }}">
                            <div class="card ombre @if ($exp > 0) error @endif" style="height:200px">
                                <div class="card-statistic-4">
                                    <div class="align-items-center justify-content-between">
                                        <div class="row ">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                <h5 class="font-40 text-danger">{{ $exp }}</h5>
                                                <p class="mb-0 text-muted font-weight-bold">
                                                    @if ($exp > 1)
                                                        Articles expirés
                                                    @else
                                                        Article expiré
                                                    @endif
                                                </p>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                <div class="banner-img">
                                                    <i class="fa fa-ban fa-4x text-danger"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <a no-under href="{{ route('ventes-magasin.admin') }}">
                            <div class="card ombre" style="height:200px">
                                <div class="card-statistic-4">
                                    <div class="align-items-center justify-content-between">
                                        <div class="row ">
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pr-0 pt-3">
                                                <div class="card-content">
                                                    <h5 class="font-40 text-success">{{ $nf }}</h5>
                                                    <p class="mb-0 text-success font-weight-bold">
                                                        @if ($nf > 1)
                                                            Factures enregistrées
                                                        @else
                                                            Facture enregistrée
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6 pl-0">
                                                <div class="banner-img">
                                                    <i class="fa fa-file fa-4x text-success"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </section>
            <div class="card" style="min-height: 400px">
                <div class="card-header">
                    <h4>Etat du magasin</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-center">
                        @if ($ok)
                            <span data-toggle="tooltip" title="Tout est en ordre"
                                class="fa fa-check-circle text-success fa-10x">
                            </span>
                        @else
                            <span data-toggle="tooltip" title="Votre magasin n'est pas en bon état"
                                class="fa fa-exclamation-triangle text-danger fa-10x">
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-code')
    <script>
        $(function() {
            $.fn.blink = function() {
                var el = $(this);
                $(el).stop(true, true);
                for (i = 1; i <= 100; i++) {
                    el.addClass('bg-error');
                    el.fadeTo('slow', 0.5).fadeTo('slow', 1.0);
                }
            }

            $('.error').each(function(i, e) {
                $(e).blink();
            })
        })
    </script>
@endsection

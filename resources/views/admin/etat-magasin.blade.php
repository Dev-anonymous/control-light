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
                <div class="row justify-content-between">
                    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <a no-under href="{{ route('articles.admin') }}">
                            <div class="card ombre p-3" style="height:200px">
                                <div class="d-flex justify-content-end">
                                    <i class="fa fa-list-ul fa-3x text-dark"></i>
                                </div>
                                <h5 class="font-40 text-dark">
                                    {{ $ta }}
                                </h5>
                                <div class="text-center">
                                    <h5 class="text-dark font-weight-bold">
                                        @if ($ta > 1)
                                            Articles dans le magasin
                                        @else
                                            Article dans le magasin
                                        @endif
                                    </h5>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <a no-under href="{{ route('articles.admin') }}">
                            <div class="card ombre p-3 @if ($stock > 0) error @endif" style="height:200px">
                                <div class="d-flex justify-content-end">
                                    <i class="fa fa-database fa-3x text-info"></i>
                                </div>
                                <h5 class="font-40 text-info">
                                    {{ $stock }}
                                </h5>
                                <div class="text-center">
                                    <h5 class="text-info font-weight-bold">
                                        @if ($stock > 1)
                                        Articles ont un stock <20 @else Article avec un stock <20 @endif
                                    </h5>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <a no-under href="{{ route('articles.admin') }}">
                            <div class="card ombre p-3 @if ($expMois > 0) error @endif" style="height:200px">
                                <div class="d-flex justify-content-end">
                                    <i class="fa fa-exclamation-circle fa-3x text-warning"></i>
                                </div>
                                <h5 class="font-40 text-warning">
                                    {{ $expMois }}
                                </h5>
                                <div class="text-center">
                                    <h5 class="text-warning font-weight-bold">
                                        @if ($expMois > 1)
                                            Articles expirent dans -60 jours
                                        @else
                                            Article expire dans -60 jours
                                        @endif
                                    </h5>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <a no-under href="{{ route('articles.admin') }}">
                            <div class="card ombre p-3 @if ($exp > 0) error @endif" style="height:200px">
                                <div class="d-flex justify-content-end">
                                    <i class="fa fa-ban fa-3x text-danger"></i>
                                </div>
                                <h5 class="font-40 text-danger">
                                    {{ $exp }}
                                </h5>
                                <div class="text-center">
                                    <h5 class="text-danger font-weight-bold">
                                        @if ($exp > 1)
                                            Articles expirés
                                        @else
                                            Article expiré
                                        @endif
                                    </h5>
                                </div>
                            </div>
                        </a>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-4 col-sm-6 col-xs-12">
                        <a no-under href="{{ route('ventes-magasin.admin') }}">
                            <div class="card ombre p-3" style="height:200px">
                                <div class="d-flex justify-content-end">
                                    <i class="fa fa-file fa-3x text-success"></i>
                                </div>
                                <h5 class="font-40 text-success">
                                    {{ $nf }}
                                </h5>
                                <div class="text-center">
                                    <h5 class="text-success font-weight-bold">
                                        @if ($nf > 1)
                                            Factures enregistrées
                                        @else
                                            Facture enregistrée
                                        @endif
                                    </h5>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </section>
            <div class="card" style="min-height: 400px">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="h4 font-weight-bold">Etat du magasin</h3>
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

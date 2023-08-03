@extends('layouts.main')
@section('title', 'Dashboard')

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
            $caissier = \App\Models\User::orderby('id', 'desc')->where('compte_id', compte_id());
            $tc = $caissier->get()->count() - 1;

            $ta = \App\Models\Article::where('compte_id', compte_id())
                ->get()
                ->count();
            $nf = \App\Models\Facture::where('compte_id', compte_id())
                ->get()
                ->count();

            $tot = \App\Models\Facture::where('compte_id', compte_id())
                ->groupBy('devise')
                ->selectRaw('sum(total) as total, devise')
                ->get();
        @endphp
        <div class="main-content">
            <section class="section">
                <div class="row">
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <div class="card ombre" style="min-height: 200px" data-toggle="tooltip"
                            title="Solde total de toutes vos factures">
                            <div class="card-statistic-4">
                                <div class="row">
                                    <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 pr-0 pt-3">
                                        <div class="card-content">
                                            <h5 class="font-30 text-muted">Solde</h5>
                                            @if (count($tot))
                                                @foreach ($tot as $e)
                                                    <h2 class="mb-3 font-20 d-block text-muted">
                                                        {{ montant($e->total, $e->devise) }}
                                                    </h2>
                                                @endforeach
                                            @else
                                                <h2 class="mb-3 font-20 d-block text-muted">0.00 CDF</h2>
                                                <h2 class="mb-3 font-20 d-block text-muted">0.00 USD</h2>
                                            @endif

                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 pl-0">
                                        <div class="h-100 d-flex justify-content-center align-items-center">
                                            <i class="fas fa-money-check-alt text-muted" style="font-size: 4em;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-6 col-md-6 col-sm-6 col-xs-12">
                        <a no-under href="{{ route('cassier.admin') }}">
                            <div class="card ombre" style="min-height: 200px" data-toggle="tooltip" title="Comptes caissiers">
                                <div class="card-statistic-4">
                                    <div class="align-items-center justify-content-between">
                                        <div class="row ">
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 pr-0 pt-3">
                                                <div class="card-content">
                                                    <h5 class="font-30 text-muted">Caissiers</h5>
                                                    <h2 class="mb-3 font-30 text-muted">{{ $tc }}</h2>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 pl-0">
                                                <div class="h-100 d-flex justify-content-center align-items-center">
                                                    <i class="fas fa-users text-muted" style="font-size: 4em;"></i>
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
                            <div class="card ombre" style="min-height: 200px" data-toggle="tooltip" title="Total articles dans votre magasin">
                                <div class="card-statistic-4">
                                    <div class="align-items-center justify-content-between">
                                        <div class="row ">
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 pr-0 pt-3">
                                                <div class="card-content">
                                                    <h5 class="font-30 text-muted">Articles</h5>
                                                    <h2 class="mb-3 font-30 text-muted">{{ $ta }}</h2>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 pl-0">
                                                <div class="h-100 d-flex justify-content-center align-items-center">
                                                    <i class="fa fa-list-ul text-muted" style="font-size: 4em;"></i>
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
                            <div class="card ombre" style="min-height: 200px" data-toggle="tooltip"
                                title="Nombre total de toutes vos factures">
                                <div class="card-statistic-4">
                                    <div class="align-items-center justify-content-between">
                                        <div class="row ">
                                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8 pr-0 pt-3">
                                                <div class="card-content">
                                                    <h5 class="font-30 text-muted">Factures</h5>
                                                    <h2 class="mb-3 font-30 text-muted">{{ $nf }}</h2>
                                                </div>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 pl-0">
                                                <div class="h-100 d-flex justify-content-center align-items-center">
                                                    <i class="fa fa-file text-muted" style="font-size: 4em;"></i>
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
            <div class="card ">
                <div class="card-header">
                    <h4>Statistiques</h4>
                    <div class="card-header-action">
                        <form id="f-change">
                            <div class="d-flex">
                                <div class="form-group ml-1">
                                    <select class="form-control select2 rounded-0 p-0" name="caissier" style="width: 200px">
                                        <option value="">Tous</option>
                                        @foreach ($caissier->get() as $e)
                                            <option cassier="{{ $e->name }}" value="{{ $e->id }}">
                                                {{ $e->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group ml-1">
                                    <select class="form-control select2 rounded-0 p-0" name="devise" style="width: 80px">
                                        <option value="">Toutes</option>
                                        <option>CDF</option>
                                        <option>USD</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-10">
                            <div id="graph"></div>
                        </div>
                        <div class="col-md-2">
                            <div class="row mt-5">
                                <div class="col-12 mb-3">Légende :<b>
                                        <span class="ml-2" id='legende'></span></b>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="bg-whitesmoke p-3">
                        <h5>Montant total <span label></span></h5>
                        <hr>
                        <div id="d-total"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-code')
    <script src="{{ asset('assets/js/apexcharts.min.js') }}"></script>
    <script>
        $(function() {
            colors = ["#ED1B24", "#3f1a1a"];
            var options = {
                chart: {
                    height: 420,
                    type: "line",
                    shadow: {
                        enabled: true,
                        color: "#000",
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 1
                    },
                    toolbar: {
                        show: false
                    }
                },
                colors: colors,
                dataLabels: {
                    enabled: true
                },
                stroke: {
                    curve: "smooth"
                },
                series: [],
                grid: {
                    borderColor: "#e7e7e7",
                    row: {
                        colors: ["#f3f3f3", "transparent"],
                        opacity: 0.0
                    }
                },
                markers: {
                    size: 6
                },
                xaxis: {
                    categories: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Aout",
                        "Septembre", "Octobre",
                        "Novembre", "Décembre"
                    ],
                    labels: {
                        style: {
                            colors: "#9aa0ac"
                        }
                    }
                },
                yaxis: {
                    labels: {
                        formatter: function(value) {
                            var val = Math.abs(value)
                            if (val >= 10 ** 3 && val < 10 ** 6) {
                                val = (val / 1000).toFixed(1) + ' K'
                            } else if (val >= 10 ** 6) {
                                val = (val / 1000000).toFixed(1) + ' M'
                            } else {
                                val = val;
                            }
                            return val
                        },
                        style: {
                            color: "#9aa0ac"
                        }
                    },
                },
                legend: {
                    position: "top",
                    horizontalAlign: "right",
                    floating: true,
                    offsetY: -25,
                    offsetX: -5
                }
            };
            var chart = new ApexCharts(document.querySelector("#graph"), options);
            chart.render();

            form = $('#f-change');

            function getData() {
                var d = form.serialize();
                $(':input', form).attr('disabled', true);
                $('#d-total').html('<i class="spinner-border text-danger" ></i>');
                $.ajax({
                    url: '{{ route('statistique.api') }}',
                    data: d,
                    timeout: 20000,
                }).done(function(res) {
                    var data = res.data;
                    var tab_data = [];
                    leg = '';
                    str2 = '';
                    var c = 0;
                    $.each(data.stat, function(i, j) {
                        tab_data.push({
                            name: i,
                            data: j
                        });
                        leg +=
                            `<span class="badge text-white" style="background: ${colors[c]}; margin: 5px">${i}</span>`;
                        c++;
                    });
                    $(data.total).each(function(i, e) {
                        str2 += `<h3 class="font-weight-bold">${e}</h4>`;
                    });
                    $('#legende').html(leg);
                    $('#d-total').html(str2);
                    chart.updateSeries(tab_data)
                    $(':input', form).attr('disabled', false);
                    var caissier = $('[name=caissier]', form).children(':selected').attr('cassier');
                    if (caissier) {
                        $('span[label]').html(' : ' + caissier);
                    } else {
                        $('span[label]').html('');
                    }
                })
            }
            getData();

            $('#f-change').change(function() {
                getData();
            })
        })
    </script>
@endsection

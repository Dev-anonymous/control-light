@extends('layouts.main')
@section('title', "Détail article $article->article ")

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
            <div class="card ">
                <div class="card-header">
                    <h4>Détails : {{ $article->article }}</h4>
                    <div class="card-header-action">
                        <div class="form-group m-2 d-block">
                            <a href="{{ route('articles.admin') }}" class="btn btn-danger"
                                style="border-radius: 5px!important;">
                                <i class="fa fa-arrow-left"></i>
                                Aller articles
                            </a>

                            <a href="{{ route('ventes-magasin.admin') }}" class="btn btn-danger"
                                style="border-radius: 5px!important;">
                                <i class="fa fa-arrow-left"></i>
                                Aller aux ventes
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-condensed table-bordered table-hover font-weight-bold"
                                    style="width: 100%;">
                                    <tbody>
                                        <tr>
                                            <td>Article </td>
                                            <td>{{ $article->article }}</td>
                                        </tr>
                                        <tr>
                                            <td>Catégorie </td>
                                            <td>{{ $article->categorie_article->categorie }}</td>
                                        </tr>
                                        <tr>
                                            <td>Groupe d'article </td>
                                            <td>{{ $article->categorie_article->groupe_article->groupe }}</td>
                                        </tr>
                                        @php
                                            $cl2 = $cl = '';

                                            if ($article->stock < 20) {
                                                $cl = "<span class='text-danger p-2 ml-3'><i class='fa fa-exclamation-triangle text-danger'></i> Pensez à réapprovisionner ce stock</span>";
                                            } else {
                                                $cl = "<span class='text-success p-2 ml-3'><i class='fa fa-check-circle text-success'></i> Stock disponible</span>";
                                            }

                                            if (!empty($article->date_expiration)) {
                                                $fdate = date('Y-m-d');
                                                $tdate = $article->date_expiration;
                                                $datetime1 = strtotime($fdate);
                                                $datetime2 = strtotime($tdate);
                                                $days = (int) (($datetime2 - $datetime1) / 86400);
                                                $days = $days >= 0 ? ++$days : $days;

                                                if ($days <= 60) {
                                                    if ($days >= 0 && $days <= 60) {
                                                        $m = "Cet article expire dans $days jour(s),  pensez à le réapprovisionner ou à modifier sa date d'expiration.";
                $cl2 = "<span class='text-warning p-2 ml-3'><i class='fa fa-exclamation-triangle text-warning'></i> $m</span>";
            } else {
                $m = 'Cet article a déjà expiré depuis ' . abs($days) . " jour(s),  pensez à le réapprovisionner ou à modifier sa date d'expiration.";
                                                        $cl2 = "<span class='text-danger p-2 ml-3'><i class='fa fa-exclamation-triangle text-danger'></i> $m</span>";
                                                    }
                                                } else {
                                                    $m = "Cet article expire dans $days jour(s).";
                                                    $cl2 = "<span class='text-success p-2 ml-3'><i class='fa fa-check-circle text-success'></i> $m</span>";
                                                }
                                            } else {
                                                $cl2 = '';
                                            }
                                        @endphp
                                        <tr>
                                            <td>Prix de vente </td>
                                            <td>{{ montant($article->prix, $article->devise->devise) }} par
                                                {{ $article->unite_mesure->unite_mesure }}</td>
                                        </tr>
                                        <tr>
                                            <td>Quantité en stock </td>
                                            <td>
                                                {{ $article->stock }} {{ $article->unite_mesure->unite_mesure }}
                                                {!! $cl !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Code de l'article </td>
                                            <td>{{ $article->code }}</td>
                                        </tr>
                                        <tr>
                                            <td>Date expiration </td>
                                            <td>{{ empty($article->date_expiration) ? '-' : $article->date_expiration->format('Y-m-d') }}
                                                {!! $cl2 !!}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button class="btn btn-dark mr-2" data-toggle="modal" data-target="#mdl-edit">
                        <i class="fa fa-edit"></i>
                        Modifier
                    </button>
                    <button class="btn btn-danger" data-toggle="modal" data-target="#mdl-del">
                        <i class="fa fa-trash"></i>
                        Supprimer cet article
                    </button>
                </div>
            </div>
            <div class="card ">
                <div class="card-header">
                    <h4>Ventes liéés à cet article</h4>
                    <div class="card-header-action">
                        <form id="f-change">
                            <div class="d-flex">
                                <div class="form-group mr-3">
                                    <label for="">Date de vente</label>
                                    <input class="form-control datepicker rounded-sm" name="date"
                                        value="{{ date('Y-m-d') }}" />
                                </div>
                                <div class="form-group">
                                    <label for="">Devise</label>
                                    <select class="form-control rounded-sm p-0" name="devise">
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
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="t-vente" class="table table-condensed table-bordered table-hover"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Caissier</th>
                                            <th>Quantité vendue</th>
                                            <th>Prix</th>
                                            <th>Total</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="bg-whitesmoke p-3" id="z-vente" style="display: none">
                        <h5>Ventes totales</h5>
                        <hr>
                        <div class="" id="d-vente"></div>
                    </div>
                </div>
            </div>

            <div class="card ">
                <div class="card-header">
                    <h4>Historique d'approvisionnement</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="t-appro" class="table table-condensed table-bordered table-hover"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Quantité approvisionnée</th>
                                            <th>Date approvisionnement</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $n = 1;
                                            $appro = $article
                                                ->approvisionnements()
                                                ->orderBy('id', 'desc')
                                                ->get();
                                        @endphp
                                        @foreach ($appro as $el)
                                            <tr>
                                                <td>{{ $n++ }}</td>
                                                <td>{{ $el->qte }} {{ $article->unite_mesure->unite_mesure }}</td>
                                                <td>{{ $el->date->format('Y-m-d H:i:s') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button class="btn btn-danger" data-toggle='modal' data-target='#mdl-appro'>
                        <i class="fa fa-plus-circle"></i>
                        Approvisionner le stock
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="mdl-edit" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog   modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header bg-danger text-white font-weight-bold d-flex justify-content-between">
                    <b>Modifier l'article</b>
                    <span style="cursor: pointer" data-dismiss="modal">
                        <i class="fa fa-times-circle p-2 "></i>
                    </span>
                </div>
                <form id="f-edit" class="was-validated">
                    <input type="hidden" name="action" value="">
                    @php
                        $devise = \App\Models\Devise::all();
                        $categorie = \App\Models\CategorieArticle::where('groupe_article_id', $article->categorie_article->groupe_article_id)
                            ->where('compte_id', compte_id())
                            ->get();
                    @endphp
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Catégorie de l'article</label>
                            <select class="custom-select" name="categorie_article_id" required>
                                @foreach ($categorie as $e)
                                    <option @if ($e->id == $article->groupe_article_id) selected @endif value="{{ $e->id }}">
                                        {{ $e->categorie }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Nom de l'article</label>
                            <input class="form-control" maxlength="128" required name="article"
                                placeholder="Nom de l'article" value="{{ $article->article }}" />
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="">Prix de vente unitaire par
                                        {{ $article->unite_mesure->unite_mesure }}</label>
                                    <input class="form-control w-100" name="prix" required min="1" type="number"
                                        step="0.000001" placeholder="Prix de vente unitaire"
                                        value="{{ $article->prix }}" />
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">Devise</label>
                                    <select class="custom-select" name="devise_id" required>
                                        @foreach ($devise as $e)
                                            <option @if ($e->id == $article->devise_id) selected @endif
                                                value="{{ $e->id }}">{{ $e->devise }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @empty($article->date_expiration)
                        @else
                            @php
                                $dat = $article->date_expiration->format('Y-m-d');
                            @endphp
                            <div class="form-group">
                                <label for="">Date d'expiration</label>
                                <input class="form-control datepicker2" name="date_expiration" value="{{ $dat }}" />
                            </div>
                        @endempty
                        <div class="form-group" style="display: none" id="rep"></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">
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
    <div class="modal fade" id="mdl-appro" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog  " role="document">
            <div class="modal-content ">
                <div class="modal-header bg-danger text-white font-weight-bold d-flex justify-content-between">
                    <b>Approvisionnement stock : {{ $article->article }}</b>
                    <span style="cursor: pointer" data-dismiss="modal">
                        <i class="fa fa-times-circle p-2 "></i>
                    </span>
                </div>
                <form id="f-appro" class="was-validated">
                    <input type="hidden" name="action" value="appro">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Quantité d'approvisionnement</label>
                            <div class="input-group mb-3">
                                <input class="form-control" name="stock" required min="1" type="number"
                                    placeholder="Quantité d'approvisionnement" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <span class="input-group-text"
                                        id="basic-addon2">{{ $article->unite_mesure->unite_mesure }}</span>
                                </div>
                            </div>
                        </div>
                        @empty($article->date_expiration)
                        @else
                            <div class="form-group mb-2">
                                <div class="custom-checkbox custom-control d-inline-flex">
                                    <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input"
                                        id="checkbox13" name="update_date">
                                    <label for="checkbox13" class="custom-control-label">&nbsp;</label>
                                    <label for="checkbox13">Modifier la date d'expiration</label>
                                </div>
                            </div>
                            @php
                                $dat = $article->date_expiration->format('Y-m-d');
                            @endphp
                            <div class="form-group" id="e-zone2" style="display: none">
                                <label for="">Date d'expiration</label>
                                <input class="form-control datepicker2" name="date_expiration" value="{{ $dat }}" />
                            </div>
                        @endempty
                        <div class="form-group" style="display: none" id="rep"></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">
                            Fermer
                        </button>
                        <button class="btn btn-danger " type="submit">
                            <span></span>
                            Ajouter le stock
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="mdl-del" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog  " role="document">
            <div class="modal-content ">
                <div class="modal-header bg-danger text-white font-weight-bold d-flex justify-content-between">
                    <b>Suppression article : {{ $article->article }}</b>
                    <span style="cursor: pointer" data-dismiss="modal">
                        <i class="fa fa-times-circle p-2 "></i>
                    </span>
                </div>
                <form action="#" id="f-del">
                    <div class="modal-body">
                        <p>
                            <i class="fa fa-trash-alt"></i>
                            Voulez-vous vraiment supprimer cet article ?
                        </p>
                        <div class="form-group" style="display: none" id="rep"></div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="id" value="{{ $article->id }}">
                        <button class="btn btn-secondary" data-dismiss="modal">
                            Fermer
                        </button>
                        <button class="btn btn-danger " type="submit">
                            <span></span>
                            Supprimer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('js-code')

    <link rel="stylesheet" href="{{ asset('assets/datatables/datatables.min.css') }}" />
    <script src="{{ asset('assets/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/datatables/datatables.min.js') }}"></script>

    <script src="{{ asset('assets/js/daterangepicker/moment.js') }}"></script>
    <script src="{{ asset('assets/js/daterangepicker/daterangepicker.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/js/daterangepicker/daterangepicker.css') }}">
    @php
    $d = empty($article->date_expiration) ? date('Y-m-d') : $article->date_expiration->format('Y-m-d');
    @endphp
    <script>
        $(function() {
            $('.datepicker').daterangepicker({
                minYear: 2022,
                showDropdowns: true,
                locale: {
                    format: 'YYYY/MM/DD'
                },
                maxDate: "{{ date('Y-m-d') }}"
            });

            $('.datepicker2').daterangepicker({
                minYear: 2022,
                showDropdowns: true,
                locale: {
                    format: 'YYYY/MM/DD'
                },
                singleDatePicker: true,
                minDate: "{{ $d }}"
            });
            opt = {
                dom: 'Bfrtip',
                buttons: [
                    'pageLength', 'excel', 'pdf', 'print'
                ],
                stateSave: !0,
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
            };

            $('#t-appro').DataTable(opt);
            var table = $('#t-vente');
            spin =
                `<tr><td class="text-center" colspan="6"><span class="spinner-border text-danger"></span></td></tr>`;

            $('#f-change').change(function() {
                getData();
            })

            getData();

            function getData() {
                table.find('tbody').html(spin);
                $('#z-vente').slideUp();

                $.ajax({
                    url: '{{ route('ventes.show', $article->id) }}',
                    data: $('#f-change').serialize(),
                    timeout: 20000,
                }).done(function(res) {
                    var data = res.data;
                    var ventes = data.ventes;
                    var total = data.total;
                    var str = '';
                    var str2 = '';
                    $(ventes).each(function(i, e) {
                        str += `<tr>
                                    <td>${i+1}</td>
                                    <td>${e.caissier}</td>
                                    <td>${e.qte}</td>
                                    <td>${e.prix}</td>
                                    <td>${e.total}</td>
                                    <td>${e.date}</td>
                                </tr>`;
                    });
                    $(total).each(function(i, e) {
                        str2 += `<h3 class="font-weight-bold">${e.montant }</h4>`;
                    });
                    $('#d-vente').html(str2);
                    if (ventes.length > 0) {
                        $('#z-vente').slideDown();
                    } else {
                        $('#z-vente').slideUp();
                    }
                    table.find('tbody').html(
                        '<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>'
                    );
                    table.DataTable().destroy();
                    if (str.length > 0) {
                        table.find('tbody').html(str);
                        table.DataTable(opt);
                    } else {
                        str =
                            '<tr><td colspan="6" class="text-danger font-weight-bolder text-center">Aucune vente</td></tr>';
                        table.find('tbody').html(str);
                    }

                })
            }

            var cb = $('#checkbox13');
            cb.change(function() {
                if ($(this).is(':checked')) {
                    $('#e-zone2').slideDown();
                } else {
                    $('#e-zone2').slideUp();
                }
            })
            if (cb.is(':checked')) {
                $('#e-zone2').slideDown();
            } else {
                $('#e-zone2').slideUp();
            }

            $('#f-appro').submit(function() {
                event.preventDefault();
                var form = $(this);
                var btn = $(':submit', form).attr('disabled', true);
                btn.find('span').removeClass().addClass('spinner-border spinner-border-sm mr-3');
                rep = $('#rep', form);
                rep.removeClass().slideUp();
                var data = form.serialize();

                $.ajax({
                    url: '{{ route('articles.update', $article->id) }}',
                    type: 'put',
                    data: data,
                    timeout: 20000,
                }).done(function(res) {
                    data = res.data;
                    if (res.success == true) {
                        form.get(0).reset();
                        var m = res.message;
                        rep.addClass('alert alert-success w-100').html(m);
                        if (cb.is(':checked')) {
                            $('#e-zone2').slideDown();
                        } else {
                            $('#e-zone2').slideUp();
                        }
                        setTimeout(() => {
                            location.reload();
                        }, 5000);
                    } else {
                        var m = res.message;
                        try {
                            m += '<br>' + res.data.msg.join('<br>');
                        } catch (error) {}
                        rep.addClass('alert alert-danger w-100').html(m);
                    }
                    rep.slideDown();
                    setTimeout(() => {
                        rep.slideUp();
                    }, 5000);
                    btn.attr('disabled', false);
                    btn.find('span').removeClass();
                });
            })

            $('#f-del').submit(function() {
                event.preventDefault();
                var form = $(this);
                var btn = $(':submit', form).attr('disabled', true);
                btn.find('span').removeClass().addClass('spinner-border spinner-border-sm mr-3');
                rep = $('#rep', form);
                rep.removeClass().slideUp();

                var id = $('[name=id]').val();

                $.ajax({
                    url: '{{ route('articles.destroy', '') }}/' + id,
                    type: 'delete',
                    timeout: 20000,
                }).done(function(res) {
                    var m = res.message;
                    rep.addClass('alert alert-success w-100').html(m);
                    rep.slideDown();
                    setTimeout(() => {
                        location.assign('{{ route('articles.admin') }}');
                    }, 3000);
                });
            })


            $('#f-edit').submit(function() {
                event.preventDefault();
                var form = $(this);
                var btn = $(':submit', form).attr('disabled', true);
                btn.find('span').removeClass().addClass('spinner-border spinner-border-sm mr-3');
                rep = $('#rep', form);
                rep.removeClass().slideUp();
                var data = form.serialize();

                $.ajax({
                    url: '{{ route('articles.update', $article->id) }}',
                    type: 'put',
                    data: data,
                    timeout: 20000,
                }).done(function(res) {
                    data = res.data;
                    if (res.success == true) {
                        var m = res.message;
                        rep.addClass('alert alert-success w-100').html(m);
                        setTimeout(() => {
                            location.reload();
                        }, 5000);
                    } else {
                        var m = res.message;
                        try {
                            m += '<br>' + res.data.msg.join('<br>');
                        } catch (error) {}
                        rep.addClass('alert alert-danger w-100').html(m);
                    }
                    rep.slideDown();
                    setTimeout(() => {
                        rep.slideUp();
                    }, 5000);
                    btn.attr('disabled', false);
                    btn.find('span').removeClass();
                });
            })

        })
    </script>


@endsection

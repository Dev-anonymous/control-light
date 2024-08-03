@extends('layouts.main')
@section('title', 'Ventes magasin')

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
            $groupe = \App\Models\GroupeArticle::where('compte_id', compte_id())->get();
        @endphp
        <div class="main-content">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="h4 font-weight-bold">Inventaire général des ventes</h3>
                    <div class="card-header-action">
                        <a data-collapse="#mycard-collapse0" class="btn btn-outline-danger btn-icon"
                            style="border-radius: 10px!important" href="#">
                            <i class="fas fa-minus ielement"></i>
                        </a>
                    </div>
                </div>
                <div class="collapse show" id="mycard-collapse0">
                    <div class="card-header ">
                        <form id="f-change0">
                            <div class="row">
                                <div class="col-md form-group mr-1">
                                    <select class=" select2 custom-select groupe-change" name="groupe">
                                        <option value="">Tous les groupes</option>
                                        @foreach ($groupe as $e)
                                            <option @if ($e->par_defaut == 1) selected @endif
                                                value="{{ $e->id }}">
                                                {{ $e->groupe }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md form-group ml-1">
                                    <select class=" select2 custom-select cat-change change0" name="categorie" disabled>
                                        <option value="">Toutes les catégories</option>
                                    </select>
                                </div>
                                <div class="col-md form-group ml-1 mr-1">
                                    <input class="form-control change0 datepicker  p-3 rounded-sm" name="date"
                                        value="{{ date('Y-m-d') }}" style="padding: 20px !important; width:200px" />
                                </div>
                                <div class="col-md form-group ml-1">
                                    <select class=" select2 form-control change0" name="devise" style="width: 80px">
                                        <option value="">Toutes</option>
                                        <option>CDF</option>
                                        <option>USD</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="t-vente0"
                                        class="table table-condensed table-bordered table-hover font-weight-bold"
                                        style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Article</th>
                                                <th>Stock actuel</th>
                                                <th>Qté tot. vendue</th>
                                                <th>Prix</th>
                                                <th>Total Vente</th>
                                                <th>Marge bénéficiaire</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="bg-whitesmoke p-3" id="z-vente0" style="display: none">
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Montant total (MT)
                                        <button data-toggle="pop" title="Information"
                                            data-content="Ceci est le montant représentatif des ventes,
                                        il indique la somme vendue sans conversion de devise,
                                        c-a-d, si un article coute 20000 CDF et que le caissier a enregistré la facture de cet article en USD,
                                        le montant de cet artcle en CDF sera converti en USD sur la facture,
                                        donc cet article sera listé ici avec sa vraie devise de CDF mais dans le tableau des factures en bas,
                                        la facture aura le montant correspondant en USD. A la fin de journée après enregistrement de vos ventes,
                                        si vous convertissez en USD par exemple le Montant total(A) de la sorte => (USD->USD + CDF->USD) vous trouverez un montant X USD,
                                        et si vous convertissez encore en USD le Montant total(B) de la sorte => (USD->USD + CDF->USD) vous trouverez un montant Y USD,
                                        et vous verrez que le montant X USD sera égale au montant Y USD"
                                            class="btn">
                                            <i class="fa fa-info-circle text-danger"></i>
                                        </button>
                                    </h5>
                                    <div class="" id="d-vente0"></div>
                                </div>
                                <div class="col-md-6 text-right">
                                    <h5>Marge bénéficiaire
                                        <button data-toggle="pop" title="Information"
                                            data-content="Ce montant est inclus dans le montant total(MT)" class="btn">
                                            <i class="fa fa-info-circle text-danger"></i>
                                        </button>
                                    </h5>
                                    <div class="" id="d-marge0"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card ">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="h4 font-weight-bold">Inventaire détaillé des ventes</h3>
                    <div class="card-header-action">
                        <a data-collapse="#mycard-collapse1" class="btn btn-outline-danger btn-icon"
                            style="border-radius: 10px!important" href="#">
                            <i class="fas fa-minus ielement"></i>
                        </a>
                    </div>
                </div>
                <div class="collapse show" id="mycard-collapse1">
                    <div class="card-header">
                        <form id="f-change">
                            <div class="row">
                                <div class="col-md form-group d-block mr-1">
                                    <select class="select2 custom-select groupe-change" name="groupe">
                                        <option value="">Tous les groupes</option>
                                        @foreach ($groupe as $e)
                                            <option @if ($e->par_defaut == 1) selected @endif
                                                value="{{ $e->id }}">
                                                {{ $e->groupe }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md form-group d-block ml-1">
                                    <select class="select2 custom-select cat-change change" name="categorie" disabled>
                                        <option value="">Toutes les catégories</option>
                                    </select>
                                </div>
                                <div class="col-md form-group ml-1 mr-1">
                                    <input class="form-control change datepicker p-3 rounded-sm" name="date"
                                        value="{{ date('Y-m-d') }}" style="padding: 20px !important; width:200px" />
                                </div>
                                <div class="col-md form-group ml-1">
                                    <select class="select2 form-control change rounded-0 p-0" name="devise"
                                        style="width: 80px">
                                        <option value="">Toutes</option>
                                        <option>CDF</option>
                                        <option>USD</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="t-vente"
                                        class="table table-condensed table-bordered table-hover font-weight-bold"
                                        style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Article</th>
                                                <th>Quantité vendue</th>
                                                <th>Prix</th>
                                                <th>Total Vente</th>
                                                <th>Marge bénéficiaire</th>
                                                <th>Caissier</th>
                                                <th>Date vente</th>
                                                <th></th>
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
                            <div class="row">
                                <div class="col-md-6">
                                    <h5>Montant total (A)
                                        <button data-toggle="pop" title="Information"
                                            data-content="Ceci est le montant représentatif des ventes,
                                        il indique la somme vendue sans conversion de devise,
                                        c-a-d, si un article coute 20000 CDF et que le caissier a enregistré la facture de cet article en USD,
                                        le montant de cet artcle en CDF sera converti en USD sur la facture,
                                        donc cet article sera listé ici avec sa vraie devise de CDF mais dans le tableau des factures en bas,
                                        la facture aura le montant correspondant en USD. A la fin de journée après enregistrement de vos ventes,
                                        si vous convertissez en USD par exemple le Montant total(A) de la sorte => (USD->USD + CDF->USD) vous trouverez un montant X USD,
                                        et si vous convertissez encore en USD le Montant total(B) de la sorte => (USD->USD + CDF->USD) vous trouverez un montant Y USD,
                                        et vous verrez que le montant X USD sera égale au montant Y USD"
                                            class="btn">
                                            <i class="fa fa-info-circle text-danger"></i>
                                        </button>
                                    </h5>
                                    <div class="" id="d-vente"></div>
                                </div>
                                <div class="col-md-6 text-right">
                                    <h5>Marge bénéficiaire
                                        <button data-toggle="pop" title="Information"
                                            data-content="Ce montant est inclus dans le montant total(MT)" class="btn">
                                            <i class="fa fa-info-circle text-danger"></i>
                                        </button>
                                    </h5>
                                    <div class="" id="d-marge1"></div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="card ">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="h4 font-weight-bold">Factures</h3>
                    <div class="card-header-action">
                        <a data-collapse="#mycard-collapse" class="btn btn-outline-danger btn-icon"
                            style="border-radius: 10px!important" href="#">
                            <i class="fas fa-minus"></i>
                        </a>
                    </div>
                </div>
                <div class="collapse show" id="mycard-collapse">
                    <div class="card-header" id="tab-fac">
                        <form id="f-change-2">
                            <div class="row">
                                @php
                                    $caissier = \App\Models\User::orderby('id')->where('compte_id', compte_id());
                                @endphp
                                <div class="col-md">
                                    <div class="form-group ml-1">
                                        <select class="select2 form-control rounded-0 p-0" name="caissier"
                                            style="width:200px">
                                            <option value="">Tous</option>
                                            @foreach ($caissier->get() as $e)
                                                <option cassier="{{ $e->name }}" value="{{ $e->id }}">
                                                    {{ $e->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-group ml-1 mr-1">
                                        <input class="form-control datepicker p-3 rounded-sm" name="date"
                                            value="{{ date('Y-m-d') }}" style="padding: 20px !important; width:200px" />
                                    </div>
                                </div>
                                <div class="col-md">
                                    <div class="form-group ml-1">
                                        <select class="select2 form-control rounded-0 p-0" name="devise"
                                            style="width: 80px">
                                            <option value="">Toutes</option>
                                            <option>CDF</option>
                                            <option>USD</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="t-facture"
                                        class="table table-condensed table-bordered table-hover font-weight-bold"
                                        style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>N° Facture</th>
                                                <th>Client</th>
                                                <th>Caissier</th>
                                                <th>Total</th>
                                                <th>Date</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="bg-whitesmoke p-3" id="z-vente-2" style="display: none">
                            <h5>Montant total (B)
                                <button data-toggle="pop" title="Information"
                                    data-content="Ceci est le montant de référence si vous voulez vérifier vos factures. Ce total représente la somme des totaux de chaque facture enregistrée en une devise spécifique."
                                    class="btn ">
                                    <i class="fa fa-info-circle text-danger"></i>
                                </button>
                            </h5>
                            <hr>
                            <div class="" id="d-vente-2"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="mdl-detail" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header bg-danger text-white font-weight-bold d-flex justify-content-between">
                    <b>Détails de la facture</b>
                    <span style="cursor: pointer" data-dismiss="modal">
                        <i class="fa fa-times-circle p-2 "></i>
                    </span>
                </div>
                <div class="modal-body" id="print-zone">

                </div>
                <div class="modal-footer">
                    <button class="btn btn-dark" data-dismiss="modal">
                        Fermer
                    </button>
                    <button class="btn btn-danger" btn-fac>
                        <span></span>
                        Imprimer la facture
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="mdl-del" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header bg-danger text-white font-weight-bold d-flex justify-content-between">
                    <b>Suppression de la facture</b>
                    <span style="cursor: pointer" data-dismiss="modal">
                        <i class="fa fa-times-circle p-2 "></i>
                    </span>
                </div>
                <div class="modal-body">
                    <h4 class="text-danger mb-3" del-title></h4>
                    <h6>
                        <i class="fa fa-exclamation-triangle text-danger"></i>
                        Si vous supprimez cette facture :
                    </h6>
                    <ul class="font-weight-bold">
                        <li>Le stock de chaque article sur cette facture sera réapprovisionné</li>
                        <li>Les articles présents sur cette facture seront supprimés de la liste des articles vendus</li>
                        <li>Le total des ventes sera déduit du montant de cette facture(<span mont-fac></span>)</li>
                    </ul>
                    <div id="del-rep"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-dark" data-dismiss="modal">
                        Annuler
                    </button>
                    <button class="btn btn-danger" btn-del-fac>
                        <span class="fa fa-trash"></span>
                        Supprimer
                    </button>
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

    <script src="{{ asset('assets/js/daterangepicker/moment.js') }}"></script>
    <script src="{{ asset('assets/js/daterangepicker/daterangepicker.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/js/daterangepicker/daterangepicker.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/print.css') }}" media="print">
    <script src="{{ asset('assets/js/printThis.js') }}"></script>

    @php
        $shop = \App\Models\Shop::where('compte_id', compte_id())->first();
    @endphp
    <script>
        $(function() {
            $('[data-toggle="pop"]').popover({
                trigger: 'hover'
            })
            $('.datepicker').daterangepicker({
                minYear: '{{ date('Y') }}',
                showDropdowns: true,
                locale: {
                    format: 'YYYY/MM/DD'
                },
                maxDate: "{{ now()->addDays(1)->format('Y-m-d') }}"
            });
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
            spin =
                `<tr><td class="text-center" colspan="9"><span class="spinner-border text-danger"></span></td></tr>`;
            spin2 =
                `<tr><td class="text-center" colspan="7"><span class="spinner-border text-danger"></span></td></tr>`;

            var table0 = $('#t-vente0');
            var table = $('#t-vente');
            var table2 = $('#t-facture');

            var fchange0 = $('#f-change0');
            var fchange = $('#f-change');

            $('.groupe-change').change(function() {
                var form = $(this).closest('form');
                $(":input", form).attr('disabled', true);
                getCategorie(form);
            });
            $('.change0').change(function() {
                getData0();
            });
            $('.change').change(function() {
                getData();
            });
            $('#f-change-2').change(function() {
                getData2();
            });
            $('#f-change0,#f-change,#f-change-2,.groupe-change').submit(function() {
                event.preventDefault();
            })

            function getCategorie(form_context = null) {
                if (!form_context) return;
                $.ajax({
                    url: '{{ route('categorie-article.index') }}',
                    data: {
                        groupe: $('.groupe-change', form_context).val()
                    },
                    timeout: 20000,
                }).done(function(res) {
                    data = res.data;
                    str = '<option value="">Toutes les catégories</option>';
                    $(data).each(function(i, e) {
                        str += `<option  value="${e.id}">${e.categorie}</option>`;
                    });
                    $('.cat-change', form_context).html(str).attr('disabled', false);
                    $(':input', form_context).attr('disabled', false);
                    if (form_context.attr('id') == 'f-change0') {
                        getData0();
                    } else {
                        getData();
                    }
                })
            }

            function getData() {
                table.find('tbody').html(spin);
                $('#z-vente').slideUp();
                $(':input', fchange).attr('disabled', false);
                var data = fchange.serialize();
                $(':input', fchange).attr('disabled', true);

                $.ajax({
                    url: '{{ route('ventes.index') }}',
                    data: data,
                    timeout: 20000,
                }).done(function(res) {
                    $(':input', fchange).attr('disabled', false);
                    var data = res.data;
                    var ventes = data.ventes;
                    var total = data.total;
                    var marge = data.marge;
                    var str = '';
                    var str2 = '';
                    var str3 = '';
                    $(ventes).each(function(i, e) {
                        if (e.id) {
                            var buto =
                                `<a href="{{ route('detail-article.admin', '') }}/${e.id}" class='btn text-muted' ><i class='fa fa-eye'></i> Détails</a>`;
                        } else {
                            var buto =
                                `<span class='text-danger' ><i class='fa fa-ban'></i> Article supprimé</span>`;
                        }
                        if (e.marge_result == 'solde') {
                            mcl = 'warning'
                        } else if (e.marge_result == 'perte') {
                            mcl = 'danger'
                        } else {
                            mcl = 'success'
                        }

                        str += `<tr>
                                    <td>${i+1}</td>
                                    <td title="${e.categorie_article}(${e.groupe})">${e.article}</td>
                                    <td>${e.qte}</td>
                                    <td class="text-nowrap">${e.prix}</td>
                                    <td><span class="badge badge-info font-weight-bold">${e.total}</span></td>
                                    <td><span class="badge badge-${mcl}">${e.marge}</span></td>
                                    <td>${e.caissier}</td>
                                    <td>${e.date}</td>
                                    <td class='d-flex justify-content-center'>${buto}</td>
                                </tr>`;
                    });
                    $(total).each(function(i, e) {
                        str2 += `<h3 class="font-weight-bold">${e.montant }</h4>`;
                    });
                    $(marge).each(function(i, e) {
                        str3 += `<h3 class="font-weight-bold">${e.cdf } <br>soit ${e.usd}</h4>`;
                    });
                    $('#d-vente').html(str2);
                    $('#d-marge1').html(str3);
                    if (ventes.length > 0) {
                        $('#z-vente').slideDown();
                    } else {
                        $('#z-vente').slideUp();
                    }
                    table.find('tbody').html('');
                    table.DataTable().destroy();
                    if (str.length > 0) {
                        table.find('tbody').html(str);
                        table.DataTable(opt);
                    } else {
                        str =
                            '<tr><td colspan="9" class="text-danger font-weight-bolder text-center">Aucune vente</td></tr>';
                        table.find('tbody').html(str);
                    }

                })
            }

            function getData0() {
                table0.find('tbody').html(spin);
                $('#z-vente0').slideUp();

                $(':input', fchange0).attr('disabled', false);
                var data = fchange0.serialize();
                $(':input', fchange0).attr('disabled', true);

                $.ajax({
                    url: '{{ route('ventes.index') }}',
                    data: data + "&groupall=true",
                    timeout: 20000,
                }).done(function(res) {
                    $(':input', fchange0).attr('disabled', false);
                    var data = res.data;
                    var ventes = data.ventes;
                    var total = data.total;
                    var marge = data.marge;
                    var str = '';
                    var str2 = '';
                    var str3 = '';
                    $(ventes).each(function(i, e) {
                        if (e.id) {
                            var buto =
                                `<a href="{{ route('detail-article.admin', '') }}/${e.id}" class='btn text-muted' ><i class='fa fa-eye'></i> Détails</a>`;
                        } else {
                            var buto =
                                `<span class='text-danger' ><i class='fa fa-ban'></i> Article supprimé</span>`;
                        }

                        if (e.marge_result == 'solde') {
                            mcl = 'warning'
                        } else if (e.marge_result == 'perte') {
                            mcl = 'danger'
                        } else {
                            mcl = 'success'
                        }
                        str += `<tr>
                                    <td>${i+1}</td>
                                    <td title="${e.categorie_article}(${e.groupe})">${e.article}</td>
                                    <td>${e.stock}</td>
                                    <td>${e.qte}</td>
                                    <td class="text-nowrap">${e.prix}</td>
                                    <td><span class="badge badge-info font-weight-bold">${e.total}</span></td>
                                    <td><span class="badge badge-${mcl}">${e.marge}</span></td>
                                    <td class='d-flex justify-content-center'>${buto}</td>
                                </tr>`;
                    });
                    $(total).each(function(i, e) {
                        str2 += `<h3 class="font-weight-bold">${e.montant }</h4>`;
                    });
                    $(marge).each(function(i, e) {
                        str3 += `<h3 class="font-weight-bold">${e.cdf } <br>soit ${e.usd}</h4>`;
                    });
                    $('#d-vente0').html(str2);
                    $('#d-marge0').html(str3);
                    if (ventes.length > 0) {
                        $('#z-vente0').slideDown();
                    } else {
                        $('#z-vente0').slideUp();
                    }
                    table0.find('tbody').html('');
                    table0.DataTable().destroy();
                    if (str.length > 0) {
                        table0.find('tbody').html(str);
                        table0.DataTable(opt);
                    } else {
                        str =
                            '<tr><td colspan="9" class="text-danger font-weight-bolder text-center">Aucune vente</td></tr>';
                        table0.find('tbody').html(str);
                    }
                })
            }

            function getData2() {
                table2.find('tbody').html(spin2);
                $('#z-vente-2').slideUp();

                $.ajax({
                    url: '{{ route('factures.index') }}',
                    data: $('#f-change-2').serialize(),
                    timeout: 20000,
                }).done(function(res) {
                    var data = res.data;
                    var factures = data.factures;
                    var total = data.total;
                    var str = '';
                    var str2 = '';
                    $(factures).each(function(i, e) {
                        var buto =
                            str += `<tr>
                                    <td>${i+1}</td>
                                    <td >${e.numero_facture}</td>
                                    <td >${e.client}</td>
                                    <td>${e.caissier}</td>
                                    <td class="text-nowrap">${e.total}</td>
                                    <td>${e.date}</td>
                                    <td class='d-flex justify-content-center'>
                                        <button value='${e.id}' class='btn text-muted detail' ><i class='fa fa-eye'></i> Détails</button>
                                        @if (in_array(auth()->user()->user_role, ['admin']))
                                            <button value='${e.id}' mont-fac='${e.total}' facture="Voulez-vous vraiment supprimer la facture N° ${e.numero_facture} enregistrée à la date ${e.date} ?" class='btn text-muted del-fact ml-2'><i class='fa fa-trash'></i> Supprimer</button>
                                        @endif
                                    </td>
                                </tr>`;
                    });
                    $(total).each(function(i, e) {
                        str2 += `<h3 class="font-weight-bold">${e.montant }</h4>`;
                    });
                    $('#d-vente-2').html(str2);
                    if (factures.length > 0) {
                        $('#z-vente-2').slideDown();
                    } else {
                        $('#z-vente-2').slideUp();
                    }
                    table2.find('tbody').html(
                        '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>'
                    );
                    table2.DataTable().destroy();
                    if (str.length > 0) {
                        table2.find('tbody').html(str);
                        init();
                        var tabl = table2.DataTable(opt);
                        var nf = '{{ request()->nf }}';
                        if (nf.length > 0) {
                            $('[aria-controls="t-facture"]').val(nf).trigger($.Event('keyup', {
                                keyCode: 13
                            }));
                            $('.ielement').each(function(i, e) {
                                $(this).removeClass('fa-minus').addClass('fa-plus').closest('.card')
                                    .find('.collapse').removeClass('show');
                            })
                            setTimeout(() => {
                                $('html, body').animate({
                                    scrollTop: $('#tab-fac').offset().top
                                }, 1000);
                            }, 3000);
                        }
                    } else {
                        str =
                            '<tr><td colspan="7" class="text-danger font-weight-bolder text-center">Aucune facture</td></tr>';
                        table2.find('tbody').html(str);
                    }

                })
            }

            $('[btn-fac]').click(function() {
                var div = $('#print-zone')[0];
                var mywindow = window.open('', 'PRINT', 'height=500,width=800');
                mywindow.document.write(
                    '<html><head><style>td,th{padding:5px;} html,body {h1,h2,h3,h4,h5,h6 {padding: 0px;margin: 0px;},font-size: 12pt !important; font-weight:bold; margin-top:5px !important; margin-bottom:10px !important;}</style>'
                );
                mywindow.document.write('</head><body >');
                mywindow.document.write(div.innerHTML);
                mywindow.document.write(
                    "<div style='margin-bottom:10px; margin-top:10px;'>Merci d'etre passé! A bientot.</div>"
                );
                mywindow.document.write('</body></html>');
                mywindow.document.close();
                mywindow.focus();
                mywindow.print();
                mywindow.close();
                // $('#print-zone').printThis({
                //     footer: "<div style='margin:2rem;'>Merci d'etre passé! A bientot.</div>",
                // });
            })
            mdl = $('#mdl-detail');

            function init() {
                $('.detail').off('click').click(function() {
                    var id = this.value;
                    mdl.find('.modal-body').html(
                        '<div class="d-flex justify-content-center"><span class="spinner-border text-danger"></span></div>'
                    );
                    $("[btn-fac]", mdl).attr('disabled', true);
                    mdl.modal();
                    $.ajax({
                        url: '{{ route('factures.show', '') }}/' + id,
                        timeout: 20000,
                    }).done(function(res) {
                        var data = res.data;
                        var facture = data.facture;
                        var articles = data.articles;
                        var str = `
                            <div style="margin-bottom:10px;">
                                <div style="text-align: center; margin-bottom:10px;">
                                    <span>{{ @$shop->shop }}</span><br>
                                    <span>Addresse: {{ @$shop->adresse }}</span><br>
                                    <span>Tel : {{ @$shop->contact }}</span><br>
                                    <span>RCCM : {{ @$shop->rccm }}</span><br>
                                    <span>IDNAT : {{ @$shop->idnat }}</span><br>
                                </div>
                                <div style="text-align: center; margin-bottom:10px;">
                                    <span style="font-size:20px"># COPIE FACTURE #</span><br>
                                </div>
                                <div>
                                    <span>N° facture : ${facture.numero_facture}</span>
                                </div>
                                <div>
                                    <span>Client : ${facture.client}</span>
                                </div>
                                <div>
                                    <span>Caissier : ${facture.caissier}</span>
                                </div>
                                <div>
                                    <span>Date : ${facture.date}</span>
                                </div>
                            </div>
                            <table style="width:100%">
                                <thead>
                                    <th></th>
                                    <th>Article</th>
                                    <th>Qte</th>
                                    <th style="text-align: right">Prix</th>
                                </thead>
                                <tbody>
                                    `;

                        var str2 = '';
                        $(articles).each(function(i, e) {
                            var buto =
                                str2 += `<tr>
                                    <td>${i+1}</td>
                                    <td >${e.article}</td>
                                    <td >${e.qte}</td>
                                    <td style="text-align: right">${e.prix}</td>
                                </tr>`;
                        });
                        str2 += `<tr>
                                    <td colspan='4' style="text-align: right; font-weight:bold; margin-top:10px;" >Total payé : ${facture.total}</td>
                                </tr>`
                        str += str2;
                        str += `</tbody></table>`;
                        mdl.find('.modal-body').html(str);
                        $("[btn-fac]", mdl).attr('disabled', false);

                    })
                })

                $('.del-fact').off('click').click(function() {
                    var idfac = this.value;
                    var btndel = $('[btn-del-fac]');
                    var mdl = $('#mdl-del')
                    mdl.modal();

                    $('[del-title]').html($(this).attr('facture'));
                    $('span[mont-fac]').html($(this).attr('mont-fac'));

                    var rep = $('#del-rep', mdl);
                    rep.slideUp().removeClass();
                    btndel.attr('disabled', false);

                    btndel.off('click').on('click', function() {
                        btndel.attr('disabled', true);
                        $.ajax({
                            url: '{{ route('factures.destroy', '') }}/' + idfac,
                            timeout: 20000,
                            type: 'DELETE'
                        }).done(function(res) {
                            rep.html('Facture supprimée');
                            rep.addClass('alert alert-success')
                                .slideDown();
                            setTimeout(() => {
                                mdl.modal('hide');
                            }, 3000);
                            getData2();
                        })
                    })
                })
            }

            getData2();
            getCategorie($('#f-change'));
            getCategorie($('#f-change0'));

        })
    </script>


@endsection

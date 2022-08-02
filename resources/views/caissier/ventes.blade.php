@extends('layouts.main')
@section('title', 'Ventes articles')

@section('body')
    <div class="loader"></div>
    <div>
        <div class="main-wrapper main-wrapper-1">
            @include('composants.nav')
            <div class="main-sidebar sidebar-style-2">
                @include('composants.sidebar-caissier')
            </div>
        </div>
        @php
            $groupe = \App\Models\GroupeArticle::where('compte_id', compte_id())->get();
        @endphp
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
                <div class="card-header">
                    <h4>Nouvelle facture</h4>
                    <div class="card-header-action">
                        <div class="d-flex">
                            <div class="form-group mr-1">
                                <select class="form-control rounded-0 p-0" id="devise-fac">
                                    <option>CDF</option>
                                    <option>USD</option>
                                </select>
                            </div>
                            <div class="form-group ml-1 mr-1">
                                <button class="btn btn-danger btn-visualiser" style="border-radius: 5px!important;">
                                    <i class="fa fa-eye"></i> visualiser
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-inline">
                                <b class="mr-3" for="">Client : </b>
                                <input name="client" class="form-control" placeholder="Nom du client">
                            </div>
                            <div class="form-inline">
                                <b class="mr-3 mt-3" for="">Devise de la facture : <b id="label-dev" for=""></b></b>
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <h6>Articles achetés</h6>
                            <div class="table-responsive">
                                <table class="table table-condensed table-bordered table-hover font-weight-bold"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>Article</th>
                                            <th>Prix de vente</th>
                                            <th>Réduction</th>
                                            <th>Qté</th>
                                            <th>Total</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody id="liste-article"></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="bg-whitesmoke p-3">
                        <h5>Net à payer</h5>
                        <hr>
                        <div class="" id="d-montant"></div>
                    </div>
                    <div class="bg-whitesmoke p-3 mt-3">
                        <h5>Taux</h5>
                        <hr>
                        <div>
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
                            <h6>1 CDF = {{ $txcdf }} USD</h6>
                            <h6>1 USD = {{ $txusd }} CDF</h6>
                        </div>
                    </div>
                    <div class="p-3">
                        <button id="btn-socket" class="btn btn-outline-danger" data-toggle="modal" data-target="#mdl-app">
                            <i class="fa fa-wifi"></i>
                            Coupler un appareil
                        </button>
                    </div>
                </div>
            </div>
            <div class="card ">
                <div class="card-header">
                    <h4>Articles</h4>
                    <div class="card-header-action">
                        <a data-collapse="#mycard-collapse" class="btn btn-outline-danger btn-icon"
                            style="border-radius: 10px!important" href="#">
                            <i class="fas fa-minus"></i>
                        </a>
                    </div>
                </div>
                <div class="collapse show" id="mycard-collapse">
                    <div class="card-header d-flex justify-content-center">
                        <div class="form-group d-block mr-1">
                            <select class="custom-select groupe-change">
                                <option value="">Tous les groupes d'articles</option>
                                @foreach ($groupe as $e)
                                    <option @if ($e->par_defaut == 1) selected @endif value="{{ $e->id }}">
                                        {{ $e->groupe }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group d-block ml-1">
                            <select class="custom-select cat-change" disabled>
                                <option value="">Toutes les catégories d'articles</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="t-data"
                                        class="table table-condensed table-bordered table-hover font-weight-bold"
                                        style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Article</th>
                                                <th>Prix de vente/Unité de mesure</th>
                                                <th>Réduction</th>
                                                <th>Qté Stock</th>
                                                <th>Code article</th>
                                                <th>Catégorie</th>
                                                <th>Date expiration</th>
                                                <th>#</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center" colspan="8">
                                                    <span class="spinner-border text-danger"></span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card ">
                <div class="card-header">
                    <h4>Mes factures enregistrées</h4>
                    <div class="card-header-action">
                        <form id="f-change-2">
                            <input type="hidden" name="filtre" value="true">
                            <div class="d-flex">
                                <div class="form-group ml-1 mr-1">
                                    <input class="form-control datepicker p-3 rounded-sm" name="date"
                                        value="{{ date('Y-m-d') }}" />
                                </div>
                                <div class="form-group ml-1">
                                    <select class="form-control rounded-0 p-0" name="devise">
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
                        <h5>Montant total</h5>
                        <hr>
                        <div class="" id="d-vente-2"></div>
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
                <div class="modal-body" id="print-zone-2">

                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">
                        Fermer
                    </button>
                    <button class="btn btn-danger " btn-fac>
                        <span class="fa fa-print"></span>
                        Imprimer la facture
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="mdl-visualise" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog  modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white font-weight-bold d-flex justify-content-between">
                    <b>Visualisation de la facture</b>
                    <span style="cursor: pointer" data-dismiss="modal">
                        <i class="fa fa-times-circle p-2 "></i>
                    </span>
                </div>
                <div class="modal-body" id="print-zone">

                </div>
                <div id="fac-rep"></div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">
                        Fermer
                    </button>
                    <button class="btn btn-danger " btn-fac2>
                        <span class="fa fa-print"></span>
                        Enregistrer & imprimer la facture
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="mdl-app" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white font-weight-bold d-flex justify-content-between">
                    <b>Couplage Application</b>
                    <span style="cursor: pointer" data-dismiss="modal">
                        <i class="fa fa-times-circle p-2 "></i>
                    </span>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 d-flex justify-content-center">
                            <div id="user-qr"></div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <p>
                                <i class="fa fa-info-circle text-danger"></i>
                                A l'aide de l'application mobile, scannez ce QRcode
                                pour coupler votre application avec votre compte, vous pouvez ainsi
                                scanner les codes barres des articles directement dépuis l'application.
                            </p>
                        </div>
                    </div>
                </div>
                <div id="fac-rep"></div>
                <div class="modal-footer">
                    <a href="{{ asset('control.apk') }}" class="btn btn-danger" type="button">
                        <i class="fa fa-download"></i>
                        Télécharger l'application
                    </a>
                    <button class="btn btn-secondary" data-dismiss="modal">
                        Fermer
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

    <script src="{{ asset('assets/phone/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('assets/js/qrcode.min.js') }}"></script>

    <script src="{{ asset('assets/js/jquery.inputmask.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('assets/css/print.css') }}" media="print">
    <script src="{{ asset('assets/js/printThis.js') }}"></script>
    @php
    $shop = \App\Models\Shop::where('compte_id', compte_id())->first();
    @endphp

    <script src="http://localhost:3000/socket.io/socket.io.js"></script>

    <script>
        $(function() {
            var qrcode = new QRCode(document.getElementById("user-qr"), {
                width: 200,
                height: 200
            });
            var qr = '{{ base64_encode(auth()->user()->id) }}';
            qrcode.makeCode((qr));
            $('.datepicker').daterangepicker({
                minYear: 2022,
                showDropdowns: true,
                locale: {
                    format: 'YYYY/MM/DD'
                },
                maxDate: "{{ date('Y-m-d') }}"
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

            cancelFunc = undefined;

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
            spin =
                `<tr><td class="text-center" colspan="9"><span class="spinner-border text-danger"></span></td></tr>`;
            spin2 =
                `<tr><td class="text-center" colspan="7"><span class="spinner-border text-danger"></span></td></tr>`;

            var table = $('#t-data');
            var table2 = $('#t-facture');

            var groupchange = $('.groupe-change');
            var catechange = $('.cat-change');
            groupchange.change(function() {
                $(this).attr('disabled', true);
                catechange.attr('disabled', true);
                getCategorie();
            })

            catechange.change(function() {
                $(this).attr('disabled', true);
                groupchange.attr('disabled', true);
                getData();
            })

            $('#f-change-2').change(function() {
                getData2();
            });

            function getCategorie() {
                $.ajax({
                    url: '{{ route('categorie-article.index') }}',
                    data: {
                        groupe: groupchange.val()
                    },
                    timeout: 20000,
                }).done(function(res) {
                    data = res.data;
                    str = '<option value="">Toutes les catégories d\'articles</option>';
                    $(data).each(function(i, e) {
                        str += `<option  value="${e.id}">${e.categorie}</option>`;
                    });
                    catechange.html(str).attr('disabled', false);
                    groupchange.attr('disabled', false);
                    getData();
                })
            }
            getCategorie();

            function getData() {
                table.find('tbody').html(spin);
                $.ajax({
                    url: '{{ route('articles.index') }}',
                    data: {
                        categorie: catechange.val(),
                        filtre: true
                    },
                    timeout: 20000,
                }).done(function(res) {
                    var data = res.data;
                    var str = '';
                    var show = false;
                    $(data).each(function(i, e) {
                        var art = e.article;
                        if (art.length > 50) {
                            art = art.substring(0, 50) + ' ...';
                        }
                        var stCl = '',
                            stTi = '';

                        if (e.stock <= 10) {
                            stCl = 'bg-warning';
                            stTi = 'Pensez à réapprovisionner ce stock';
                        }

                        var newFact = JSON.stringify({
                            id: e.id,
                            article: e.article,
                            prix: e.prix,
                            reduction: e.reduction,
                            prix_min: e.prix_min,
                            pv: e.prix_min
                        });

                        var red = '',
                            redt = '';
                        if (Number(e.reduction) > 0) {
                            red = "<span class='badge badge-danger'>" + e.reduction + '%</span>';
                            red += '<br>' + e.prix_min + ' - ' + e.prix;
                            redt = "Le prix de vente va varier entre " + e.prix_min;
                            redt += " et " + e.prix + ' lors de la vente';
                        }

                        str += `<tr>
                                    <td>${i+1}</td>
                                    <td title="${e.article}">${art}</td>
                                    <td title="Prix de vente : ${e.prix} Par ${e.unite_mesure}">${e.prix}</td>
                                    <td class="text-center" title="${redt}">${red}</td>
                                    <td class="${stCl}" title="${stTi}">${e.stock} ${e.unite_mesure}</td>
                                    <td>${e.code}</td>
                                    <td>${e.categorie}</td>
                                    <td>${e.date_expiration}</td>
                                    <td class='d-flex justify-content-center'>
                                        <button value='${newFact}' class='btn btn-danger new-fac' ><i class='fa fa-plus-circle'></i> Ajouter à la facture</button>
                                    </td>
                                </tr>`;
                    });
                    catechange.attr('disabled', false);
                    groupchange.attr('disabled', false);
                    table.find('tbody').html(
                        '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>'
                    );
                    table.DataTable().destroy();
                    if (str.length > 0) {
                        table.find('tbody').html(str);
                        init2();
                        table.DataTable(opt);
                    } else {
                        str =
                            '<tr><td colspan="8" class="text-danger font-weight-bolder text-center">Aucun article</td></tr>';
                        table.find('tbody').html(str);
                    }

                    if (show == true) {
                        $('#card-info').slideDown();
                    } else {
                        $('#card-info').slideUp();
                    }

                })
            }

            getData2();

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
                        str += `<tr>
                                    <td>${i+1}</td>
                                    <td >${e.numero_facture}</td>
                                    <td >${e.client}</td>
                                    <td>${e.caissier}</td>
                                    <td>${e.total}</td>
                                    <td>${e.date}</td>
                                    <td class='d-flex justify-content-center'>
                                        <button value='${e.id}' class='btn text-muted detail' ><i class='fa fa-eye'></i> Détails</button>
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
                        table2.DataTable(opt);
                    } else {
                        str =
                            '<tr><td colspan="7" class="text-danger font-weight-bolder text-center">Aucune facture</td></tr>';
                        table2.find('tbody').html(str);
                    }
                })
            }

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
                        var str =
                            `<div class="mb-2">
                                <div style="text-align: center">
                                    <h4>{{ @$shop->shop }}</h4>
                                    <h5>{{ @$shop->adresse }}</h5>
                                    <h6>{{ @$shop->contact }}</h6>
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
            }

            $('[btn-fac]').click(function() {
                $('#print-zone-2').printThis({
                    importStyle: false,
                    footer: "<div style='margin:2rem;'>Merci d'etre passé! A bientot.</div>",
                });
            })

            var listarticle = $('#liste-article');

            function init2() {
                $('.new-fac').off('click').click(function() {
                    var data = JSON.parse(this.value);

                    var tr = $('[tr-item=' + data.id + ']');
                    var iqte = $('[item-qte-' + data.id + ']', tr);
                    var qte = Number(iqte.val());
                    if (qte) {
                        qte += 1;
                    } else {
                        qte = 1;
                    };
                    iqte.val(qte);
                    data.qte = qte;
                    saveItem(data);
                    insert(data, true);
                    addNum();
                    initActions();
                });
            }

            function addNum() {
                var trs = $('#liste-article').children();
                trs.each(function(i, e) {
                    var td = $(':first-child', e)[0];
                    $(td).html(i + 1)
                });
            }

            function getItems() {
                var data = localStorage.getItem('items');
                tab = JSON.parse(data);
                return tab;
            }

            function saveItem(item) {
                var items = localStorage.getItem('items');
                var tab = [];
                if (items) {
                    tab = JSON.parse(items);
                    let t = [];
                    let find = false;
                    $(tab).each(function(i, e) {
                        if (e.id == item.id) {
                            e.qte = e.qte + 1;
                            find = true;
                        }
                        t.push(e);
                    });
                    if (!find) {
                        item.qte = 1;
                        t.push(item);
                    }
                    tab = t;
                } else {
                    item.qte = 1;
                    tab.push(item);
                }
                localStorage.setItem('items', JSON.stringify(tab));
                getTotal();
            }

            function removeItem(id) {
                var items = localStorage.getItem('items');
                var tab = [];
                if (items) {
                    tab = JSON.parse(items);
                    let t = [];
                    $(tab).each(function(i, e) {
                        if (e.id != id) {
                            t.push(e);
                        }
                    });
                    tab = t;
                    localStorage.setItem('items', JSON.stringify(tab));
                    getTotal();
                }
            }

            function clearItems() {
                localStorage.setItem('items', JSON.stringify([]));
            }

            function updateItem(id, qte, pv) {
                var items = localStorage.getItem('items');
                var tab = [];
                if (items) {
                    tab = JSON.parse(items);
                    let t = [];
                    $(tab).each(function(i, e) {
                        if (e.id == id) {
                            e.qte = qte;
                            if (pv) {
                                e.pv = pv;
                            }
                        }
                        t.push(e);
                    });
                    tab = t;
                    localStorage.setItem('items', JSON.stringify(tab));
                    getTotal();
                }
            }

            restore();

            function restore() {
                var data = getItems();
                $('#liste-article').html('');
                $(data).each(function(i, e) {
                    insert(e);
                });
                $("input[item-qte]").off('mask').mask('0000000');
                initActions();
                addNum();
            }

            function insert(data, blink = false) {
                var dev = (data.prix.split(' ')).slice(-1)[0];
                var pr = Number(data.prix.replace(`${dev}`, '').split(' ').join(''));
                var pv = Number(data.pv.toString().replace(` ${dev}`, '').split(' ').join(''));
                var prMin = Number(data.prix_min.toString().replace(`${dev}`, '').split(' ').join(''));
                var reduction = Number(data.reduction);

                var tr = $('[tr-item=' + data.id + ']');
                var tot = data.qte * pv;
                tot = tot.toLocaleString('fr-FR', {
                    minimumFractionDigits: 2
                });
                tot = tot.replace(',', '.');
                var tit = '';
                if (reduction > 0) {
                    tit = "Le prix de vente doit etre >= " + data.prix_min + " et <= " + data.prix;
                }
                if (tr.length) {
                    $('[item-tot-' + data.id + ']', tr).html(tot + ' ' + dev).show();
                } else {
                    var art = `<tr tr-item='${data.id}'>
                                    <td></td>
                                    <td item-name-${data.id}>${data.article}</td>
                                    <td item-prix-${data.id}>${data.prix}</td>
                                    <td title="${tit}">
                                       ${reduction > 0 ? '<i class="text-danger">[-'+ reduction+ '%] ' + data.prix_min+' - '+ data.prix+ '</i>' :''}
                                        <input ${reduction == 0 ? 'disabled':''} item-reduction="${data.id}" reduction="${reduction}" dev="${dev}" prix="${pr}" style="max-width: 200px"
                                            class="form-control reduction" value="${pv}" min-val="${prMin}" max-val="${pr}" placeholder="Prix réduction">
                                        </td>
                                    <td>
                                        <input dev="${dev}" prix="${prMin}" item-qte='${data.id}' item-qte-${data.id} style="max-width: 100px"
                                            class="form-control" value='${data.qte}' placeholder="Quantite">
                                    </td>
                                    <td item-tot-${data.id}>${tot} ${dev}</td>
                                    <td class='d-flex justify-content-center'>
                                        <button value='${data.id}' class='btn remove' ><i class='fa fa-times-circle text-danger'></i></button>
                                    </td>
                                </tr>`;
                    listarticle.append(art).show();
                    tr = $('[tr-item=' + data.id + ']');
                }
                if (blink) {
                    $(tr).Blink();
                }
            }

            function checkItems() {
                var table = $('#liste-article').closest('.table');
                table.addClass('table-danger');
                $('button,input', table).attr('disabled', true);
                var data = getItems();
                var cinfo = $('#card-info');

                $.ajax({
                    url: '{{ route('check-items.api') }}',
                    data: {
                        items: JSON.stringify(getItems())
                    },
                    timeout: 20000,
                }).done(function(res) {
                    var data = res.data;
                    localStorage.setItem('items', JSON.stringify(data));
                    $('#liste-article').children().remove();
                    restore();
                    table.Blink();
                    setTimeout(() => {
                        cinfo.slideUp();
                        table.removeClass('table-danger');
                        $('button,input', table).attr('disabled', false);
                    }, 2000);

                }).fail(function(res) {
                    location.reload();
                })

            }

            function initActions() {
                $("input[item-qte]").off('mask').mask('0000000');
                $('input[item-qte]').off('keyup change').on('change keyup', function() {
                    var id = $(this).attr('item-qte');
                    var dev = $(this).attr('dev');
                    var prix = $('[item-reduction=' + id + ']').val();
                    var qte = this.value;
                    if (qte.toString().length > 0) {
                        qte = Number(qte);
                        if (!qte) {
                            $(this).val(1);
                            qte = 1;
                        }
                    } else {
                        return;
                    }

                    var tot = qte * prix;
                    tot = tot.toLocaleString('fr-FR', {
                        minimumFractionDigits: 2
                    });
                    tot = tot.replace(',', '.');
                    $('[item-tot-' + id + ']').html(tot + ' ' + dev);
                    updateItem(id, qte);
                });
                $('input[item-reduction]').off('keyup change').on('change keyup', function() {
                    var id = $(this).attr('item-reduction');
                    var dev = $(this).attr('dev');
                    var qte = Number($('[item-qte=' + id + ']').val());
                    var prix = Number(this.value);
                    if (!qte || !prix) return

                    var min = Number($(this).attr('min-val'));
                    var max = Number($(this).attr('max-val'));

                    if (prix < min || prix > max) return;


                    var tot = qte * prix;
                    tot = tot.toLocaleString('fr-FR', {
                        minimumFractionDigits: 2
                    });
                    tot = tot.replace(',', '.');
                    $('[item-tot-' + id + ']').html(tot + ' ' + dev);
                    updateItem(id, qte, prix);
                });
                $('.remove').off('click').click(function() {
                    var id = this.value;
                    removeItem(id);
                    $('tr[tr-item=' + id + ']').addClass('bg-danger').fadeOut(function() {
                        $(this).remove();
                        addNum();
                    });
                })

                $('.reduction').each(function(i, e) {
                    $(e).inputmask('remove');
                    $(e).inputmask('decimal', {
                        min: $(e).attr('min-val'),
                        max: $(e).attr('max-val'),
                        digits: 2,
                        rightAlign: false
                    });
                })
            }

            $('input[name=client]').change(function() {
                localStorage.setItem('client', this.value);
            });
            $('input[name=client]').val(localStorage.getItem('client'));

            $('#devise-fac').change(function() {
                localStorage.setItem('_devise', this.value);
                $('#label-dev').html(this.value);
                getTotal();
            });
            $('#devise-fac').val(localStorage.getItem('_devise') ?? 'CDF');
            $('#label-dev').html(localStorage.getItem('_devise') ?? 'CDF');


            var dmontant = $('#d-montant');
            getTotal();

            function getTotal() {
                dmontant.html('<span class=\'spinner-border text-danger \'></span>');
                $.ajax({
                    url: '{{ route('netApayer.api') }}',
                    data: {
                        items: JSON.stringify(getItems()),
                        devise: $('#devise-fac').val()
                    },
                    timeout: 20000,
                }).done(function(res) {
                    var data = res.data;
                    if (res.success == true) {
                        var m = data.total;
                        dmontant.html("<h3 class='class=font-weight-bold'>" + m + "</h3>");
                    } else {
                        var m = res.message;
                        dmontant.html("<i class='text-danger '>" + m + "</i>");
                    }
                }).fail(function(res) {
                    dmontant.html("<i class='text-danger '>Une erreur s'est produite</i>");
                })

            }

            mdlvisualise = $('#mdl-visualise');
            $('.btn-visualiser').click(function() {
                mdlvisualise.find('.modal-body').html(
                    '<div class="d-flex justify-content-center"><span class="spinner-border text-danger"></span></div>'
                );
                $("[btn-fac2]", mdlvisualise).attr('disabled', true);
                var cinfo = $('#card-info');
                cinfo.slideUp();
                $('#fac-rep').fadeOut();
                mdlvisualise.modal();

                $.ajax({
                    url: '{{ route('afficher-facture.api') }}',
                    data: {
                        items: JSON.stringify(getItems()),
                        devise: $('#devise-fac').val()
                    },
                    timeout: 20000,
                }).done(function(res) {
                    var data = res.data;
                    if (res.success == true) {
                        var facture = data.facture;
                        var articles = data.articles;
                        var client = localStorage.getItem('client') ?? '-';
                        var str = `
                            <div class="mb-2">
                                <div style="text-align: center">
                                    <h4>{{ @$shop->shop }}</h4>
                                    <h5>{{ @$shop->adresse }}</h5>
                                    <h6>{{ @$shop->contact }}</h6>
                                </div>
                                <div>
                                    <span>N° facture : <span numero-facture>-</span></span>
                                </div>
                                <div>
                                    <span>Client : ${client}</span>
                                </div>
                                <div>
                                    <span>Caissier : ${facture.caissier}</span>
                                </div>
                                <div>
                                    <span>Date : <span date-facture>-</span></span>
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
                        mdlvisualise.find('.modal-body').html(str);
                        $("[btn-fac2]", mdlvisualise).attr('disabled', false);

                    } else {
                        mdlvisualise.modal('hide');
                        cinfo.find('[msg]').html(
                            '<i class="fa fa-exclamation-triangle text-danger"></i>' + res
                            .message +
                            '<span class="spinner-border ml-3 text-danger"></span>');
                        cinfo.slideDown();
                        checkItems();
                    }

                }).fail(function(res) {
                    cinfo.find('[msg]').html(
                        "<i class=\"fa fa-exclamation-triangle text-danger\"></i> Erreur de visualisation, merci d'actualisez cette page"
                    );
                    cinfo.slideDown();
                    mdlvisualise.modal('hide');
                })
            })


            $('[btn-fac2]').click(function() {
                event.preventDefault();
                var btn = $(this).attr('disabled', true);
                btn.find('span').removeClass().addClass('spinner-border spinner-border-sm mr-3');
                rep = $('#fac-rep');
                rep.removeClass().slideUp();
                var client = localStorage.getItem('client');

                $.ajax({
                    url: '{{ route('nouvelle-facture.api') }}',
                    type: 'post',
                    data: {
                        items: JSON.stringify(getItems()),
                        devise: $('#devise-fac').val(),
                        client: client
                    },
                    timeout: 20000,
                }).done(function(res) {
                    data = res.data;
                    if (res.success == true) {
                        var m = res.message;
                        rep.addClass('alert alert-success mr-4 ml-4').html(m);
                        $('span[numero-facture]').html(data.numero_facture);
                        $('span[date-facture]').html(data.date);
                        clearItems();
                        restore();
                        getData2();
                        getData();
                        localStorage.setItem('client', '');
                        $('input[name=client]').val('');
                        setTimeout(() => {
                            $('#print-zone').printThis({
                                importStyle: false,
                                footer: "<div style='margin:2rem;'>Merci d'etre passé! A bientot.</div>",
                            });
                        }, 1000);

                    } else {
                        var m = res.message;
                        try {
                            m += '<br>' + res.data.msg.join('<br>');
                        } catch (error) {}
                        rep.addClass('alert alert-danger mr-4 ml-4').html(m);
                        btn.attr('disabled', false);
                    }
                    rep.slideDown();
                    btn.find('span').removeClass().addClass('fa fa-print');
                });
            })

            var io_server = 'http://localhost:3000/';
            var uid = '{{ auth()->user()->id }}';
            var id = localStorage.getItem('_socket_id');
            if (!id) {
                localStorage.setItem('_socket_id', Math.random());
            }

            var socketIO;
            var init_soc = false;
            var btnApp = $('#btn-socket');
            try {
                socketIO = io(io_server);
                socketIO.on("connect", function(data) {
                    _start();
                });

                function _start() {
                    socketIO.emit("connected", {
                        uid: uid,
                        id: id,
                        type: "user"
                    });
                    socketIO.on("app-connected", function(data) {
                        if (data) {
                            btnApp.removeClass().addClass('btn btn-success').html(
                                '<i class="fa fa-wifi"></i> Appareil connecté').attr(
                                'disabled', true);
                        } else {
                            btnApp.removeClass().addClass('btn btn-outline-danger').html(
                                '<i class="fa fa-wifi"></i> Coupler un appareil').attr(
                                'disabled', false);
                        }
                    });

                    socketIO.on("welcome", function(data) {
                        if (!init_soc) {
                            init_soc = true;
                            socketIO.on("new-item", (data) => {
                                var tr = $('[tr-item=' + data.id + ']');
                                var iqte = $('[item-qte-' + data.id + ']', tr);
                                var qte = Number(iqte.val());
                                if (qte) {
                                    qte += 1;
                                } else {
                                    qte = 1;
                                };
                                iqte.val(qte);
                                data.qte = qte;
                                saveItem(data);
                                insert(data, true);
                                addNum();
                                initActions();
                            });
                        }
                    });

                }
            } catch (error) {}


        })
    </script>

@endsection

@extends('layouts.main')
@section('title', 'Ventes magasin')

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
            <div class="card ">
                <div class="card-header">
                    <h4>Ventes magasin</h4>
                    <div class="card-header-action">
                        <form id="f-change">
                            <div class="row">
                                <div class="col-md form-group d-block mr-1">
                                    <select class="custom-select groupe-change" name="groupe">
                                        <option value="">Tous les groupes d'articles</option>
                                        @foreach ($groupe as $e)
                                            <option @if ($e->par_defaut == 1) selected @endif
                                                value="{{ $e->id }}">
                                                {{ $e->groupe }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md form-group d-block ml-1">
                                    <select class="custom-select cat-change" name="categorie" disabled>
                                        <option value="">Toutes les catégories d'articles</option>
                                    </select>
                                </div>
                                <div class="col-md form-group ml-1 mr-1">
                                    <input class="form-control datepicker p-3 rounded-sm" name="date"
                                        value="{{ date('Y-m-d') }}" />
                                </div>
                                <div class="col-md form-group ml-1">
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
                                <table id="t-vente"
                                    class="table table-condensed table-bordered table-hover font-weight-bold"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Article</th>
                                            <th>Code</th>
                                            <th>Quantité vendue</th>
                                            <th>Prix</th>
                                            <th>Total</th>
                                            <th>Caissier</th>
                                            <th>Date vente</th>
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
                        <hr>
                        <div class="" id="d-vente"></div>
                    </div>
                </div>
            </div>
            <div class="card ">
                <div class="card-header">
                    <h4>Factures</h4>
                    <div class="card-header-action">
                        <form id="f-change-2">
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
                    <button class="btn btn-secondary" data-dismiss="modal">
                        Fermer
                    </button>
                    <button class="btn btn-danger " btn-fac>
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
                        <li>L'administrateur sera informé de la suppression de cette facture</li>
                        <li>Le stock de chaque article sur cette facture sera réapprovisionné</li>
                        <li>Les articles présents sur cette facture seront supprimés de la liste des articles vendus</li>
                        <li>Le total des ventes sera déduit du montant de cette facture(<span mont-fac></span>)</li>
                    </ul>
                    <div id="del-rep"></div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">
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
            spin =
                `<tr><td class="text-center" colspan="8"><span class="spinner-border text-danger"></span></td></tr>`;
            spin2 =
                `<tr><td class="text-center" colspan="7"><span class="spinner-border text-danger"></span></td></tr>`;

            var table = $('#t-vente');
            var table2 = $('#t-facture');

            var groupchange = $('.groupe-change');
            var catechange = $('.cat-change');
            groupchange.change(function() {
                $(this).attr('disabled', true);
                catechange.attr('disabled', true);
                getCategorie();
            });
            $('#f-change').change(function() {
                getData();
            });
            $('#f-change-2').change(function() {
                getData2();
            });
            $('#f-change,#f-change-2').submit(function() {
                event.preventDefault()
            })

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
                $('#z-vente').slideUp();

                $.ajax({
                    url: '{{ route('ventes.index') }}',
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
                                    <td title="${e.categorie_article}(${e.groupe})">${e.article}</td>
                                    <td>${e.code}</td>
                                    <td>${e.qte}</td>
                                    <td>${e.prix}</td>
                                    <td>${e.total}</td>
                                    <td>${e.caissier}</td>
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
                        '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>'
                    );
                    table.DataTable().destroy();
                    if (str.length > 0) {
                        table.find('tbody').html(str);
                        table.DataTable(opt);
                    } else {
                        str =
                            '<tr><td colspan="8" class="text-danger font-weight-bolder text-center">Aucune vente</td></tr>';
                        table.find('tbody').html(str);
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
                        var buto =
                            str += `<tr>
                                    <td>${i+1}</td>
                                    <td >${e.numero_facture}</td>
                                    <td >${e.client}</td>
                                    <td>${e.caissier}</td>
                                    <td>${e.total}</td>
                                    <td>${e.date}</td>
                                    <td class='d-flex justify-content-center'>
                                        <button value='${e.id}' class='btn text-muted detail' ><i class='fa fa-eye'></i> Détails</button>
                                        <button value='${e.id}' mont-fac='${e.total}' facture="Voulez-vous vraiment supprimer la facture N° ${e.numero_facture} enregistrée à la date ${e.date} ?" class='btn text-muted del-fact ml-2'><i class='fa fa-trash'></i> Supprimer</button>
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

            $('[btn-fac]').click(function() {
                // $('#print-zone').printThis({
                //     footer: "<div style='margin:2rem;'>Merci d'etre passé! A bientot.</div>",
                // });
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
                setTimeout(function() {
                    mywindow.close();
                }, 1000);
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
                                    <span>{{ @$shop->adresse }}</span><br>
                                    <span>{{ @$shop->contact }}</span>
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


        })
    </script>


@endsection

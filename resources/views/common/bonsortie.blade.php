@extends('layouts.main')
@section('title', 'Bon de sortie')

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
                <div class="card-header d-flex justify-content-between">
                    <h3 class="h4 font-weight-bold">Bons de sortie</h3>
                    <div class="card-header-action">
                        <div class="form-group m-2 d-block">
                            <button class="btn btn-danger" data-toggle='modal' data-target='#mdl-add'
                                style="border-radius: 5px!important;">
                                Ajouter un bon de sortie Articles
                            </button>
                            <button class="btn btn-dark d-none" data-toggle='modal' data-target='#mdl-add2'
                                style="border-radius: 5px!important;">
                                Ajouter un bon de sortie fonds
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="t-unite"
                                    class="table table-condensed table-bordered table-hover font-weight-bold"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>N°</th>
                                            <th>Total CDF</th>
                                            <th>Status</th>
                                            <th>Emis par</th>
                                            <th>Date</th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" colspan="7">
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

            <div class="card ">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-muted">
                                <i class="fa fa-exclamation-triangle text-warning"></i>
                                Une fois le bon de sortie validé, le solde de la caisse sera mis à jour
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade modal-fullscreen" id="mdl-add" tabindex="-1" role="dialog" aria-hidden="true"
        data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered fullscreen" role="document">
            <div class="modal-content ">
                <div class="modal-header bg-danger text-white font-weight-bold d-flex justify-content-between">
                    <b>Ajouter un bon de sortie article</b>
                    <span style="cursor: pointer" data-dismiss="modal">
                        <i class="fa fa-times-circle p-2 "></i>
                    </span>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Articles en stock</h4>
                            <b class="text-danger mb-3">Cliquez sur un article pour l'ajouter au bon de sortie.</b>
                            <div class="table-responsive">
                                <table table class="table table-striped table-hover" style="width: 100%">
                                    <thead>
                                        <th></th>
                                        <th>ARTICLE</th>
                                        <th>PU</th>
                                        <th>PV</th>
                                        <th>STOCK ACTUEL</th>
                                    </thead>
                                    <tbody>
                                        @foreach ($articles as $k => $el)
                                            <tr style="cursor: pointer" value="{{ json_encode($el) }}">
                                                <td>{{ $k + 1 }}</td>
                                                <td>{{ $el->article }}</td>
                                                <td>{{ montant($el->prix_achat, $el->devise_achat) }}</td>
                                                <td>{{ montant($el->prix, $el->devise->devise) }}</td>
                                                <td>{{ "$el->stock {$el->unite_mesure->unite_mesure}" }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <form action="#" id="f-bon" class="was-validated">
                                <div class="d-flex justify-content-between">
                                    <h4>Bon de sortie</h4>
                                    <div class="form-group mr-1 d-none">
                                        <label for="">Sélectionnez un Client</label> <br>
                                        <select name="client_id" class="select2 form-control rounded-0 p-0 selclient"
                                            style="width: 300px !important">
                                            <option value="">Aucun client</option>
                                            @foreach ($clients as $el)
                                                <option value="{{ $el->id }}" client='{{ json_encode($el) }}'>
                                                    {{ $el->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <input type="hidden" name="type" value="sortie" id="">
                                    <table tbon class="table table-striped table-hover" style="width: 100%">
                                        <thead>
                                            <th>ARTICLE</th>
                                            <th>QTE</th>
                                            <th>PRIX DE VENTE</th>
                                            <th></th>
                                        </thead>
                                        <tbody items></tbody>
                                        <tfoot class="font-weight-bolder">
                                            <tr title="Somme Prix de vente * Qte">
                                                <td class="text-nowrap">
                                                    TOTAL BON
                                                </td>
                                                <td colspan="3" class="text-right text-nowrap">
                                                    <span totbon class="badge badge-success" style="font-size: 18px"></span>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>

                                    {{-- <h5>Bon de livraison</h5> --}}
                                    <div class="d-none">
                                        <input type="hidden" name="typebon" value="article" id="">
                                        <div class="form-group">
                                            <label for="">Nom du client</label>
                                            <input type="hidden" value="" class="form-control" name="nomclient"
                                                required placeholder="Nom du client">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Tel. du client</label>
                                            <input type="hidden" value="" class="form-control" name="telephoneclient"
                                                required placeholder="Tel. du client">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Adresse du client</label>
                                            <input type="hidden" value="" class="form-control"
                                                name="adresseclient" placeholder="Adresse du client">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Adresse de livraison</label>
                                            <input type="hidden" value="" class="form-control"
                                                name="adresselivraison" required placeholder="Adresse de livraison">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Nom du chauffeur</label>
                                            <input type="hidden" value="" class="form-control" name="chauffeur"
                                                placeholder="Nom du chauffeur">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Numéro vehicule</label>
                                            <input type="hidden" value="" class="form-control"
                                                name="numerovehicule" placeholder="Numero vehicule">
                                        </div>
                                        <div class="form-group">
                                            <label for="">Date de livraison</label>
                                            <input type="hidden" etype="datetime-local"
                                                value="{{ now('Africa/Lubumbashi') }}" class="form-control"
                                                name="datelivraison" required placeholder="Date de elivraison" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="">Motif de sortie</label>
                                            <textarea name="motif" class="form-control" name="motif" id="" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </form>
                            <div class="form-group" style="display: none" id="rep00"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer mb-5 pb-5">
                    <button class="btn btn-dark" data-dismiss="modal">
                        Fermer
                    </button>
                    <button class="btn btn-danger" savebon type="button">
                        <span></span>
                        Ajouter
                    </button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="mdl-up" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header bg-danger text-white font-weight-bold d-flex justify-content-between">
                    <b>Aperçu</b>
                    <span style="cursor: pointer" data-dismiss="modal">
                        <i class="fa fa-times-circle p-2 "></i>
                    </span>
                </div>
                <input type="hidden" name="id">
                <div class="modal-body">
                    <div class="table-responsive">
                        @php
                            if (!$shop->logo) {
                                $logo = fileToBase64('assets/img/logo.png');
                            } else {
                                $logo = fileToBase64('storage/' . $shop->logo);
                            }
                        @endphp
                        <div style="width: 21cm !important">
                            <div id="bonentree" style="width: 100% !important" class="p-2">
                                <div class="row px-4" style="padding-top: 20px; height: 170px">
                                    <div class="col-6 d-flex align-items-center">
                                        <img src="{{ $logo }}" width="120px" height="120px" alt="">
                                    </div>
                                    <div class="col-6 d-flex align-items-center justify-content-end">
                                        <div class="text-right">
                                            <h5 style="font-weight: 900; color:#112667">BON SORTIE N° <span numbon></span>
                                            </h5>
                                            <h5 style="font-weight: 900; color:#112667">STATUS : <span statusbon></span>
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                                <div style="height: 3px;background-color: #112667" class="w-100 mt-3"></div>
                                <div class="row px-4">
                                    <div class="col-8 mt-3">
                                        <h5 class="font-weight-bold">{{ $shop->shop }}</h5>
                                        <div class="text-muted">
                                            <p class='m-0'>
                                                {{ $shop->adresse }}
                                            </p>
                                            <p class='m-0'>
                                                {{ $shop->contact }}
                                                <br>
                                                RCCM: {{ $shop->rccm ?? '-' }},
                                                IDNAT: {{ $shop->idnat ?? '-' }},
                                                MUM IMPOT: {{ $shop->numeroimpot ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <div style="height: 200px;background-color: #f5e513" class="w-100">
                                    <div class="row d-flex h-100 px-4">
                                        <div class="col-6 d-flex align-items-center">
                                            <div class="bg-white p-2 w-100 text-dark"
                                                style="border: 2px solid #112667; border-radius: 10px;">
                                                <p class="m-0">Date : <span datebon></span></p>
                                                <p class="m-0">Date limite : -</p>
                                                <p class="m-0">Référence : <span numbon></span></p>
                                                <p class="m-0">Emis par : <span emispar></span></p>
                                            </div>
                                        </div>
                                        <div class="col-6 d-flex align-items-center">
                                            <div class="">
                                                <b>Client(e)</b>
                                                <br>
                                                <p class="m-0">Nom : <span bnomclient></span></p>
                                                <p class="m-0">Adresse : <span badresseclient></span></p>
                                                <p class="m-0">Tel : <span btelephoneclient></span></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <iscopie></iscopie>
                                </div>
                                <div style="background-color: #112667" class="w-100 my-3">
                                    <p class="m-0 text-white font-weight-bold p-2"></p>
                                </div>

                                <div style="height: 3px;background-color: #112667" class="w-100 my-3"></div>
                                <div class="row">
                                    <div class="col-12">
                                        <table class="table table-borderless table-condensed" style="width: 100%">
                                            <tr>
                                                <th style="width:2px!important">#</th>
                                                <th>Désignation</th>
                                                <th>Prix Unitaire</th>
                                                <th>Qte</th>
                                                <th class="text-right">Prix Total</th>
                                            </tr>
                                            <tbody titems></tbody>
                                        </table>
                                    </div>
                                </div>
                                <div class="row px-4">
                                    <div class="col-4"></div>
                                    <div class="col-8 text-right" style="font-size:20px">
                                        <span>
                                            <b style="color: #112667">Total Géneral TTC</b>
                                            <span class="p-1"
                                                style="border-radius: 10px;background-color: #ff0000; color: #112667"
                                                totbon1></span>
                                        </span>
                                        <div class="d-flex justify-content-end">
                                            <div class="mt-2 p-2"
                                                style="height: 100px; width:400px; font-size: 15px; background-color: #e7e9e9;">
                                                <small class="text-muted">Signature du client (précédée de la mention « Bon
                                                    pour accord
                                                    »)</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <b>Modalité de paiemen : -</b>
                                    </div>
                                    <div style="height: 3px;background-color: #e7e9e9" class="w-100 my-3"></div>
                                    <div class="text-center w-100">
                                        <h5 class="font-weight-bold">{{ $shop->shop }}</h5>
                                        <p class="m-0">
                                            {{ $shop->contact }}
                                            <br>
                                            RCCM: {{ $shop->rccm ?? '-' }},
                                            IDNAT: {{ $shop->idnat ?? '-' }},
                                            MUM IMPOT: {{ $shop->numeroimpot ?? '-' }}
                                        </p>
                                        <p class="m-0">{{ $shop->adresse }}</p>
                                    </div>
                                </div>
                                <div class="w-100 d-flex">
                                    <div style="height: 20px;background-color: #112667" class="w-100 my-3"></div>
                                    <div style="height: 20px; width: 60px; background-color: #f5e513" class="my-3">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="" id="ajusteprix" style="display: none">
                            <h4>Ajustement des prix</h4>
                            <div class="mt-3">
                                <form action="#" id="fadjust">
                                    <div class="table-responsive">
                                        <input type="hidden" id="ajustedata">
                                        <table class="table table-hover table-striped">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Designation</th>
                                                    <th>PU</th>
                                                    <th>Réduction</th>
                                                    <th>Qte Sortie</th>
                                                    <th>Qte Vendue</th>
                                                    <th>Prix Vente</th>
                                                    <th>Total Vendu</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody tableajust>

                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="py-3">
                                        <div id="rep01"></div>
                                    </div>
                                    <button class="btn mb-2 btn-info mr-2" type="submit">
                                        <span></span>
                                        Valider la vente
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="form-group" style="display: none" id="rep11"></div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <div class="" id="controlbtn">
                        @if (in_array(auth()->user()->user_role, ['admin', 'gerant']))
                            <button class="btn mb-2 btn-info mr-2" btnvalide btnvb type="button">
                                <span></span>
                                Valider ce bon
                            </button>
                            <button class="btn mb-2 btn-danger" btnvalide btnrej type="button" action="rejeter">
                                <span></span>
                                Rejeter ce bon
                            </button>
                        @endif
                        <button class="btn mb-2 btn-success ml-2" bprint>
                            <i class="fa fa-print"></i>
                            Imprimer
                        </button>
                    </div>
                    <div class="">
                        <button class="btn mb-2 btn-info" type="button" style="display: none" id="showfac">
                            Retour
                        </button>
                    </div>
                    <button class="btn mb-2 btn-dark" data-dismiss="modal">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>

    @php
        $shop = \App\Models\Shop::where('compte_id', compte_id())->first();
    @endphp

    <div class="modal fade" id="mdl-up2" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header bg-danger text-white font-weight-bold d-flex justify-content-between">
                    <b>Bon de livraison</b>
                    <span style="cursor: pointer" data-dismiss="modal">
                        <i class="fa fa-times-circle p-2 "></i>
                    </span>
                </div>
                <div class="modal-body">
                    <div class="table-responsive">
                        <div id="bonsortie" estyle="width: 21cm !important">
                            <div style="width: 100% !important">
                                <div class="mt-3 p-3">
                                    <table class="table-table-striped table-hover w-100" border="1">
                                        <thead>
                                            <tr class="text-center">
                                                <th colspan="5">BON DE LIVRAISON</th>
                                            </tr>
                                        </thead>
                                        <tr>
                                            <td colspan="2"><b>{{ $shop->shop }}</b></td>
                                            <td>BDL N0</td>
                                            <td colspan="2">-</td>
                                        </tr>
                                        <tr>
                                            <td colspan="2" rowspan="6">
                                                @if ($shop->adresse)
                                                    {{ $shop->adresse }} <br>
                                                @endif
                                                @if ($shop->contact)
                                                    {{ $shop->contact }} <br>
                                                @endif
                                                @if ($shop->email)
                                                    {{ $shop->email }} <br>
                                                @endif
                                                @if ($shop->telephone)
                                                    {{ $shop->telephone }} <br>
                                                @endif
                                                @if ($shop->siegesocial)
                                                    {{ $shop->siegesocial }} <br>
                                                @endif
                                            </td>
                                            <td>Date livraison</td>
                                            <td colspan="2"> <span datelivraison>-</span> </td>
                                        </tr>
                                        <tr>
                                            <td colspan="1">N° Bon</td>
                                            <td colspan="2"><span numbonsortie></span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1">Date facture</td>
                                            <td colspan="2"><span datefacture>-</span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1">Délivré par</td>
                                            <td colspan="2"><span emetteurnom>-</span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1">BDC Number</td>
                                            <td colspan="2"><span bdc>-</span></td>
                                        </tr>
                                        <tr>
                                            <td colspan="1">Location</td>
                                            <td colspan="2"><span location>-</span></td>
                                        </tr>
                                        <tr>
                                            <th colspan="5">Client : <span nomclient></span></th>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Contact : <span telephoneclient></span></th>
                                            <th colspan="3">Email : <span emailclient></span></th>
                                        </tr>
                                        <tr>
                                            <th colspan="5">Adresse : <span adresseclient></span></th>
                                        </tr>
                                        <tr>
                                            <th colspan="2">Chauffeur : <span nomchauffeur></span></th>
                                            <th colspan="3">N° vehicule : <span numerovehicule></span></th>
                                        </tr>
                                        <tr>
                                            <th colspan="2" class="font-weight-bold">Description article</th>
                                            <th colspan="3" class="font-weight-bold text-right">Prix * Qte</th>
                                        </tr>
                                        <tbody articleslist></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <div class="">
                        <button class="btn mb-2 btn-success ml-2" bprint2>
                            <i class="fa fa-print"></i>
                            Imprimer
                        </button>
                    </div>
                    <button class="btn mb-2 btn-dark" data-dismiss="modal">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-code')
    <style>
        .modal-fullscreen {
            padding: 0 !important;
        }

        .modal-fullscreen .modal-dialog {
            width: 100%;
            max-width: none;
            height: 100%;
            margin: 0;
        }

        .modal-fullscreen .modal-content {
            height: 100%;
            border: 0;
            border-radius: 0;
        }

        .modal-fullscreen .modal-body {
            overflow-y: auto;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('assets/datatables/datatables.min.css') }}" />
    <script src="{{ asset('assets/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/datatables/datatables.min.js') }}"></script>

    <script src="{{ asset('assets/js/printThis.js') }}"></script>

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

            $('[bprint]').click(function() {
                $("#bonentree").printThis();
            });
            $('[bprint2]').click(function() {
                $("#bonsortie").printThis();
            });

            var selclient = $('.selclient');
            selclient.change(function() {
                fillclient();
            })

            function fillclient() {
                try {
                    var v = JSON.parse($(':selected', selclient).attr('client'));
                    var form = $('#f-bon');
                    $('[name=nomclient]', form).val(v.name);
                    $('[name=telephoneclient]', form).val(v.phone);
                    $('[name=adresseclient]', form).val(v.adresse);
                    $('[name=adresselivraison]', form).val(v.adresselivraison);
                } catch (error) {

                }
            }
            fillclient();

            var items = $('[items]');
            $('tr[value]').click(function() {
                var btn = $(this);
                var val = btn.attr('value');
                var data = JSON.parse(val);
                var id = data.id;

                var tr = $(`tr[tr-${id}]`);
                if (tr.length) {
                    var q = Number($(`[qte]`, tr).val()) + 1;
                    $(`[qte]`, tr).val(q);
                } else {
                    tr = `
                        <tr tr-${data.id}>
                            <td>${data.article}</td>
                            <td>
                                <input type="hidden" value="${data.id}" name="article_id[]">
                                <input qte type="number" style="min-width: 100px" value="1" min="1" name="qte[]" class="form-control">
                            </td>
                            <td>
                                <b class="">
                                    ${data.prix} ${data.devise.devise}
                                </b>
                            </td>
                            <td>
                                <button value="${data.id}"
                                    class="btn btn-outline-danger bremove">
                                    <i class="fa fa-times-circle"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                    tr = $(tr);
                    items.append(tr);
                }

                btn.Blink(100, 1);
                $('.bremove').off('click').click(function() {
                    var id = this.value;
                    $(`tr[tr-${id}]`).remove();
                    totbon();
                });
                $(':input', $('#f-bon')).off('change').change(function() {
                    totbon();
                })
                totbon();
            });

            function totbon() {
                var span = $('span[totbon]');
                span.html('<i class="fa fa-spin fa-spinner"></i>');
                var data = $('#f-bon').serialize();
                $.ajax({
                    url: '{{ route('totbonentree') }}',
                    type: 'post',
                    data: data,
                    success: function(data) {
                        span.html(data.total);
                    },
                    error: function() {

                    }
                });
            }
            $('[table]').DataTable({
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
                pageLength: 50
            });

            spin =
                `<tr><td class="text-center" colspan="7"><span class="spinner-border text-danger"></span></td></tr>`;
            var table = $('#t-unite');
            getData();

            function getData() {
                table.find('tbody').html(spin);
                $.ajax({
                    url: '{{ route('bonsortie.index') }}',
                    timeout: 20000,
                }).done(function(res) {
                    data = res.data;
                    str = '';
                    if (data.length > 0) {
                        $(data).each(function(i, e) {
                            var stat = '',
                                disabled = '';
                            if (e.status == 0) {
                                stat =
                                    `<span style="cursor:pointer" title="ce bon de sortie est en attente de validation." class="badge badge-warning"><span class="fa fa-check-circle"></span> EN ATTENTE</i>`;
                            }
                            if (e.status == 1) {
                                disabled = `disabled`;
                                stat =
                                    `<span style="cursor:pointer" title="ce bon de sortie est validé par ${e.valide_par}." class="badge badge-success"><span class="fa fa-check-circle"></span> VALIDE</i>`;
                            }
                            if (e.status == 2) {
                                disabled = `disabled`;
                                stat =
                                    `<span style="cursor:pointer" title="ce bon de sortie est rejeté par ${e.rejete_par}." class="badge badge-danger"><span class="fa fa-check-circle"></span> REJETE</i>`;
                            }

                            var json = escape(JSON.stringify(e));
                            str += `<tr>
                                        <td>${i+1}</td>
                                        <td>
                                            ${e.numero}
                                        </td>
                                        <td class="text-nowrap">${e.total_cdf}</td>
                                        <td>${stat}</td>
                                        <td>${e.emetteur}</td>
                                        <td>${e.date}</td>
                                        <td>
                                            <span app-label-msg='${e.id}' class='ml-3 text-nowrap'></span>
                                            <div class='d-flex justify-content-center'>
                                                <div>
                                                    <button class='btn text-muted mr-3 bdetail text-nowrap' data='${json}'><i class='fa fa-file'></i> Bon de sortie</button>
                                                </div>
                                                <div class='d-none'>
                                                    <button class='btn text-black-50 mr-3 bdetail2 text-nowrap' data='${json}'><i class='fa fa-file-archive'></i> Bon de livraison</button>
                                                </div>
                                                @if (in_array(auth()->user()->user_role, ['admin']))
                                                    <div class="dropdown ml-2">
                                                        <button ${disabled} title="Supprimer : ${e.numero}" class="btn text-danger btn-del dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            <i class='fa fa-trash'></i>
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="">
                                                            <button class="dropdown-item delete btn"  value='${e.id}'>Supprimer</button>
                                                        </div>
                                                    </div>
                                                @endif

                                            </div>
                                        </td>
                                    </tr>`;
                        });
                        table.find('tbody').html(
                            '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>'
                        );
                        table.DataTable().destroy();
                        table.find('tbody').html(str);
                        $('[data-toggle=tooltip]').off('tooltip').tooltip();
                        $('body').tooltip({
                            selector: '[data-toggle="tooltip"]'
                        }).click(function() {
                            $('[data-toggle="tooltip"]').tooltip("hide");
                        });
                        $('.bdetail').off('click').click(function() {
                            var d = $(this).attr('data');
                            $('#ajustedata').val(d);
                            var data = JSON.parse(unescape(d));
                            var mdl = $('#mdl-up');
                            $('#rep11').html('');
                            var titem = $('[titems]');
                            var tr = '';
                            $(data.articles).each(function(i, e) {
                                tr += `
                                <tr>
                                    <td>${i+1}</td>
                                    <td>${e.article}</td>
                                    <td>${e.pivot.prix_vente} ${e.pivot.devise_vente}</td>
                                    <td>${e.pivot.qte}</td>
                                    <td class='text-right'>${e.pivot.qte * e.pivot.prix_vente } ${e.pivot.devise_vente}</td>
                                </tr>
                                `;
                            });


                            titem.html(tr);
                            $('span[numbon]').html(data?.numero);
                            $('span[datebon]').html(data?.date);
                            $('span[emispar]').html(data?.emetteur);
                            $('span[totbon1]').html(data?.total_cdf);

                            var bon = data.bon_livraisons[0];
                            $('span[bnomclient]').html(bon?.nomclient);
                            $('span[badresseclient]').html(bon?.adresseclient);
                            $('span[btelephoneclient]').html(bon?.telephoneclient);

                            var st = data.status;
                            $('span[statusbon]').html(st == 0 ?
                                '<span class="text-muted">EN ATTENTE</span>' : (st == 1 ?
                                    '<span class="text-success">VALIDE</span>' :
                                    '<span class="text-danger">REJETE</span>'));
                            var b = $('[btnvalide]');
                            b.val(data.id);
                            if (st == 0) {
                                b.attr('disabled', false);
                            } else {
                                b.attr('disabled', true);
                            }
                            mdl.modal();
                            $('#showfac').click();
                        });
                        $('.bdetail2').off('click').click(function() {
                            var data = JSON.parse(unescape($(this).attr('data')));
                            var mdl = $('#mdl-up2');
                            var bonlivr = data.bon_livraisons[0];

                            $('[datelivraison]').html(bonlivr?.datelivraison);
                            $('[numbonsortie]').html(data?.numero);
                            $('[datefacture]').html(data?.date);
                            $('[emetteurnom]').html(data?.emetteur);
                            $('[nomclient]').html(bonlivr?.nomclient);
                            $('[telephoneclient]').html(bonlivr?.telephoneclient);
                            $('[emailclient]').html(bonlivr?.emailclient);
                            $('[adresseclient]').html(bonlivr?.adresseclient);
                            $('[nomchauffeur]').html(bonlivr?.chauffeur);
                            $('[numerovehicule]').html(bonlivr?.numerovehicule);

                            var articles = data.articles;
                            var txt = '';

                            $(articles).each(function(i, e) {
                                txt +=
                                    `<tr>
                                        <th colspan="2">${e.article}</th>
                                        <th colspan="3" class='text-right'> ${e.pivot.prix_vente} ${e.pivot.devise_vente} * ${e.pivot.qte} </th>
                                    </tr>
                                    `;
                            });

                            txt +=
                                `<tr>
                                        <th colspan="2">TOTAL BON</th>
                                        <th colspan="3" class='text-right'> ${data.total_cdf}</th>
                                    </tr>
                                    `

                            $('[articleslist]').html(txt);

                            mdl.modal();

                        });

                        $('.delete').off('click').click(function() {
                            var id = this.value;
                            var val = $('[app-input=' + id + ']').val();
                            var btn = $(this)
                            btn = $(btn).closest('.dropdown');
                            btn = $(btn).find('.btn-del');
                            btn.find('i').removeClass().addClass(
                                'spinner-border spinner-border-sm');

                            var tr = btn.closest('tr');
                            $('button', tr).attr('disabled', true);
                            var span = $('[app-label-msg=' + id + ']').removeClass().html('');
                            $.ajax({
                                url: '{{ route('bonsortie.destroy', '') }}/' + id,
                                type: 'delete',
                                timeout: 20000,
                            }).done(function(res) {
                                data = res.data;
                                if (res.success == true) {
                                    var m = res.message;
                                    span.addClass('ml-2 text-success').html(m);
                                    $('[app-label=' + id + ']').html(val);
                                    setTimeout(() => {
                                        getData();
                                    }, 2000);
                                } else {
                                    var m = res.message;
                                    span.addClass('ml-2 text-danger').html(m);
                                    btn.find('i').removeClass().addClass(
                                        'fa fa-trash text-danger');
                                }
                                $('button', tr).attr('disabled', false);
                            });
                        });
                        table.DataTable(opt);
                    } else {
                        str =
                            '<tr><td colspan="7" class="text-danger font-weight-bolder text-center">Aucune donnée</td></tr>';
                        table.find('tbody').html(
                            '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>');
                        table.DataTable().destroy();
                        table.find('tbody').html(str);
                    }

                })
            }

            $('[savebon]').click(function() {
                var rep = $('#rep00');
                var data = $('#f-bon').serialize();


                if ($('[items]').children().length == 0) {
                    alert("Veuillez ajouter au moins un article au bon");
                    // rep.removeClass().addClass('text-danger').html(
                    //     "Veuillez ajouter au moins un article au bon").slideDown();
                    return;
                }

                var stop = false
                $(':input[required]', $('#f-bon')).each(function(i, e) {
                    if ($(this).val() == '') {
                        stop = true;
                        return;
                    }
                });

                if (stop) {
                    alert("Veuillez completer tous champs en rouge dans le formulaire");
                    return;
                }

                rep.removeClass().slideUp();

                var btn = $(this);
                btn.find('span').removeClass().addClass('spinner-border spinner-border-sm');
                btn.attr('disabled', true);

                $.ajax({
                    url: '{{ route('bonsortie.store') }}',
                    type: 'post',
                    data: data,
                    timeout: 20000,
                }).done(function(res) {
                    btn.attr('disabled', false);
                    if (res.success == true) {
                        var m = res.message;
                        rep.addClass('ml-2 text-success').html(m).slideDown();
                        $('[items]').html('');
                        getData();
                        setTimeout(() => {
                            $('.modal').modal('hide');
                        }, 3000);
                    } else {
                        var m = res.message;
                        rep.addClass('ml-2 text-danger').html(m).slideDown();
                    }
                }).always(function() {
                    btn.find('span').removeClass();
                    btn.attr('disabled', false);
                });
            });

            $('[btnvb]').click(function() {
                $('#bonentree').hide();
                $('#controlbtn').hide();
                $('#ajusteprix').show();
                $('#showfac').show();
            });

            $('#showfac').click(function() {
                $('#ajusteprix').hide();
                $('#showfac').hide();
                $('#bonentree').show();
                $('#controlbtn').show();

                var v = $('#ajustedata').val();
                var data = JSON.parse(unescape(v));

                var articles = data.articles;

                var tr = '';
                $(articles).each(function(i, e) {
                    tr += `
                        <tr tr2-${e.id}>
                            <td>${i+1}</td>
                            <td>${e.article}</td>
                            <td>
                                <b class="text-nowrap">
                                    ${e.pivot.prix_vente} ${e.pivot.devise_vente}
                                </b>
                            </td>
                            <td>
                                <b class="">
                                    ${e.reduction} %
                                </b>
                            </td>
                            <td>
                                <b class="">
                                    ${e.pivot.qte}
                                </b>
                            </td>
                            <td>
                                <input qte type="number" style="min-width: 100px" value="${e.pivot.qte}" max="${e.pivot.qte}" min="1" name="qtevente[]" class="form-control">
                                <input type="hidden" value="${e.id}" name="article_ids[]">
                            </td>
                            <td>
                                <input qte type="number" style="min-width: 100px" value="${e.pivot.prix_vente}" max="${e.pivot.prix_vente}" min="0" name="prixvente[]" class="form-control">
                            </td>
                            <td class="text-nowrap">
                                <b totvente devise='${e.pivot.devise_vente}'></b>
                            </td>
                            <td>
                                <button value="${e.id}"
                                    class="btn btn-outline-danger bremove2">
                                    <i class="fa fa-times-circle"></i>
                                </button>
                            </td>
                        </tr>
                    `;
                });
                $('[tableajust]').html(tr);
                $('.bremove2').off('click').click(function() {
                    var id = this.value;
                    $(`tr[tr2-${id}]`).remove();
                    calcul();
                });

                $(':input', $('[tableajust]')).off('change').change(function() {
                    calcul();
                })

                calcul();

                function calcul() {
                    var trs = $('[tableajust]').children();

                    var tot = 0;
                    trs.each(function(i, el) {
                        var q = Number($('[name="qtevente[]"]', el).val());
                        var pv = Number($('[name="prixvente[]"]', el).val());
                        var t = q * pv;
                        if (!t) {
                            t = '<span class="text-danger">ERREUR</span>'
                        } else {
                            var dev = $('[devise]', el).attr('devise');
                            t = t + ' ' + dev;
                        }
                        $('[totvente]', el).html(t);
                    });
                }
            });

            $('#fadjust').submit(function() {
                event.preventDefault();

                var rep = $('#rep01', form);

                var v = $('#ajustedata').val();
                var data = JSON.parse(unescape(v));
                var id = data.id;
                rep.removeClass().slideUp();

                var form = $(this);
                var data = form.serialize();
                data += '&action=valider';

                var btn = $(this);
                btn.find('span').removeClass().addClass('spinner-border spinner-border-sm');
                btn.attr('disabled', true);

                $.ajax({
                    url: '{{ route('bonsortie.update', '') }}/' + id,
                    type: 'put',
                    data: data,
                    timeout: 20000,
                }).done(function(res) {
                    btn.attr('disabled', false);
                    if (res.success == true) {
                        var m = res.message;
                        rep.addClass('ml-2 text-success').html(m).slideDown();
                        getData();
                        setTimeout(() => {
                            $('.modal').modal('hide');
                        }, 8000);
                    } else {
                        var m = res.message;
                        rep.addClass('ml-2 text-danger').html(m).slideDown();
                    }
                }).always(function() {
                    btn.find('span').removeClass();
                    btn.attr('disabled', false);
                });
            });

            $('[btnrej]').click(function() {
                event.preventDefault();
                var rep = $('#rep11');
                rep.removeClass().slideUp();
                data += 'action=rejeter';

                var btn = $(this);
                btn.find('span').removeClass().addClass('spinner-border spinner-border-sm');
                btn.attr('disabled', true);
                var id = this.value;
                $.ajax({
                    url: '{{ route('bonsortie.update', '') }}/' + id,
                    type: 'put',
                    data: data,
                    timeout: 20000,
                }).done(function(res) {
                    btn.attr('disabled', false);
                    if (res.success == true) {
                        var m = res.message;
                        rep.addClass('ml-2 text-success').html(m).slideDown();
                        getData();
                        setTimeout(() => {
                            $('.modal').modal('hide');
                        }, 8000);
                    } else {
                        var m = res.message;
                        rep.addClass('ml-2 text-danger').html(m).slideDown();
                    }
                }).always(function() {
                    btn.find('span').removeClass();
                    btn.attr('disabled', false);
                });
            });




        })
    </script>


@endsection

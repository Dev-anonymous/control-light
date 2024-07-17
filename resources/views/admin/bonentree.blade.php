@extends('layouts.main')
@section('title', 'Bon d\'entrée ')

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
                    <h3 class="h4 font-weight-bold">Bons d'entrée</h3>
                    <div class="card-header-action">
                        <div class="form-group m-2 d-block">
                            <button class="btn btn-danger" data-toggle='modal' data-target='#mdl-add'
                                style="border-radius: 5px!important;">
                                Ajouter un bon
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
                                Une fois le bon d'entrée validé, le stock, le prix d'achat et le prix de vente des articles
                                correspondants seront mis à jour
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
                    <b>Ajouter un bon d'entrée</b>
                    <span style="cursor: pointer" data-dismiss="modal">
                        <i class="fa fa-times-circle p-2 "></i>
                    </span>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Articles en stock</h4>
                            <p class="text-danger">Cliquez sur un article pour l'ajouter au bon d'entrée.</p>
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
                            <h4>Bon d'entrée</h4>
                            <div class="table-responsive">
                                <form action="#" id="f-bon">
                                    <table tbon class="table table-striped table-hover" style="width: 100%">
                                        <thead>
                                            <th>ARTICLE</th>
                                            <th>QTE</th>
                                            <th>PRIX D'ACHAT</th>
                                            <th>PRIX DE VENTE</th>
                                            <th></th>
                                        </thead>
                                        <tbody items></tbody>
                                        <tfoot class="font-weight-bolder">
                                            <tr title="Somme Prix d'achat * Qte">
                                                <td class="text-nowrap">
                                                    TOTAL BON
                                                </td>
                                                <td colspan="3" class="text-right text-nowrap">
                                                    <span totbon class="badge badge-success" style="font-size: 18px"></span>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </form>
                            </div>
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

    <div class="modal fade" id="mdl-up" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content ">
                <div class="modal-header bg-danger text-white font-weight-bold d-flex justify-content-between">
                    <b>Modification des information</b>
                    <span style="cursor: pointer" data-dismiss="modal">
                        <i class="fa fa-times-circle p-2 "></i>
                    </span>
                </div>
                <form id="f-up" class="was-validated">
                    <input type="hidden" name="id">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Nom</label>
                            <input class="form-control" required name="name" placeholder="Nom" />
                        </div>
                        <div class="form-group">
                            <label for="">Email</label>
                            <input class="form-control" id="email" placeholder="Email" />
                        </div>
                        <div class="form-group">
                            <label for="">Téléphone</label>
                            <input class="form-control phone" id="phone2" placeholder="Telephone" />
                        </div>
                        <div class="form-group">
                            <label for="">Role</label>
                            <select name="user_role" id="" class="form-control">
                                <option value="caissier">Caissier</option>
                                <option value="gerant">Gérant</option>
                            </select>
                        </div>
                        <div class="form-group" style="display: none" id="rep"></div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <div class="">
                            <button class="btn btn-dark btn-reset" type="button">
                                <span></span>
                                Réinitialiser le mot de passe
                            </button>
                        </div>
                        <div class="">
                            <button class="btn btn-dark" data-dismiss="modal">
                                Fermer
                            </button>
                            <button class="btn btn-danger " type="submit">
                                <span></span>
                                Modifier
                            </button>
                        </div>
                    </div>
                </form>
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

    <script>
        $(function() {
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
                                <div class="d-flex">
                                    <input type="number" name="prix_achat[]" min="0.1" step="0.01" value="${data.prix_achat}"
                                    class="form-control" style="min-width: 120px">
                                    <select name="devise_achat[]" devise_achat style="min-width: 80px" id="" class="form-control w-100">
                                        <option>CDF</option>
                                        <option>USD</option>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex">
                                    <input type="number" name="prix_vente[]" min="0.1" step="0.01" value="${data.prix}"
                                    class="form-control" style="min-width: 120px">
                                    <select name="devise_vente[]" devise_vente style="min-width: 80px" id="" class="form-control w-100">
                                        <option>CDF</option>
                                        <option>USD</option>
                                    </select>
                                </div>
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

                    $(`[devise_achat]`, tr).val(data.devise_achat);
                    $(`[devise_vente]`, tr).val(data.devise.devise);
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
                    url: '{{ route('bonentree.index') }}',
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
                                    `<span style="cursor:pointer" title="ce bon d\'entrée est en attente de validation." class="badge badge-warning"><span class="fa fa-check-circle"></span> EN ATTENTE</i>`;
                            }
                            if (e.status == 1) {
                                disabled = `disabled`;
                                stat =
                                    `<span style="cursor:pointer" title="ce bon d\'entrée est validé par ${e.valide_par}." class="badge badge-success"><span class="fa fa-check-circle"></span> VALIDE</i>`;
                            }
                            if (e.status == 2) {
                                disabled = `disabled`;
                                stat =
                                    `<span style="cursor:pointer" title="ce bon d\'entrée est rejeté par ${e.rejete_par}." class="badge badge-danger"><span class="fa fa-check-circle"></span> REJETE</i>`;
                            }


                            var json = escape(e);
                            str += `<tr>
                                        <td>${i+1}</td>
                                        <td>
                                            ${e.numero}
                                        </td>
                                        <td>${e.total_cdf}</td>
                                        <td>${stat}</td>
                                        <td>${e.emetteur}</td>
                                        <td>${e.date}</td>
                                        <td class='d-flex justify-content-center'>
                                            <button class='btn text-muted mr-3' data='${json}'><i class='fa fa-eye'></i></button>
                                            <div class="dropdown ml-2">
                                                <button ${disabled} title="Supprimer : ${e.numero}" class="btn text-danger btn-del dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class='fa fa-trash'></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <button class="dropdown-item delete btn"  value='${e.id}'>Supprimer</button>
                                                </div>
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
                        // init();
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
                if (data.length == 0) {
                    rep.removeClass().addClass('text-danger').html(
                        "Veuillez ajouter au moins un article au bon d'entrée").slideDown();
                    return;
                }
                rep.removeClass().slideUp();

                var btn = $(this);
                btn.find('span').removeClass().addClass('spinner-border spinner-border-sm');
                btn.attr('disabled', true);

                $.ajax({
                    url: '{{ route('bonentree.store') }}',
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
            })


        })
    </script>


@endsection

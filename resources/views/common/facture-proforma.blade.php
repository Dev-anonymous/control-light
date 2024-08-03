@extends('layouts.main')
@section('title', 'Modèles de factures')

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
            <div class="card">
                <div class="card-header">
                    <h3 class="h4 font-weight-bold">Nouvelle factures : <b>Modèle #{{ $modele->id }}</b></h4>
                </div>
            </div>
            <form action="#" id="form1" class="was-validated">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <p class="font-weight-bold">Information de l'entreprise</p>
                                <div class="form-group mb-1">
                                    <label for="">Entreprise</label>
                                    <input name="nom_entreprise" type="text" placeholder="Entreprise"
                                        value="{{ $shop->shop }}" class="form-control form-control-sm" required>
                                </div>
                                <div class="form-group mb-1">
                                    <label for="">Adresse</label>
                                    <input name="adresse_entreprise" type="text" placeholder="Adresse"
                                        value="{{ $shop->adresse }}" class="form-control form-control-sm" required>
                                </div>
                                <div class="form-group mb-1">
                                    <label for="">Email</label>
                                    <input name="email_entreprise" type="email" placeholder="Email"
                                        value="{{ $email }}" class="form-control form-control-sm" required>
                                </div>
                                <div class="form-group mb-1">
                                    <label for="">Tel.</label>
                                    <input name="telephone_entreprise" type="tel" placeholder="Telephone"
                                        value="{{ $shop->contact }}" class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">Information du client</p>
                                <div class="form-group mb-1">
                                    <label for="">Sélectionnez un Client</label>
                                    <select name="client_id" class="select2 d-flex form-control rounded-0 p-0 selclient">
                                        @foreach ($clients as $el)
                                            <option value="{{ $el->id }}" client='{{ json_encode($el) }}'>
                                                {{ $el->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group mb-1">
                                    <label for="">Client</label>
                                    <input name="nom_client" type="text" placeholder="Client"
                                        class="form-control form-control-sm" required>
                                </div>
                                <div class="form-group mb-1">
                                    <label for="">Adresse</label>
                                    <input name="adresse_client" type="text" placeholder="Adresse"
                                        class="form-control form-control-sm" required>
                                </div>
                                <div class="form-group mb-1">
                                    <label for="">Email</label>
                                    <input name="email_client" type="email" placeholder="Email"
                                        class="form-control form-control-sm">
                                </div>
                                <div class="form-group mb-1">
                                    <label for="">Tel.</label>
                                    <input name="telephone_client" type="tel" placeholder="Telephone"
                                        class="form-control form-control-sm" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <p class="font-weight-bold">Autres informations</p>
                                <div class="form-group mb-1">
                                    <label for="">Mode de règlement</label> <br>
                                    <select name="mode_reglement" class="select2 form-control d-flex">
                                        <option value="">-</option>
                                        <option selected>CASH</option>
                                        <option>MOBILE MONEY</option>
                                        <option>CARTE DE CREDIT</option>
                                    </select>
                                </div>
                                <div class="form-group mb-1">
                                    <label for="">Condition de règlement</label>
                                    <input name="condition_reglement" type="text" placeholder="Condition de règlement"
                                        class="form-control form-control-sm">
                                </div>
                                <div class="form-group mb-1">
                                    <label for="">Date limite du règlement</label>
                                    <input name="date_reglement" placeholder="Date limite du règlement"
                                        class="form-control form-control-sm datepicker">
                                </div>
                                <div class="form-group mb-1">
                                    <label for="">Note supplémentaire</label>
                                    <textarea name="note_reglement" maxlength="600" name="" class="form-control" placeholder="Note supplémentaire"
                                        id="" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group mb-1">
                                    <label for="">Devise de la facutre</label> <br>
                                    <select name="devise" id="" class="select2 form-control d-flex">
                                        <option>CDF</option>
                                        <option>USD</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h3 class="h4 font-weight-bold">Articles de la facture</h3>
                    <div class="card-header-action">
                        <div class="form-group m-2 d-block">
                            <button class="btn btn-danger" data-toggle='modal' data-target='#mdl-add'
                                style="border-radius: 5px!important;">
                                <i class="fa fa-plus-circle"></i>
                                Ajouter les articles
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <form action="#" id="form2">
                                    <table class="table table-striped table-hover" style="width: 100%">
                                        <thead>
                                            <th>ARTICLE</th>
                                            <th>PRIX UNITAIRE</th>
                                            <th>QTE</th>
                                            <th></th>
                                        </thead>
                                        <tbody items></tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h3 class="h4 font-weight-bold">Facture</h3>
                        </div>
                        <div class="card-body">
                            <div class="w-100 text-center">
                                <b spinner style="display: none" class="spinner-border spinner-boder text-danger"></b>
                            </div>
                            <div class="table-responsive">
                                <div style="width: 21cm !important">
                                    <iframe src="" id="iframe" style="height: 31cm" width="100%"
                                        frameborder="0"></iframe>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="card-footer d-flex justify-content-end">
                            <button class="btn btn-danger" data-toggle='modal' data-target='#mdl-save'>
                                <i class="fa fa-save"></i>
                                Confirmer la facture
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="modal fade" id="mdl-add" tabindex="-1">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content ">
                    <div class="modal-header bg-danger text-white font-weight-bold d-flex justify-content-between">
                        <b>Liste des articles</b>
                        <span style="cursor: pointer" data-dismiss="modal">
                            <i class="fa fa-times-circle p-2 "></i>
                        </span>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table table class="table table-striped table-hover" style="width: 100%">
                                <thead>
                                    <th></th>
                                    <th>ARTICLE</th>
                                    <th>PRIX UNITAIRE</th>
                                    <th>STOCK ACTUEL</th>
                                    <th></th>
                                </thead>
                                <tbody>
                                    @foreach ($articles as $k => $el)
                                        <tr>
                                            <td>{{ $k + 1 }}</td>
                                            <td>{{ $el->article }}</td>
                                            <td>{{ $el->prix }}</td>
                                            <td>{{ "$el->stock $el->unite_mesure" }}</td>
                                            <td>
                                                <button value="{{ json_encode($el) }}"
                                                    class="btn btn-sm btn-outline-danger badd">
                                                    <i class="fa fa-plus-circle"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark" data-dismiss="modal">
                            Fermer
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="mdl-save" tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content ">
                    <div class="modal-header bg-danger text-white font-weight-bold d-flex justify-content-between">
                        <b>Enregistrer la facture</b>
                        <span style="cursor: pointer" data-dismiss="modal">
                            <i class="fa fa-times-circle p-2 "></i>
                        </span>
                    </div>
                    <div class="modal-body text-center">
                        <b>confirmez l'enregistrement de la facture ? </b>
                        <p><i class="text-danger">Vous ne pouvez plus modifier cette facture après enregistrement.</i></p>
                        <div id="rep"></div>
                        <div id="rep3"></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark" data-dismiss="modal">
                            NON
                        </button>
                        <button class="btn btn-danger bsave">
                            <span></span>
                            OUI
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-code')
    <link rel="stylesheet" href="{{ asset('assets/datatables/datatables.min.css') }}" />
    <script src="{{ asset('assets/datatables/datatables.min.js') }}"></script>

    <script src="{{ asset('assets/js/daterangepicker/moment.js') }}"></script>
    <script src="{{ asset('assets/js/daterangepicker/daterangepicker.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/js/daterangepicker/daterangepicker.css') }}">

    <script>
        $(function() {
            $('.datepicker').daterangepicker({
                minYear: '{{ date('Y') }}',
                showDropdowns: true,
                autoUpdateInput: false,
                locale: {
                    format: 'YYYY/MM/DD'
                },
                singleDatePicker: true,
                minDate: "{{ date('Y-m-d') }}"
            }, function(sd) {
                $('.datepicker').val(sd.format('YYYY/MM/DD'))
            });


            opt = {
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
            };

            var selclient = $('.selclient');
            selclient.change(function() {
                fillclient();
            })

            function fillclient() {
                var v = JSON.parse($(':selected', selclient).attr('client'));
                var form = $('#form1');
                $('[name=nom_client]', form).val(v.name);
                $('[name=telephone_client]', form).val(v.phone);
                $('[name=email_client]', form).val(v.email);
                $('[name=adresse_client]', form).val(v.adresselivraison);
            }
            fillclient();
            var items = $('[items]');

            function insertItem(data, restrore = false) {
                var id = data.id;
                var tr = $(`tr[tr-${id}]`);

                if (restrore) {
                    var q = data.qte ?? 1;
                    tr = `<tr tr-${data.id}>
                            <input type="hidden" name="articles[]" value='${data.id}' />
                            <input type="hidden" name="qtes[]" value='${q}' iqte-${data.id} />
                            <td>${data.article}</td>
                            <td>${data.prix}</td>
                            <td tr-qte-${data.id}>${q}</td>
                            <td>
                                <button value="${data.id}"
                                    class="btn btn-outline-danger bremove">
                                    <i class="fa fa-times-circle"></i>
                                </button>
                            </td>
                        </tr>`;
                    tr = $(tr);
                    items.append(tr);
                    return false;
                }

                if (tr.length) {
                    var q = Number($(`[tr-qte-${data.id}]`, tr).html()) + 1;
                    $(`[tr-qte-${data.id}]`, tr).html(q);
                    $(`[iqte-${data.id}]`, tr).val(q);
                } else {
                    var q = 1;
                    tr = `<tr tr-${data.id}>
                            <input type="hidden" name="articles[]" value='${data.id}' />
                            <input type="hidden" name="qtes[]" value='${q}' iqte-${data.id} />
                            <td>${data.article}</td>
                            <td>${data.prix}</td>
                            <td tr-qte-${data.id}>${q}</td>
                            <td>
                                <button value="${data.id}"
                                    class="btn btn-outline-danger bremove">
                                    <i class="fa fa-times-circle"></i>
                                </button>
                            </td>
                        </tr>`;
                    tr = $(tr);
                    items.append(tr);
                }

                var ar = localStorage.getItem('factureItems');
                if (ar) {
                    ar = JSON.parse(ar);
                } else {
                    ar = [data];
                }
                var tmp = [];
                var find = false;
                for (var e in ar) {
                    e = ar[e];
                    if (e.id == data.id) {
                        e.qte = q;
                        find = true;
                    }
                    tmp.push(e);
                }

                if (!find) {
                    tmp.push(data);
                }
                ar = tmp;
                localStorage.setItem('factureItems', JSON.stringify(ar));
            }

            function restoreInvoice() {
                var ar = localStorage.getItem('factureItems');
                if (ar) {
                    ar = JSON.parse(ar);
                } else {
                    ar = [];
                }
                ar.forEach(function(e) {
                    insertItem(e, true);
                });
                initRem();
            }

            restoreInvoice();

            $('.badd').click(function() {
                var btn = $(this);
                var val = this.value;
                var data = JSON.parse(val);

                insertItem(data);

                btn.find('i').removeClass().addClass('fa fa-check-circle fa-2x text-success');
                setTimeout(() => {
                    btn.find('i').removeClass().addClass('fa fa-plus-circle');
                }, 500);
                initRem();
                preview();
            });

            function initRem() {
                $('.bremove').off('click').click(function() {
                    var id = this.value;
                    $(`tr[tr-${id}]`).remove();
                    var ar = localStorage.getItem('factureItems');
                    if (ar) {
                        ar = JSON.parse(ar);
                        var tmp = [];
                        ar.forEach(function(e) {
                            if (e.id != id) {
                                tmp.push(e);
                            }
                        });
                        localStorage.setItem('factureItems', JSON.stringify(tmp));
                    }

                    preview();
                })
            }
            $('[table]').DataTable(opt);

            function preview() {
                $('b[spinner]').show();
                var data1 = $('#form1').serialize();
                var data2 = $('#form2').serialize();
                var data = data1 + '&' + data2;
                $.ajax({
                    url: '{{ route('proforma.preview', $modele->id) }}',
                    data
                }).done(function(res) {
                    $('#iframe').contents().find('body').html(res);
                    $('b[spinner]').fadeOut();
                }).fail(function(res) {
                    $('#iframe').contents().find('body').html('');
                    $('b[spinner]').fadeOut();
                })
            }
            preview();
            $('#form1').change(function() {
                preview();
            });

            $('.bsave').click(function() {
                event.preventDefault();

                var stop = false
                $(':input[required]', $('#form1')).each(function(i, e) {
                    if ($(this).val() == '') {
                        stop = true;
                        return;
                    }
                });

                if (stop) {
                    $('.modal.show').modal('hide');
                    alert("Veuillez completer tous champs en rouge dans le formulaire");
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#form1").offset().top
                    }, 2000);
                    return;
                }

                stop = $(':input', $('#form2')).length == 0;

                if (stop) {
                    $('.modal.show').modal('hide');
                    alert("Veuillez ajouter au moins un article");
                    $([document.documentElement, document.body]).animate({
                        scrollTop: $("#form2").closest('.card').offset().top - 100
                    }, 2000);
                    return;
                }

                var btn = $(this).attr('disabled', true);
                btn.find('span').removeClass().addClass('spinner-border spinner-border-sm');
                rep = $('#rep');
                rep.removeClass().slideUp();
                var data1 = $('#form1').serialize();
                var data2 = $('#form2').serialize();
                var data = data1 + '&' + data2 + '&proforma_id={{ $proforma_id }}';
                $.ajax({
                    url: '{{ route('proforma.store') }}',
                    type: 'post',
                    data: data,
                    timeout: 20000,
                }).done(function(res) {
                    data = res.data;
                    if (res.success == true) {
                        var m = res.message;
                        rep.addClass('alert alert-success w-100 text-left mt-2').html(m);
                        $('#form1')[0].reset();
                        $('#form2')[0].reset();
                        btn.remove();
                        var url = '{{ route('proforma') }}?nf=' + data.numero_facture;
                        var a =
                            `<a class='btn btn-link' href='${url}'>Afficher ou imprimer la facture</a>`;
                        $('#rep3').html(a);
                        localStorage.setItem('factureItems', '[]');

                    } else {
                        var m = res.message;
                        rep.addClass('alert alert-danger w-100 text-left mt-2').html(m);
                    }
                    rep.slideDown();
                    btn.attr('disabled', false);
                    btn.find('span').removeClass();
                }).fail(function() {
                    btn.attr('disabled', false);
                    btn.find('span').removeClass();
                    rep.addClass('alert alert-danger w-100 text-left mt-2').html(
                        'Erreur, veuillez reessayer');
                    rep.slideDown();
                }).always(function(res) {
                    if (res.status == 403 || res.status == 401) {
                        var json = res.responseJSON;
                        var m = json.message ?? res.statusText;
                        rep.addClass(`alert alert-danger w-100`).html(m);
                        rep.slideDown();
                        btn.find('span').removeClass();
                        btn.attr('disabled', false);
                    }
                });;
            })


        })
    </script>
@endsection

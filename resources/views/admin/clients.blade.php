@extends('layouts.main')
@section('title', 'Clients')

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
                    <h3 class="h4 font-weight-bold">Clients</h3>
                    <div class="card-header-action">
                        <div class="form-group m-2 d-block">
                            <button class="btn btn-danger" data-toggle='modal' data-target='#mdl-add'
                                style="border-radius: 5px!important;">
                                Ajouter un client
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
                                            <th>Nom(Dénomination)</th>
                                            <th>Email</th>
                                            <th>Téléphone</th>
                                            <th>Adresse client</th>
                                            <th>Adresse livraison</th>
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
        </div>
    </div>
    <div class="modal fade" id="mdl-add" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content ">
                <div class="modal-header bg-danger text-white font-weight-bold d-flex justify-content-between">
                    <b>Ajouter un client</b>
                    <span style="cursor: pointer" data-dismiss="modal">
                        <i class="fa fa-times-circle p-2 "></i>
                    </span>
                </div>
                <form id="f-add" class="was-validated">
                    <input type="hidden" name="type" value="client">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Nom ou dénomination</label>
                            <input class="form-control" required name="name" placeholder="Nom" />
                        </div>
                        <div class="form-group">
                            <label for="">Email</label>
                            <input class="form-control" id="email" placeholder="Email" />
                        </div>
                        <div class="form-group">
                            <label for="">Téléphone</label>
                            <input class="form-control phone" required id="phone" placeholder="Telephone" />
                        </div>
                        <div class="form-group">
                            <label for="">Adresse du client</label>
                            <input class="form-control" required name="adresse" placeholder="Adresse" />
                        </div>
                        <div class="form-group">
                            <label for="">Adresse de livraison</label>
                            <input class="form-control" required name="adresselivraison" placeholder="Adresse de livraison" />
                        </div>
                        <div class="form-group" style="display: none" id="rep"></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark" data-dismiss="modal">
                            Fermer
                        </button>
                        <button class="btn btn-danger " type="submit">
                            <span></span>
                            Ajouter
                        </button>
                    </div>
                </form>
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
                    <input type="hidden" name="type" value="client">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Nom ou dénomination</label>
                            <input class="form-control" required name="name" placeholder="Nom" />
                        </div>
                        <div class="form-group">
                            <label for="">Email</label>
                            <input class="form-control" id="email" placeholder="Email" />
                        </div>
                        <div class="form-group">
                            <label for="">Téléphone</label>
                            <input class="form-control phone" required id="phone2" placeholder="Telephone" />
                        </div>
                        <div class="form-group">
                            <label for="">Adresse du client</label>
                            <input class="form-control" required name="adresse" placeholder="Adresse" />
                        </div>
                        <div class="form-group">
                            <label for="">Adresse de livraison</label>
                            <input class="form-control" required name="adresselivraison" placeholder="Adresse de livraison" />
                        </div>
                        <div class="form-group" style="display: none" id="rep"></div>
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
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
    <link rel="stylesheet" href="{{ asset('assets/datatables/datatables.min.css') }}" />
    <script src="{{ asset('assets/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/datatables/datatables.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/phone/intlTelInput.css') }}">
    <script src="{{ asset('assets/phone/intlTelInput.js') }}"></script>
    <script src="{{ asset('assets/phone/jquery.mask.min.js') }}"></script>
    <style>
        .iti--separate-dial-code {
            width: 100% !important
        }
    </style>
    <script>
        $(function() {
            $(".phone").mask('0000000000000');
            intlTelInput($("#phone")[0], {
                preferredCountries: ["cd"],
                initialCountry: "cd",
                separateDialCode: true,
            });
            iti = intlTelInput($("#phone2")[0], {
                preferredCountries: ["cd"],
                initialCountry: "cd",
                separateDialCode: true,
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
                `<tr><td class="text-center" colspan="7"><span class="spinner-border text-danger"></span></td></tr>`;
            var table = $('#t-unite');
            getData();

            function getData() {
                table.find('tbody').html(spin);
                $.ajax({
                    url: '{{ route('caissier.index') }}',
                    data: {
                        'user_role': 'client'
                    },
                    timeout: 20000,
                }).done(function(res) {
                    data = res.data;
                    str = '';
                    if (data.length > 0) {
                        $(data).each(function(i, e) {
                            var json = {
                                id: e.id,
                                name: e.name,
                                phone: e.phone ?? '',
                                email: e.email ?? '',
                                adresse: e.adresse ?? '',
                                adresselivraison: e.adresselivraison ?? '',
                            };
                            json = JSON.stringify(json);

                            str += `<tr>
                                        <td>${i+1}</td>
                                        <td>
                                            ${e.name}
                                            <span app-label-msg='${e.id}' class='ml-3'></span>
                                        </td>
                                        <td>${e.email ?? '-'}</td>
                                        <td>${e.phone??'-'}</td>
                                        <td>${e.adresse??'-'}</td>
                                        <td>${e.adresselivraison??'-'}</td>
                                        <td class='d-flex justify-content-center'>
                                            <button data-toggle='tooltip' title='Modifier' class='btn text-muted mr-3 btn-edit' data='${json}' value='${e.id}'><i class='fa fa-edit'></i></button>
                                            <div class="dropdown ml-2">
                                                <button title="Supprimer : ${e.name}" class="btn text-danger btn-del dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class='fa fa-trash'></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <button class="dropdown-item delete btn"  value='${e.id}'>Supprimer</button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>`;
                        });
                        table.find('tbody').html('');
                        table.DataTable().destroy();
                        table.find('tbody').html(str);
                        $('[data-toggle=tooltip]').off('tooltip').tooltip();
                        $('body').tooltip({
                            selector: '[data-toggle="tooltip"]'
                        }).click(function() {
                            $('[data-toggle="tooltip"]').tooltip("hide");
                        });
                        init();
                        table.DataTable(opt);
                    } else {
                        str =
                            '<tr><td colspan="7" class="text-danger font-weight-bolder text-center">Aucune donnée</td></tr>';
                        table.find('tbody').html('');
                        table.DataTable().destroy();
                        table.find('tbody').html(str);
                    }

                })
            }

            function init() {
                $('.btn-edit').off('click').click(function() {
                    var id = this.value;
                    var data = $(this).attr('data');
                    var mdl = $('#mdl-up');
                    mdl.modal();
                    data = JSON.parse(data);
                    var form = $('form', mdl);
                    iti.destroy();
                    $('input[name=id]', form).val(data.id);
                    $('.btn-reset', form).val(data.id);
                    $('input[name=name]', form).val(data.name);
                    $('input[id=email]', form).val(data.email);
                    $('input[id=phone2]', form).val(data.phone);
                    $('[name=adresse]', form).val(data.adresse);
                    $('[name=adresselivraison]', form).val(data.adresselivraison);
                    console.log(data);
                    iti = intlTelInput($("#phone2")[0], {
                        preferredCountries: ["cd"],
                        separateDialCode: true
                    });
                });

                $('.delete').off('click').click(function() {
                    var id = this.value;
                    var val = $('[app-input=' + id + ']').val();
                    var btn = $(this)
                    btn = $(btn).closest('.dropdown');
                    btn = $(btn).find('.btn-del');
                    btn.find('i').removeClass().addClass('spinner-border spinner-border-sm');

                    var tr = btn.closest('tr');
                    $('button', tr).attr('disabled', true);
                    var span = $('[app-label-msg=' + id + ']').removeClass().html('');

                    $.ajax({
                        url: '{{ route('caissier.destroy', '') }}/' + id,
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

                $('.btn-default').off('click').click(function() {
                    var id = this.value;
                    var btn = $(this);
                    var to = $(this).attr('to');
                    btn.find('i').removeClass().addClass('spinner-border spinner-border-sm');

                    var tr = btn.closest('tr');
                    $('button', tr).attr('disabled', true);
                    var span = $('[app-label-msg=' + id + ']').removeClass().html('');

                    $.ajax({
                        url: '{{ route('caissier.update', '') }}/' + id,
                        type: 'put',
                        data: {
                            to: to,
                            default: 'yes'
                        },
                        timeout: 20000,
                    }).done(function(res) {
                        data = res.data;
                        var m = res.message;
                        span.addClass('ml-2 text-success').html(m);
                        setTimeout(() => {
                            getData();
                        }, 2000);
                    });

                });
            }

            $('#f-add').submit(function() {
                event.preventDefault();
                var form = $(this);
                var btn = $(':submit', form).attr('disabled', true);
                btn.find('span').removeClass().addClass('spinner-border spinner-border-sm mr-3');
                rep = $('#rep', form);
                rep.removeClass().slideUp();

                var dial = $('.iti__selected-dial-code', form).html();

                var phone = $('#phone', form);
                var email = $('#email', form);
                var data = form.serialize();

                if (phone.val().length > 0) {
                    data = data + '&phone=' + encodeURIComponent(dial + '' + phone.val());
                }
                if (email.val().length > 0) {
                    data = data + '&email=' + email.val();
                }

                $.ajax({
                    url: '{{ route('caissier.store') }}',
                    type: 'post',
                    data: data,
                    timeout: 20000,
                }).done(function(res) {
                    data = res.data;
                    if (res.success == true) {
                        form.get(0).reset();
                        var m = res.message;
                        rep.addClass('alert alert-success w-100').html(m);
                        setTimeout(() => {
                            getData();
                        }, 2000);
                    } else {
                        var m = res.message;
                        try {
                            m += '<br>' + res.data.msg.join('<br>');
                        } catch (error) {}
                        rep.addClass('alert alert-danger w-100').html(m);
                    }
                    rep.slideDown();
                    btn.attr('disabled', false);
                    btn.find('span').removeClass();
                });
            })

            $('#f-up').submit(function() {
                event.preventDefault();
                var form = $(this);
                var btn = $(':submit', form).attr('disabled', true);
                btn.find('span').removeClass().addClass('spinner-border spinner-border-sm mr-3');
                rep = $('#rep', form);
                rep.removeClass().slideUp();

                var dial = $('.iti__selected-dial-code', form).html();

                var phone = $('#phone2', form);
                var email = $('#email', form);
                var data = form.serialize();

                if (phone.val().length > 0) {
                    data = data + '&phone=' + encodeURIComponent(dial + '' + phone.val());
                }
                if (email.val().length > 0) {
                    data = data + '&email=' + email.val();
                }

                var id = $('[name=id]', form).val();
                $.ajax({
                    url: '{{ route('caissier.update', '') }}/' + id,
                    type: 'put',
                    data: data,
                    timeout: 20000,
                }).done(function(res) {
                    data = res.data;
                    if (res.success == true) {
                        var m = res.message;
                        rep.addClass('alert alert-success w-100').html(m);
                        setTimeout(() => {
                            getData();
                        }, 2000);
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
                    }, 2000);
                    btn.attr('disabled', false);
                    btn.find('span').removeClass();
                });
            });

            $('.btn-reset').click(function() {
                event.preventDefault();
                var btn = $(this);
                btn.find('span').removeClass().addClass('spinner-border spinner-border-sm mr-3');
                rep = $('#rep', $('#f-up'));
                rep.removeClass().slideUp();
                var id = this.value;
                btn.attr('disabled', true);

                $.ajax({
                    url: '{{ route('caissier.update', '') }}/' + id,
                    type: 'put',
                    data: {
                        action: 'reset'
                    },
                    timeout: 20000,
                }).done(function(res) {
                    data = res.data;
                    var m = res.message;
                    rep.addClass('alert alert-success w-100').html(m);
                    rep.slideDown();
                    setTimeout(() => {
                        rep.slideUp();
                    }, 2000);
                    btn.attr('disabled', false);
                    btn.find('span').removeClass();
                });
            })
        })
    </script>


@endsection

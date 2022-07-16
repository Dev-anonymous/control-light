@extends('layouts.main')
@section('title', 'Cassiers')

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
                    <h4>Cassiers</h4>
                    <div class="card-header-action">
                        <div class="form-group m-2 d-block">
                            <button class="btn btn-danger" data-toggle='modal' data-target='#mdl-add'
                                style="border-radius: 5px!important;">
                                Ajouter un cassier
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="t-unite" class="table table-condensed table-bordered table-hover"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Nom du caissier</th>
                                            <th>Email</th>
                                            <th>Téléphone</th>
                                            <th>Date dernière activité</th>
                                            <th>Etat</th>
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
                                Vous pouvez créé plusieurs comptes des cassiers, ils auront pour but d'enregistrer les
                                ventes dans l'application.
                            </p>
                            <p class="text-muted">
                                <i class="fa fa-exclamation-triangle text-warning"></i>
                                Un caissier peut se connecter à l'application avec son email ou son numéro de téléphone.
                            </p>
                            <p class="text-muted">
                                <i class="fa fa-exclamation-triangle text-warning"></i>
                                Si l'état du compte du caissier est 'bloqué' cela veut dire qu'il ne peut pas se connecter à
                                l'application.
                            </p>
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
                    <b>Ajouter un caissier</b>
                    <span style="cursor: pointer" data-dismiss="modal">
                        <i class="fa fa-times-circle p-2 "></i>
                    </span>
                </div>
                <form id="f-add" class="was-validated">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Nom du caisssier</label>
                            <input class="form-control" required name="name" placeholder="Nom du caissier" />
                        </div>
                        <div class="form-group">
                            <label for="">Email</label>
                            <input class="form-control" id="email" placeholder="Email" />
                        </div>
                        <div class="form-group">
                            <label for="">Téléphone</label>
                            <input class="form-control phone" id="phone" placeholder="Telephone" />
                        </div>
                        <div class="form-group">
                            <label for="">Mot de passe</label>
                            <input class="form-control" required name="password" placeholder="Mot de passe" />
                        </div>
                        <div class="form-group" style="display: none" id="rep"></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-dismiss="modal">
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
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Nom du caisssier</label>
                            <input class="form-control" required name="name" placeholder="Nom du caissier" />
                        </div>
                        <div class="form-group">
                            <label for="">Email</label>
                            <input class="form-control" id="email" placeholder="Email" />
                        </div>
                        <div class="form-group">
                            <label for="">Téléphone</label>
                            <input class="form-control phone" id="phone2" placeholder="Telephone" />
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
                            <button class="btn btn-secondary" data-dismiss="modal">
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
                stateSave: !0,
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
                    timeout: 20000,
                }).done(function(res) {
                    data = res.data;
                    str = '';
                    if (data.length > 0) {
                        $(data).each(function(i, e) {
                            if (e.actif == 1) {
                                var def =
                                    '<i class="font-weight-bold text-muted"><span class="fa fa-check-circle text-success"></span> Actif</i>';
                                var v = 0;
                                var btn =
                                    `<button data-toggle='tooltip' title='Bloqué le compte du caissier ${e.name}' class='btn text-muted mr-3 btn-default' value='${e.id}' to='${v}'><i class='fa fa-ban text-danger'></i></button>`;
                            } else {
                                var def =
                                    '<i class="font-weight-bold text-danger"><span class="fa fa-ban text-danger"></span> Bloqué</i>';
                                var v = 1;
                                var btn =
                                    `<button data-toggle='tooltip' title='Débloqué le compte du caissier ${e.name}' class='btn text-muted mr-3 btn-default' value='${e.id}' to='${v}'><i class='fa fa-ban text-success'></i></button>`;
                            }

                            var json = {
                                id: e.id,
                                name: e.name,
                                phone: e.phone ?? '',
                                email: e.email ?? ''
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
                                        <td>${e.derniere_activite??'-'}</td>
                                        <td>${def}</td>
                                        <td class='d-flex justify-content-center'>
                                            <button data-toggle='tooltip' title='Modifier' class='btn text-muted mr-3 btn-edit' data='${json}' value='${e.id}'><i class='fa fa-edit'></i></button>
                                            ${btn}
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
                        table.find('tbody').html(
                            '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>');
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
                            '<tr><td colspan="7" class="text-danger font-weight-bolder text-center">Aucun compte caissier</td></tr>';
                        table.find('tbody').html(
                            '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>');
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

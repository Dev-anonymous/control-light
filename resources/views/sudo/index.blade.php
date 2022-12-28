@extends('layouts.main')
@section('title', 'Super admin')

@section('body')
    <div class="loader"></div>
    <div>
        <div class="main-wrapper main-wrapper-1">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar sticky">
                <div class="form-inline mr-auto">
                    <ul class="navbar-nav mr-3">
                        <li>
                            <a href="#" data-toggle="sidebar" class="nav-link nav-link-lg collapse-btn">
                                <i data-feather="align-justify" class="text-danger"></i>
                            </a>
                        </li>
                        <li>
                            <a href="#" class="nav-link nav-link-lg fullscreen-btn">
                                <i data-feather="maximize" class="text-danger"></i>
                            </a>
                        </li>
                    </ul>
                </div>
                <ul class="navbar-nav navbar-right">
                    <li class="dropdown">
                        <a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            <span class="text-muted"><i class="fa fa-user"></i> {{ auth()->user()->name }} </span>
                            <span class="d-sm-none d-lg-inline-block"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right pullDown">
                            <div class="dropdown-title">
                                {{ auth()->user()->name . ' (' . auth()->user()->user_role . ')' }}
                            </div>
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('logout.web') }}" class="dropdown-item has-icon text-danger">
                                <i class="fas fa-sign-out-alt"></i>
                                Déconnexion
                            </a>
                        </div>
                    </li>
                </ul>
            </nav>

            <div class="main-sidebar sidebar-style-2">
                <aside id="sidebar-wrapper">
                    <div class="sidebar-brand">
                        <a>
                            <img alt="" src="{{ asset('assets/img/logo.png') }}" class="header-logo" />
                        </a>
                    </div>
                    <ul class="sidebar-menu">
                        <li class="dropdown">
                            <a href="{{ route('accueil.super-admin') }}" class="nav-link">
                                <i class="fa fa-home"></i><span>Accueil</span>
                            </a>
                        </li>
                    </ul>
                </aside>

            </div>
        </div>
        @php
            $compte = \App\Models\Compte::orderby('id', 'desc')->get();
            $n = 1;
        @endphp
        <div class="main-content">
            <div class="card ">
                <div class="card-header">
                    <h4>Compte</h4>
                    <div class="card-header-action">
                        <button class="btn btn-danger" data-toggle='modal' data-target='#mdl-add'
                            style="border-radius: 5px!important;">
                            Ajouter un compte
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="t-data" class="table table-condensed table-bordered table-hover"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Client</th>
                                            <th>Contact</th>
                                            <th>Magasin</th>
                                            <th>Dernière connexion</th>
                                            <th>Date création</th>
                                            <th>Type</th>
                                            <th>Backup</th>
                                            <th>Etat</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($compte as $item)
                                            @php
                                                $da = '-';
                                                $u = \App\Models\User::where('compte_id', $item->id)->first();
                                                if (@$u->derniere_activite) {
                                                    $da = $u->derniere_activite->format('d-m-Y H:i:s');
                                                }
                                                if (@$u->actif == 1) {
                                                    $def = '<i class="font-weight-bold text-muted"><span class="fa fa-check-circle text-success"></span> Actif</i>';
                                                    $v = 0;
                                                    $btn = "<button data-toggle='tooltip' title='Bloqué le compte de $u->name' class='btn-bloque btn btn-block text-muted mr-3 btn-default' value='$item->id' to='$v'><i class='fa fa-ban text-danger'></i> Bloquer</button>";
                                                } else {
                                                    $def = '<i class="font-weight-bold text-danger"><span class="fa fa-ban text-danger"></span> Bloqué</i>';
                                                    $v = 1;
                                                    $btn = "<button data-toggle='tooltip' title='Débloqué le compte de $u->name' class='btn-bloque btn btn-block text-muted mr-3 btn-default' value='$item->id' to='$v'><i class='fa fa-ban text-success'></i> Débloquer</button>";
                                                }

                                            @endphp
                                            <tr>
                                                <td>{{ $n++ }}</td>
                                                <td>{{ $item->client }}</td>
                                                <td>{!! $item->phone . '<br>' . $item->email !!}</td>
                                                <td>{{ $item->magasin }}</td>
                                                <td>{{ $da }}</td>
                                                <td>{{ $item->date_creation->format('d-m-Y H:i:s') }}</td>
                                                <td>{{ $item->type }}</td>
                                                <td></td>
                                                <th class="text-center">{!! $def !!}</th>
                                                <td class='d-flex justify-content-center'>
                                                    <div class="dropdown ml-2">
                                                        <button class="btn text-danger btn-del dropdown-toggle"
                                                            type="button" data-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class='fa fa-cogs'></i>
                                                        </button>
                                                        <div class="dropdown-menu" aria-labelledby="">
                                                            {!! $btn !!}
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
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
        <div class="modal-dialog   modal-lg" role="document">
            <div class="modal-content ">
                <div class="modal-header bg-danger text-white font-weight-bold d-flex justify-content-between">
                    <b>Création compte</b>
                    <span style="cursor: pointer" data-dismiss="modal">
                        <i class="fa fa-times-circle p-2 "></i>
                    </span>
                </div>
                <form id="f-add" class="was-validated">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Client</label>
                            <input class="form-control" placeholder="Client" name="client" required />
                        </div>
                        <div class="form-group">
                            <label for="">Magasin</label>
                            <input class="form-control" placeholder="Magasin" name="magasin" required />
                        </div>
                        <div class="form-group">
                            <label for="">Email</label>
                            <input class="form-control" type="email" id="email" placeholder="Email" />
                        </div>
                        <div class="form-group">
                            <label for="">Telephone</label>
                            <input class="form-control" type="tel" id="phone" placeholder="Telephone" />
                        </div>
                        <div class="form-group">
                            <label for="">Mot de passe</label>
                            <input class="form-control" name="password" placeholder="Mot de passe" required
                                autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label for="">Type</label>
                            <select name="type" class="form-control">
                                <option value="online">Online</option>
                                <option value="local">Local</option>
                            </select>
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
@endsection

@section('js-code')
    <link rel="stylesheet" href="{{ asset('assets/datatables/datatables.min.css') }}" />
    <script src="{{ asset('assets/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/datatables/datatables.min.js') }}"></script>
    <script>
        $(function() {
            $('.table').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'pageLength', 'excel', 'pdf', 'print'
                ],
                "lengthMenu": [
                    [10, 25, 50, 100, -1],
                    [10, 25, 50, 100, "All"]
                ],
            });

            $('#f-add').submit(function() {
                event.preventDefault();
                var form = $(this);
                var d = form.serialize();
                var btn = $(':submit', form).attr('disabled', true);
                btn.find('span').removeClass().addClass('spinner-border spinner-border-sm');
                $(':input', form).attr('disabled', true);
                rep = $('#rep', form);
                rep.removeClass().slideUp();

                var email = $('#email', form).val();
                var phone = $('#phone', form).val();

                if (email.length > 0) {
                    d += "&email=" + email;
                }
                if (phone.length > 0) {
                    d += "&phone=" + encodeURIComponent(phone);
                }

                $.ajax({
                    url: '{{ route('add-user.sudo.api') }}',
                    data: d,
                    type: 'post',
                    timeout: 20000,
                }).done(function(res) {
                    var data = res.data;
                    if (res.success == true) {
                        form.get(0).reset();
                        var m = res.message;
                        rep.addClass('alert alert-success w-100').html(m);
                        setTimeout(() => {
                            location.reload();
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
                    $(':input', form).attr('disabled', false);

                })
            })

            $('.btn-bloque').off('click').click(function() {
                var btn = $(this);
                var id = this.value;
                var to = btn.attr('to');

                btn = $(btn).closest('.dropdown');
                var tr = btn.closest('tr');
                $('button', tr).attr('disabled', true);

                $.ajax({
                    url: '{{ route('access.sudo.api') }}',
                    type: 'post',
                    data: {
                        compteid: id,
                        to: to
                    },
                    timeout: 20000,
                }).done(function(res) {
                    location.reload();
                });
            });

        })
    </script>
@endsection

@extends('layouts.main')
@section('title', "Catégorie d'articles")

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
            <div class="card ">
                <div class="card-header">
                    <h4>Catégorie d'articles</h4>
                </div>
                @if (!$groupe->count())
                    <div class="card-header d-flex justify-content-center">
                        <b class="text-danger">
                            <i class="fa fa -info text-danger"></i> Vous devez d'abord ajouté un
                            <a class="text-muted" href="{{ route('groupe-article.admin') }}">groupe d'articles</a>
                        </b>
                    </div>
                @endif
                <div class="card-header d-flex justify-content-between">
                    <button @if (!$groupe->count()) disabled @endif class="btn btn-danger" data-toggle='modal'
                        data-target='#mdl-unite' style="border-radius: 5px!important;">
                        Ajouter une catégorie
                    </button>
                    <div class="card-header-action">
                        <div class="form-group m-2 d-block">
                            <select class="custom-select form-change">
                                <option value="">Tous les groupes d'articles</option>

                                @foreach ($groupe as $e)
                                    <option @if ($e->par_defaut == 1) selected @endif value="{{ $e->id }}">
                                        {{ $e->groupe }}</option>
                                @endforeach
                            </select>
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
                                            <th>Catégorie</th>
                                            <th>Groupe</th>
                                            <th></th>
                                            <th>#</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center" colspan="5">
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
                            <b class="text-muted">
                                <i class="fa fa-exclamation-triangle text-warning"></i>
                                Les catégories vous seront demandé à chaque fois que vous voulez ajouter un article.
                                Vous devez donc les ajouter selon la nature des articles que vous vendez dans votre magasin.
                            </b>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="mdl-unite" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content ">
                <div class="modal-header bg-danger text-white font-weight-bold d-flex justify-content-between">
                    <b>Ajouter une nouvelle catégorie d'articles</b>
                    <span style="cursor: pointer" data-dismiss="modal">
                        <i class="fa fa-times-circle p-2 "></i>
                    </span>
                </div>
                <form id="f-add" class="was-validated">
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Groupe d'articles</label>
                            <select class="custom-select" name="groupe_article_id" required>
                                @foreach ($groupe as $e)
                                    <option @if ($e->par_defaut == 1) selected @endif value="{{ $e->id }}">
                                        {{ $e->groupe }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="">Nom de la catégorie ou des catégories</label>
                            <textarea class="form-control" required name="categorie" placeholder="" id="" cols="30" rows="10"
                                setyle="height: 100px"></textarea>
                            <span>Ex : Legumes,Aliment,Lingerie,Chaussure homme</span>
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
                `<tr><td class="text-center" colspan="5"><span class="spinner-border text-danger"></span></td></tr>`;
            var table = $('#t-unite');
            var select = $('.form-change');
            select.change(function() {
                getCategorie();
            })
            getCategorie();

            function getCategorie() {
                table.find('tbody').html(spin);
                $.ajax({
                    url: '{{ route('categorie-article.index') }}',
                    data: {
                        groupe: select.val()
                    },
                    timeout: 20000,
                }).done(function(res) {
                    data = res.data;
                    str = '';
                    if (data.length > 0) {
                        $(data).each(function(i, e) {
                            if (e.par_defaut == 1) {
                                var def =
                                    '<i class="font-weight-bold text-muted"><span class="fa fa-check-circle text-success"></span> Catégorie par défaut</i>';
                                var v = 0;
                            } else {
                                var def = '';
                                var v = 1;
                            }

                            str += `<tr>
                                        <td>${i+1}</td>
                                        <td>
                                            <span app-label='${e.id}'>${e.categorie}</span>
                                            <input style='display:none' app-input='${e.id}' value='${e.categorie}' class='form-control' placeholder='Catégorie' />
                                            <span app-label-msg='${e.id}' class='ml-3'></span>
                                        </td>
                                        <td>${e.groupe}</td>
                                        <td>${def}</td>
                                        <td class='d-flex justify-content-center'>
                                            <button data-toggle='tooltip' title='Modifier' class='btn text-muted mr-3 btn-edit' value='${e.id}'><i class='fa fa-edit'></i></button>
                                            <button style='display:none' data-toggle='tooltip' title='Annuler' class='btn text-muted mr-3 btn-cancel' value='${e.id}'><i class='fa fa-times-circle text-danger'></i></button>
                                            <button style='display:none' data-toggle='tooltip' title='Enregistrer' class='btn text-success mr-3 btn-save' value='${e.id}'><i class='fa fa-save'></i></button>
                                            <button data-toggle='tooltip' title='Marquer "${e.categorie}" comme catégorie par defaut' class='btn text-muted mr-3 btn-default' value='${e.id}' to='${v}'><i class='fa fa-check-circle'></i></button>
                                            <div class="dropdown ml-2">
                                                <button title="Supprimer : ${e.categorie}" class="btn text-danger btn-del dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class='fa fa-trash'></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="">
                                                    <button class="dropdown-item delete btn"  value='${e.id}'>Supprimer</button>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>`;
                        });
                        table.find('tbody').html('<tr><td></td><td></td><td></td><td></td><td></td></tr>');
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
                            '<tr><td colspan="5" class="text-danger font-weight-bolder text-center">Aucune catégorie</td></tr>';
                        table.find('tbody').html('<tr><td></td><td></td><td></td><td></td><td></td></tr>');
                        table.DataTable().destroy();
                        table.find('tbody').html(str);
                    }

                })
            }

            function init() {
                $('.btn-edit').off('click').click(function() {
                    var id = this.value;
                    $('.btn-edit').hide();
                    $('.btn-default').hide();
                    $('.btn-del').hide();
                    $('.btn-save[value=' + id + ']').show();
                    $('.btn-cancel[value=' + id + ']').show();

                    $('[app-input]').hide();

                    $('[app-label=' + id + ']').hide();
                    $('[app-input=' + id + ']').show();
                });

                $('.btn-cancel').off('click').click(function() {
                    $('.btn-cancel').hide();
                    $('.btn-save').hide();
                    $('.btn-del').show();
                    $('.btn-edit').show();
                    $('.btn-default').show();


                    $('[app-label]').show();
                    $('[app-input]').hide();
                });

                $('.btn-save').off('click').click(function() {

                    var id = this.value;
                    var val = $('[app-input=' + id + ']').val();
                    var btn = $(this);
                    btn.find('i').removeClass().addClass('spinner-border spinner-border-sm');

                    var tr = btn.closest('tr');
                    $('button', tr).attr('disabled', true);
                    var span = $('[app-label-msg=' + id + ']').removeClass().html('');

                    $.ajax({
                        url: '{{ route('categorie-article.update', '') }}/' + id,
                        data: {
                            categorie: val
                        },
                        type: 'put',
                        timeout: 20000,
                    }).done(function(res) {
                        data = res.data;
                        if (res.success == true) {
                            var m = res.message;
                            span.addClass('ml-2 text-success').html(m);
                            $('[app-label=' + id + ']').html(val);
                        } else {
                            var m = data.msg.join('<br>');
                            span.addClass('ml-2 text-danger').html(m);
                        }
                        setTimeout(() => {
                            span.removeClass().html('');
                        }, 5000);

                        $('button', tr).attr('disabled', false);
                        btn.find('i').removeClass().addClass('fa fa-save');
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
                        url: '{{ route('categorie-article.destroy', '') }}/' + id,
                        type: 'delete',
                        timeout: 20000,
                    }).done(function(res) {
                        data = res.data;
                        if (res.success == true) {
                            var m = res.message;
                            span.addClass('ml-2 text-success').html(m);
                            $('[app-label=' + id + ']').html(val);
                            setTimeout(() => {
                                getCategorie();
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
                        url: '{{ route('categorie-article.update', '') }}/' + id,
                        type: 'put',
                        data: {
                            to: to,
                            default: 'yes'
                        },
                        timeout: 20000,
                    }).done(function(res) {
                        data = res.data;
                        if (res.success == true) {
                            var m = res.message;
                            span.addClass('ml-2 text-success').html(m);
                            setTimeout(() => {
                                getCategorie();
                            }, 2000);
                        } else {
                            var m = res.message;
                            span.addClass('ml-2 text-danger').html(m);
                            btn.find('i').removeClass().addClass('fa fa-check-circle');
                        }
                        $('button', tr).attr('disabled', false);
                    });

                });
            }

            $('#f-add').submit(function() {
                event.preventDefault();
                var form = $(this);
                var btn = $(':submit', form).attr('disabled', true);
                btn.find('span').removeClass().addClass('spinner-border spinner-border-sm mr-3');
                rep = $('#rep');
                rep.removeClass().slideUp();

                $.ajax({
                    url: '{{ route('categorie-article.store') }}',
                    type: 'post',
                    data: form.serialize(),
                    timeout: 20000,
                }).done(function(res) {
                    data = res.data;
                    if (res.success == true) {
                        form.get(0).reset();
                        var m = res.message;
                        rep.addClass('alert alert-success w-100').html(m);
                        setTimeout(() => {
                            getCategorie();
                        }, 2000);
                    } else {
                        var m = res.message;
                        try {
                            m += '<br>' + res.data.msg.join('<br>');
                        } catch (error) {}
                        rep.addClass('alert alert-danger w-100').html(m);
                        btn.find('i').removeClass().addClass('fa fa-check-circle');
                    }
                    rep.slideDown();
                    btn.attr('disabled', false);
                    btn.find('span').removeClass();
                });

            })
        })
    </script>


@endsection

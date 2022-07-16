@extends('layouts.main')
@section('title', 'Mes articles')

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
            <div class="card" style="display: none" id="card-info">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h4 class="text-danger">
                                <i class="fa fa-exclamation-triangle text-danger"></i>
                                Vous avez un ou plusieurs articles avec des avertissements !
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card ">
                <div class="card-header">
                    <h4>Articles</h4>
                    <div class="card-header-action">
                        <div class="form-group m-2 d-block">
                            <button class="btn btn-danger" data-toggle='modal' data-target='#mdl-add'
                                style="border-radius: 5px!important;">
                                Ajouter un article
                            </button>
                        </div>
                    </div>
                </div>
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
                                <table id="t-data" class="table table-condensed table-bordered table-hover"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Article</th>
                                            <th>Prix de vente/Unité de mesure</th>
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

            <div class="card ">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-muted">
                                <i class="fa fa-exclamation-triangle text-warning"></i>
                                Vous pouvez ajouter tous les articles de votre magasin ici tout en les rangent selon le
                                groupe et la catégorie d'aricles afin de mieux les gerer.
                            </p>
                            <p class="text-muted">
                                <i class="fa fa-exclamation-triangle text-warning"></i>
                                Vous serez informé si nécessaire dans le tableau ci-haut du niveau de stock de chaque
                                article et de sa date d'expiration.
                            </p>

                            <p class="text-muted">
                                <i class="fa fa-exclamation-triangle text-warning"></i>
                                Seuls les articles n'ayant pas de date d'expiration ou ayant une date d'expiration qui va au
                                delà de 30 jours peuvent etre vendus. c-à-d, vous ne pouvez pas enregistrer une vente d'un
                                article dont sa date d'expiration est inférieure à 30 jours.
                            </p>

                            <p class="text-muted">
                                <i class="fa fa-exclamation-triangle text-warning"></i>
                                Il ne pas recommandé de modifier les prix de vos articles pendant qu'un caissier est entrain
                                d'enregistrer des ventes, rassurez vous que lors de la modification des prix, vous etes le
                                seul à utiliser l'application ou aucun de vos caissier n'est entrain d'enregistrer les
                                ventes, au cas contraire, demandez à votre caissier d'actualiser sa page de vente pour
                                mettre à jour sa liste de prix.
                            </p>
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
                    <b>Ajouter un article</b>
                    <span style="cursor: pointer" data-dismiss="modal">
                        <i class="fa fa-times-circle p-2 "></i>
                    </span>
                </div>
                <form id="f-add" class="was-validated">
                    @php
                        $unite = \App\Models\UniteMesure::where('compte_id', compte_id())->get();
                        $devise = \App\Models\Devise::all();
                    @endphp
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Catégorie de l'article</label>
                            <select class="custom-select" id="select-cat" name="categorie_article_id" required></select>
                        </div>
                        <div class="form-group">
                            <label for="">Nom de l'article</label>
                            <input class="form-control" maxlength="128" required name="article"
                                placeholder="Nom de l'article" />
                        </div>
                        <div class="form-group">
                            <label for="">Quantité d'approvisionnement</label>
                            <div class="input-group mb-3">
                                <input class="form-control" name="stock" required min="1" type="number"
                                    placeholder="Quantité d'approvisionnement" aria-describedby="basic-addon2">
                                <div class="input-group-append">
                                    <span class="input-group-text" id="basic-addon2"></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="">Prix de vente unitaire</label>
                                    <input class="form-control w-100" name="prix" required min="1" type="number"
                                        step="0.000001" placeholder="Prix de vente unitaire" />
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">Devise</label>
                                    <select class="custom-select" name="devise_id" required>
                                        @foreach ($devise as $e)
                                            <option value="{{ $e->id }}">{{ $e->devise }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex">
                                    <div class="form-group">
                                        <label class="mt-5 mr-3">Par</label>
                                    </div>
                                    <div class="form-group ">
                                        <label for="">Unité de mesure</label>
                                        <select class="custom-select w-100" name="unite_mesure_id" required>
                                            @foreach ($unite as $e)
                                                <option @if ($e->par_defaut == 1) selected @endif
                                                    value="{{ $e->id }}">
                                                    {{ $e->unite_mesure }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mb-2">
                            <div class="custom-checkbox custom-control d-inline-flex">
                                <input type="checkbox" data-checkboxes="mygroup" class="custom-control-input"
                                    id="checkbox12" name="can_expire">
                                <label for="checkbox12" class="custom-control-label">&nbsp;</label>
                                <label for="checkbox12">Cet article peut expirer</label>
                            </div>
                        </div>
                        <div class="form-group" id="e-zone" style="display: none">
                            <label for="">Date d'expiration</label>
                            <input class="form-control datepicker" name="date_expiration" value="{{ date('Y-m-d') }}" />
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

    <script src="{{ asset('assets/js/daterangepicker/moment.js') }}"></script>
    <script src="{{ asset('assets/js/daterangepicker/daterangepicker.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/js/daterangepicker/daterangepicker.css') }}">

    <script>
        $(function() {
            $('.datepicker').daterangepicker({
                minYear: 2022,
                showDropdowns: true,
                locale: {
                    format: 'YYYY/MM/DD'
                },
                singleDatePicker: true,
                drops: 'up',
                minDate: "{{ date('Y-m-d') }}"
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
            var table = $('#t-data');
            var groupchange = $('.groupe-change');
            var catechange = $('.cat-change');
            groupchange.change(function() {
                $(this).attr('disabled', true);
                catechange.attr('disabled', true);
                getCategorie();
            })

            var cb = $('#checkbox12');
            cb.change(function() {
                if ($(this).is(':checked')) {
                    $('#e-zone').slideDown();
                } else {
                    $('#e-zone').slideUp();
                }
            })
            if (cb.is(':checked')) {
                $('#e-zone').slideDown();
            } else {
                $('#e-zone').slideUp();
            }

            var select = $('[name=unite_mesure_id]');
            var lab = $('#basic-addon2');
            lab.html(select.children(':selected').html());
            select.change(function() {
                lab.html(select.children(':selected').html());
            })

            catechange.change(function() {
                $(this).attr('disabled', true);
                groupchange.attr('disabled', true);
                getData();
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
                    str2 = '';
                    $(data).each(function(i, e) {
                        if (e.par_defaut == 1) {
                            str += `<option value="${e.id}" selected >${e.categorie}</option>`;
                            str2 += `<option value="${e.id}" selected >${e.categorie}</option>`;
                        } else {
                            str += `<option  value="${e.id}">${e.categorie}</option>`;
                            str2 += `<option  value="${e.id}">${e.categorie}</option>`;
                        }
                    });
                    $('#select-cat').html(str2);
                    catechange.html(str).attr('disabled', false);
                    groupchange.attr('disabled', false);
                    getData();

                }).fail(function(res) {})
            }
            getCategorie();

            function getData() {
                table.find('tbody').html(spin);
                $.ajax({
                    url: '{{ route('articles.index') }}',
                    data: {
                        categorie: catechange.val()
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
                            stTi = '',
                            canE = '',
                            canEc = '';

                        if (e.stock < 20) {
                            stCl = 'bg-warning';
                            stTi = 'Pensez à réapprovisionner ce stock';
                        }

                        if (e.can_expire == true) {
                            if (e.jour_restant <= 60) {
                                show = true;
                                if (e.jour_restant >= 0 && e.jour_restant <= 60) {
                                    canEc = 'bg-warning';
                                    canE =
                                        `Cet article expire dans ${e.jour_restant} jour(s),  pensez à le réapprovisionner ou à modifier sa date d'expiration.`;
                                } else {
                                    canEc = 'bg-danger';
                                    canE =
                                        `Cet article a déjà expiré depuis ${e.jour_restant.toString().replace('-','')} jour(s),  pensez à le réapprovisionner ou à modifier sa date d'expiration.`;
                                }
                            } else {
                                canE =
                                    `Cet article expire dans ${e.jour_restant} jour(s).`;
                            }
                        }
                        str += `<tr>
                                    <td>${i+1}</td>
                                    <td title="${e.article}">${art}</td>
                                    <td title="Prix de vente : ${e.prix} Par ${e.unite_mesure}">${e.prix}</td>
                                    <td class="${stCl}" title="${stTi}">${e.stock} ${e.unite_mesure}</td>
                                    <td>${e.code}</td>
                                    <td>${e.categorie}</td>
                                    <td class="${canEc}" title="${canE}" >${e.date_expiration}</td>
                                    <td class='d-flex justify-content-center'>
                                        <a href="{{ route('detail-article.admin', '') }}/${e.id}" class='btn text-muted' ><i class='fa fa-eye'></i> Détails</a>
                                    </td>
                                </tr>`;
                    });
                    catechange.attr('disabled', false);
                    groupchange.attr('disabled', false);
                    table.find('tbody').html(
                        '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>'
                    );
                    table.DataTable().destroy();
                    if (str.length > 0) {
                        table.find('tbody').html(str);
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

                }).fail(function(res) {})
            }


            $('#f-add').submit(function() {
                event.preventDefault();
                var form = $(this);
                var btn = $(':submit', form).attr('disabled', true);
                btn.find('span').removeClass().addClass('spinner-border spinner-border-sm mr-3');
                rep = $('#rep', form);
                rep.removeClass().slideUp();

                var data = form.serialize();

                $.ajax({
                    url: '{{ route('articles.store') }}',
                    type: 'post',
                    data: data,
                    timeout: 20000,
                }).done(function(res) {
                    data = res.data;
                    if (res.success == true) {
                        form.get(0).reset();
                        var m = res.message;
                        rep.addClass('alert alert-success w-100').html(m);
                        if (cb.is(':checked')) {
                            $('#e-zone').slideDown();
                        } else {
                            $('#e-zone').slideUp();
                        }
                        lab.html(select.children(':selected').html());

                        getData();
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
                    }, 5000);
                    btn.attr('disabled', false);
                    btn.find('span').removeClass();
                });
            })


        })
    </script>


@endsection

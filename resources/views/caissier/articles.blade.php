@extends('layouts.main')
@section('title', 'Mes articles')

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
                    <h4>Articles</h4>
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
                `<tr><td class="text-center" colspan="7"><span class="spinner-border text-danger"></span></td></tr>`;
            var table = $('#t-data');
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
                                </tr>`;
                    });
                    catechange.attr('disabled', false);
                    groupchange.attr('disabled', false);
                    table.find('tbody').html(
                        '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>'
                    );
                    table.DataTable().destroy();
                    if (str.length > 0) {
                        table.find('tbody').html(str);
                        table.DataTable(opt);
                    } else {
                        str =
                            '<tr><td colspan="7" class="text-danger font-weight-bolder text-center">Aucun article</td></tr>';
                        table.find('tbody').html(str);
                    }

                })
            }

        })
    </script>


@endsection

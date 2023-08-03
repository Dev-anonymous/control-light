@extends('layouts.main')
@section('title', 'Code barre articles')

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
                    <h4>Code barre des articles</h4>
                </div>
                <div class="card-header">
                    <div class="form-group d-block mr-1">
                        <select class="select2 custom-select groupe-change">
                            <option value="">Tous les groupes d'articles</option>
                            @foreach ($groupe as $e)
                                <option value="{{ $e->id }}">
                                    {{ $e->groupe }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group d-block ml-1">
                        <select class="select2 custom-select cat-change" disabled>
                            <option value="">Toutes les catégories d'articles</option>
                        </select>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="t-data"
                                    class="table table-condensed table-bordered table-hover font-weight-bold font-weight-bold"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>Article</th>
                                            <th>Code article</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="text-center"
                                                colspan="3>
                                                <span class="spinner-border
                                                text-danger"></span>
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
                <div class="card-header">
                    <i class="fa fa-barcode fa-4x"></i>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-muted">
                                <i class="fa fa-exclamation-triangle text-warning"></i>
                                Vous pouvez exporter ce tableau d'articles et imprimer les étiquettes
                                codes barres que vous collerez sur vos articles pour permettre au scanneur d'identifier
                                rapidement vos articles
                                lors de la vente.
                            </p>
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

    <script src="{{ asset('assets/js/JsBarcode.all.min.js') }}"></script>

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
                `<tr><td class="text-center" colspan="8"><span class="spinner-border text-danger"></span></td></tr>`;
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
                    str = '<option value="">Toutes les catégories</option>';
                    $(data).each(function(i, e) {
                        str += `<option  value="${e.id}">${e.categorie}</option>`;
                    });
                    catechange.html(str).attr('disabled', false);
                    groupchange.attr('disabled', false);
                    getData();

                }).fail(function(res) {})
            }
            getCategorie();


            function makeQr() {
                $('[code]').each(function(i, e) {
                    var code = $(e).attr('code');
                    JsBarcode(e, code, {
                        foraamt: "pharmacode",
                        lineColor: '#0aa',
                        height:30
                    });
                });
            }

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
                        str += `<tr>
                                    <td>${e.article}</td>
                                    <td>${e.code}</td>
                                    <td><canvas code='${e.code}' ></canvas></td>
                                </tr>`;
                    });
                    catechange.attr('disabled', false);
                    groupchange.attr('disabled', false);
                    table.find('tbody').html(
                        '<tr><td></td><td></td><td></td></tr>'
                    );
                    table.DataTable().destroy();
                    if (str.length > 0) {
                        table.find('tbody').html(str);
                        makeQr();
                        table.DataTable(opt);
                    } else {
                        str =
                            '<tr><td colspan="3" class="text-danger font-weight-bolder text-center">Aucun article</td></tr>';
                        table.find('tbody').html(str);
                    }
                })
            }

        })
    </script>


@endsection

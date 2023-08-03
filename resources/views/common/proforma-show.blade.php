@extends('layouts.main')
@section('title', 'Détails proforma ' . $proforma->numero)

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
                <div class="card-header d-flex justify-content-between">
                    <h3 class="h4 font-weight-bold">Facture proforma :
                        {{ $proforma->numero . ' | ' . $proforma->montant . ' | ' . $proforma->date->format('Y-m-d H:i:s') }}
                    </h3>
                </div>
                <div class="collapse show" id="mycard-collapse0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <div class="">
                                        {!! $proforma->html !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-between">
                        <button class="btn btn-outline-danger" data-toggle="modal" data-target="#mdldel">
                            <i class="fa fa-trash"></i>
                            Supprimer la facture
                        </button>
                        <button class="btn btn-dark bg-black" data-toggle="modal" data-target="#mdlenc">
                            <i class="fa fa-save"></i>
                            Encaisser la facture
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="mdldel" tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content ">
                    <div class="modal-header bg-danger text-white font-weight-bold d-flex justify-content-between">
                        <b>Suppression de la facture</b>
                        <span style="cursor: pointer" data-dismiss="modal">
                            <i class="fa fa-times-circle p-2 "></i>
                        </span>
                    </div>
                    <div class="modal-body text-center">
                        <b>confirmez la suppression de la facture {{ $proforma->numero }} ? </b>
                        <div id="rep"></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark" data-dismiss="modal">
                            NON
                        </button>
                        <button class="btn btn-danger bdel">
                            <span></span>
                            OUI
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="mdlenc" tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content ">
                    <div class="modal-header bg-danger text-white font-weight-bold d-flex justify-content-between">
                        <b>Encaissement de la facture</b>
                        <span style="cursor: pointer" data-dismiss="modal">
                            <i class="fa fa-times-circle p-2 "></i>
                        </span>
                    </div>
                    <div class="modal-body text-center">
                        <b>confirmez l'encaissement de la facture {{ $proforma->numero }} ? </b>
                        <div id="rep2"></div>
                        <div id="rep3" class="mt-2"></div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-dark" data-dismiss="modal">
                            NON
                        </button>
                        <button class="btn btn-danger benc">
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
    <script src="{{ asset('assets/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/datatables/datatables.min.js') }}"></script>

    <script src="{{ asset('assets/js/daterangepicker/moment.js') }}"></script>
    <script src="{{ asset('assets/js/daterangepicker/daterangepicker.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/js/daterangepicker/daterangepicker.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/css/print.css') }}" media="print">
    <script src="{{ asset('assets/js/printThis.js') }}"></script>

    <script>
        $(function() {

            $('.bdel').click(function() {
                event.preventDefault();
                var btn = $(this).attr('disabled', true);
                btn.find('span').removeClass().addClass('spinner-border spinner-border-sm');
                rep = $('#rep');
                rep.removeClass().slideUp();
                $.ajax({
                    url: '{{ route('proforma.destroy', $proforma->id) }}',
                    type: 'delete',
                    timeout: 20000,
                }).done(function(res) {
                    data = res.data;
                    var m = res.message;
                    rep.addClass('alert alert-success w-100 text-left mt-2').html(m);
                    setTimeout(() => {
                        location.href = '{{ route('proforma') }}';
                    }, 3000);
                    rep.slideDown();
                    btn.find('span').removeClass();
                }).fail(function() {
                    btn.attr('disabled', false);
                    btn.find('span').removeClass();
                    rep.addClass('alert alert-danger w-100 text-left mt-2').html(
                        'Erreur, veuillez reessayer');
                    rep.slideDown();
                });
            });

            $('.benc').click(function() {
                event.preventDefault();
                var btn = $(this).attr('disabled', true);
                btn.find('span').removeClass().addClass('spinner-border spinner-border-sm');
                rep = $('#rep2');
                $('#rep3').html('');
                rep.removeClass().slideUp();
                $.ajax({
                    url: '{{ route('proforma.encaissement', $proforma->id) }}',
                    type: 'post',
                    timeout: 20000,
                }).done(function(res) {
                    data = res.data;
                    if (res.success == true) {
                        var m = res.message;
                        rep.addClass('alert alert-success w-100 text-left mt-2').html(m);
                        @if (auth()->user()->user_role == 'admin')
                            var url = '{{ route('ventes-magasin.admin') }}?nf=' + data
                                .numero_facture;
                        @else
                            var url = '{{ route('ventes-magasin.caissier') }}?nf=' + data
                                .numero_facture;
                        @endif
                        var a =
                            `<a class='btn btn-link' href='${url}'>Afficher ou imprimer la facture</a>`;
                        $('#rep3').html(a);
                    } else {
                        var m = res.message;
                        rep.addClass('alert alert-danger w-100 text-left mt-2').html(m);
                        btn.attr('disabled', false);
                    }
                    rep.slideDown();
                    btn.find('span').removeClass();
                }).fail(function() {
                    btn.attr('disabled', false);
                    btn.find('span').removeClass();
                    rep.addClass('alert alert-danger w-100 text-left mt-2').html(
                        'Erreur, veuillez reessayer');
                    rep.slideDown();
                });
            })
        })
    </script>


@endsection

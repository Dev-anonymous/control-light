@extends('layouts.main')
@section('title', 'Factures proforma')

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
                    <h3 class="h4 font-weight-bold">Factures proforma</h3>
                    <div class="card-header-action">
                        <form id="f-change0">
                            <div class="row">
                                <div class="form-group ml-1 mr-1">
                                    <input class="form-control datepicker p-3 rounded-sm" name="date"
                                        value="{{ date('Y-m-d') }}" style="padding: 20px !important; width:200px" />
                                </div>
                                <div class="form-group ml-1">
                                    <select class="select2 form-control rounded-0 p-0" name="devise" style="width:80px">
                                        <option value="">Toutes</option>
                                        <option>CDF</option>
                                        <option>USD</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card-hearder d-flex justify-content-end">
                    <div class="form-group m-2 d-block">
                        <button class="btn btn-danger" onclick="javascript:location.assign('{{route('proforma_default')}}')"
                            style="border-radius: 5px!important;">
                            <i class="fa fa-plus-circle"></i>
                            Nouvelle facture PF
                        </button>
                    </div>
                </div>
                <div class="collapse show" id="mycard-collapse0">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table id="t-vente0"
                                        class="table table-condensed table-bordered table-hover font-weight-bold"
                                        style="width: 100%;">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th></th>
                                                <th>Numéro facture</th>
                                                <th>Client</th>
                                                <th>Total facture</th>
                                                <th>Créée par</th>
                                                <th>Date</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
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

    <script src="{{ asset('assets/js/daterangepicker/moment.js') }}"></script>
    <script src="{{ asset('assets/js/daterangepicker/daterangepicker.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/js/daterangepicker/daterangepicker.css') }}">

    <script>
        $(function() {
            $('[data-toggle="pop"]').popover({
                trigger: 'hover'
            })
            $('.datepicker').daterangepicker({
                minYear: '{{ date('Y') }}',
                showDropdowns: true,
                locale: {
                    format: 'YYYY/MM/DD'
                },
                maxDate: "{{ date('Y-m-d') }}"
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

            var fchange = $('#f-change0');

            fchange.change(function() {
                var form = $(this);
                $(":input", form).attr('disabled', true);
                getData();
            });

            var table = $('#t-vente0');

            function getData() {
                table.find('tbody').html(spin);
                $(':input', fchange).attr('disabled', false);
                var data = fchange.serialize();
                $(':input', fchange).attr('disabled', true);

                $.ajax({
                    url: '{{ route('proforma.index') }}',
                    data: data,
                    timeout: 20000,
                }).done(function(res) {
                    $(':input', fchange).attr('disabled', false);
                    var data = res.data;
                    var str = '';
                    $(data).each(function(i, e) {
                        var buto =
                            `<a href="{{ route('proforma.show', '') }}/${e.id}" class='btn text-muted' ><i class='fa fa-eye'></i> Détails</a>`;

                        var enc = '';
                        var title = '';
                        if (null != e.date_encaissement) {
                            enc =
                                `<i class="fa fa-2x fa-check-circle text-success"></i>`;
                            title = `Proforma encaissée le ${e.date_encaissement}`;
                        } else {
                            enc =
                                `<i class="fa fa-2x fa-times-circle text-danger"></i>`;
                            title = 'Proforma non encaissée';
                        }

                        str += `<tr title="${title}">
                                    <td>${i+1}</td>
                                    <td>${enc}</td>
                                    <td>${e.numero}</td>
                                    <td>${e.client}</td>
                                    <td>${e.montant}</td>
                                    <td>${e.enregistrer_par}</td>
                                    <td>${e.date}</td>
                                    <td class='d-flex justify-content-center'>${buto}</td>
                                </tr>`;
                    });

                    table.find('tbody').html(
                        '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>'
                    );
                    table.DataTable().destroy();
                    if (str.length > 0) {
                        table.find('tbody').html(str);
                        var tab = table.DataTable(opt);
                        var nf = '{{ request()->nf }}';
                        if (nf.length > 0) {
                            $('[aria-controls="t-vente0"]').val(nf).trigger($.Event('keyup', {
                                keyCode: 13
                            }));
                        }
                    } else {
                        str =
                            '<tr><td colspan="8" class="text-danger font-weight-bolder text-center">Aucune facture</td></tr>';
                        table.find('tbody').html(str);
                    }

                })
            }

            getData();

        })
    </script>


@endsection

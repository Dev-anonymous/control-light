@extends('layouts.main')
@section('title', 'Factures supprimées')

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
                    <h3 class="h4 font-weight-bold">Factures supprimées</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="t-facture"
                                    class="table table-condensed table-bordered table-hover font-weight-bold"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>N° Facture</th>
                                            <th>Client</th>
                                            <th>Caissier</th>
                                            <th>Articles</th>
                                            <th>Total</th>
                                            <th>Date facture</th>
                                            <th>Date suppression</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $n = 1;
                                        @endphp
                                        @foreach ($factures as $el)
                                            <tr item='{{ $el->id }}'>
                                                <td>{{ $n++ }}</td>
                                                <td>{{ $el->numero_facture }}</td>
                                                <td>{{ $el->client }}</td>
                                                <td>{{ $el->caissier }}</td>
                                                <td>{{ $el->articles }}</td>
                                                <td>{{ $el->total }}</td>
                                                <td>{{ $el->date_facture }}</td>
                                                <td>{{ $el->date_suppression }}</td>
                                                <td>
                                                    <button class="btn btn-danger daccord" value="{{ $el->id }}">
                                                        <i class="fa fa-check-circle"></i> D'accord
                                                    </button>
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
            <div class="card ">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <p class="text-muted">
                                <i class="fa fa-exclamation-triangle text-warning"></i>
                                Toutes les factures supprimées par un cassier seront affichées ici en signe de notification.
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

    <script>
        $(function() {
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
            $('.table').DataTable(opt);

            $('.daccord').click(function() {
                var btn = $(this);
                btn.attr('disabled', true);
                $.ajax({
                    url: '{{ route('daccord.api') }}',
                    data: {
                        item: btn.val()
                    },
                    type: 'DELETE'
                }).done(function(res) {
                    $('.table').DataTable().destroy();
                    $('tr[item=' + btn.val() + ']').remove();
                    $('.table').DataTable(opt);
                })
            })

        })
    </script>


@endsection

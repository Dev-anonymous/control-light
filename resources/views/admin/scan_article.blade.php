@extends('layouts.main')
@section('title', 'Scan article')

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
                    <h4>Liste de codes uniques de tous vos articles</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table id="table" class="table table-condensed table-bordered table-hover"
                                    style="width: 100%;">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Article</th>
                                            <th>Code</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($code as $k => $el)
                                            <tr>
                                                <td>{{ $k + 1 }}</td>
                                                <td>{{ $el->article->article }}</td>
                                                <td>{{ $el->code }}</td>
                                                <td>{{ $el->date?->format('d-m-Y H:i:s') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-info mr-2 bcam">
                        <i class="fa fa-barcode"></i>
                        Scanner un code
                    </button>
                </div>
                <div class="card-footer divscan" style="display: none">
                    <div class="container">
                        <h4>Scannez un code barre de l'article</h4>
                        <div class="section">
                            <div id="my-qr-reader">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>



@endsection

@section('js-code')
    {{-- <script src="https://unpkg.com/html5-qrcode"></script> --}}
    <script src="{{ asset('assets/js/html5-qrcode.js') }}"></script>
    <style>
        #my-qr-reader {
            padding: 20px !important;
            border: 1.5px solid #b2b2b2 !important;
            border-radius: 8px;
        }

        #my-qr-reader img[alt="Info icon"] {
            display: none;
        }

        #my-qr-reader img[alt="Camera based scan"] {
            width: 50px !important;
            height: 50px !important;
        }

        button {
            padding: 10px 20px;
            border: 1px solid #b2b2b2;
            outline: none;
            border-radius: 0.25em;
            color: white;
            font-size: 15px;
            cursor: pointer;
            margin-top: 15px;
            margin-bottom: 10px;
            background-color: #008000ad;
            transition: 0.3s background-color;
        }

        button:hover {
            background-color: #008000;
        }

        #html5-qrcode-anchor-scan-type-change {
            text-decoration: none !important;
            color: #1d9bf0;
        }

        video {
            width: 100% !important;
            border: 1px solid #b2b2b2 !important;
            border-radius: 0.25em;
        }
    </style>
    <script>
        let htmlscanner;
        var loaded = false;

        try {
            htmlscanner = new Html5QrcodeScanner(
                "my-qr-reader", {
                    fps: 10,
                    qrbos: 150,
                    // rememberLastUsedCamera: true,
                }
            );

        } catch (error) {
            console.log(error);
        }

        $('.bcam').click(function() {
            if (!loaded) {
                try {
                    htmlscanner.render(function(decodedText, decodedResult) {
                        console.log(`Code matched = ${decodedText}`, decodedResult);
                        // var d = $('.divscan');
                        // d.stop();
                        // d.slideUp();
                        $('.bcam').attr('disabled', true);

                        $.ajax({
                            url: '{{ route('code.api') }}',
                            data: {
                                code: decodedText,
                            },
                            timeout: 20000,
                        }).done(function(res) {
                            alert(res.message);
                        }).always(function() {
                            $('.bcam').attr('disabled', false);
                        });
                    });
                    loaded = true;
                } catch (error) {
                    alert("Erreur de demarrage de la camera, veuillez actualiser la page");
                }
            }
            var d = $('.divscan');
            d.stop();
            d.slideToggle();
        })
    </script>

    <link rel="stylesheet" href="{{ asset('assets/datatables/datatables.min.css') }}" />
    <script src="{{ asset('assets/datatables/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/datatables/vfs_fonts.js') }}"></script>
    <script src="{{ asset('assets/datatables/datatables.min.js') }}"></script>
    <script>
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

        $('#table').DataTable(opt);
    </script>


@endsection

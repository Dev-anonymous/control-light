@extends('layouts.main')
@section('title', 'Modèles de factures proforma')

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
                    <h3 class="h4 font-weight-bold">Modèles de factures proforma</h3>
                </div>
            </div>
            <div class="row">
                @if (count($models))
                    @foreach ($models as $el)
                        <div class="col-md-4">
                            <div class="card ">
                                <div class="card-header d-flex justify-content-between">
                                    <b>Modèle #{{ $el->id }}</b>
                                    @if ($el->id == getConfig('facture_zero'))
                                        <i style="cursor: pointer" title="C'est votre modèle par defaut."
                                            class="fa fa-2x fa-check-circle text-success"> </i>
                                    @endif
                                </div>
                                <div class="card-body p-1">
                                    <img src="{{ $el->img }}" alt="" width="100%" height="500px"
                                        style="object-fit: cover">
                                </div>
                                <div class="card-footer d-flex justify-content-between">
                                    <a href="{{ route('proforma.facture', $el->id) }}" class="btn btn-danger mr-2">
                                        Utiliser ce modèle
                                    </a>

                                    @if ($el->id != getConfig('facture_zero'))
                                        <button type="button" bdef title="Définir comme modèle par defaut."
                                            value="{{ $el->id }}" class="btn btn-info">
                                            <i class="fa fa-check-circle"></i>
                                            Modele par defaut
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="alert alert-warning w-100">
                                    <b>Aucun modèle de proforma</b>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('js-code')
    <script>
        $('[bdef]').click(function() {

            var btn = $(this);
            btn.find('i').removeClass().addClass('spinner-border spinner-border-sm');
            btn.attr('disabled', true);
            var id = this.value;

            $.ajax({
                url: '{{ route('config.api') }}',
                type: 'post',
                data: {
                    id: id,
                    'action': 'facture'
                },
                timeout: 20000,
            }).done(function(res) {

            }).always(function() {
                location.reload();
            });
        })
    </script>
@endsection

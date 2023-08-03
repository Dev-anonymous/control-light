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
                                <div class="card-header">
                                    <b>Modèle #{{ $el->id }}</b>
                                </div>
                                <div class="card-body p-1">
                                    <img src="{{ $el->img }}" alt="" width="100%" height="500px"
                                        style="object-fit: cover">
                                </div>
                                <div class="card-footer">
                                    <a href="{{ route('proforma.facture', $el->id) }}" class="btn btn-danger">
                                        Utiliser ce modèle
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="alert alert-warning w-100">
                                    <b>Aucum modèle de proforma</b>
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

@endsection

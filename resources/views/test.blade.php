@extends('layouts.main')
@section('title', 'Dashboard')

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
                    <h4>Dash</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-9">
                            <div id="graph"></div>
                        </div>
                        <div class="col-lg-3">
                            <div class="row mt-5">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js-code')
    {{-- <script src="{{ asset('ressources/js/apexcharts.min.js') }}"></script> --}}


@endsection

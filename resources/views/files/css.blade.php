<style>
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.1);
        border-radius: 16px;
    }

    ::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05);
    }

    * {
        -ms-overflow-style: 8px;
        scrollbar-width: thin;
        scrollbar-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.05);
    }

</style>
<link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
<link rel='shortcut icon' type='image/x-icon' href="{{ asset('assets/img/favicon.png') }}" />
<style>
    .bg-error {
        background: rgba(255, 0, 0, 0.1)
    }

    [no-under]:hover {
        text-decoration: none;
    }

    .ombre:hover {
        box-shadow: 0 1rem 3rem rgba(0, 0, 0, .175) !important;
    }

</style>
<meta name="csrf-token" content="{{ csrf_token() }}">

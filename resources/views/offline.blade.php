<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Non connecté</title>
    <script>
        window.addEventListener('online', function(e) {
            location.reload();
        });
    </script>
    <style>
        {!! file_get_contents('assets/css/app.min.css') !!}
    </style>
    <style>
        {!! file_get_contents('assets/css/style.css') !!}
    </style>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-12 text-center" style="margin-top: 25%">
                <h3 class="text-muted">Connectez-vous à Internet</h3>
                <p class="text-center text-muted mt-3 font-weight-bold">
                    Vous n'êtes pas connecté. Vérifiez votre connexion.
                    ! </p>

                <section class="error-container">
                    <img src="{{ encode('assets/img/offline.png') }}" width="150px" height="150px" alt="">
                </section>
                <button onclick="location.reload()" class="btn btn-danger mt-5">
                    Actualiser
                </button>
            </div>
        </div>
    </div>
</body>

</html>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Non connecté</title>
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css?family=Montserrat:400,600,700');
        @import url('https://fonts.googleapis.com/css?family=Catamaran:400,800');

        .error-container {
            text-align: center;
            font-size: 160px;
            font-family: 'Catamaran', sans-serif;
            font-weight: 600;
            margin: 15px 10px;
        }

        .error-container>span {
            display: inline-block;
            line-height: 0.7;
            position: relative;
            color: #FFB485;
        }

        .error-container>span {
            display: inline-block;
            position: relative;
            vertical-align: middle;
        }

        .error-container>span:nth-of-type(1) {
            color: #D1F2A5;
            animation: colordancing 4s infinite;
        }

        .error-container>span:nth-of-type(3) {
            color: #F56991;
            animation: colordancing2 4s infinite;
        }

        .error-container>span:nth-of-type(2) {
            width: 120px;
            height: 120px;
            border-radius: 999px;
        }

        .error-container>span:nth-of-type(2):before,
        .error-container>span:nth-of-type(2):after {
            border-radius: 0%;
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: inherit;
            height: inherit;
            border-radius: 999px;
            box-shadow: inset 30px 0 0 rgba(209, 242, 165, 0.4),
                inset 0 30px 0 rgba(239, 250, 180, 0.4),
                inset -30px 0 0 rgba(255, 196, 140, 0.4),
                inset 0 -30px 0 rgba(245, 105, 145, 0.4);
            animation: shadowsdancing 4s infinite;
        }

        .error-container>span:nth-of-type(2):before {
            -webkit-transform: rotate(45deg);
            -moz-transform: rotate(45deg);
            transform: rotate(45deg);
        }

        .screen-reader-text {
            position: absolute;
            top: -9999em;
            left: -9999em;
        }

        @keyframes shadowsdancing {
            0% {
                box-shadow: inset 30px 0 0 rgba(209, 242, 165, 0.4),
                    inset 0 30px 0 rgba(239, 250, 180, 0.4),
                    inset -30px 0 0 rgba(255, 196, 140, 0.4),
                    inset 0 -30px 0 rgba(245, 105, 145, 0.4);
            }

            25% {
                box-shadow: inset 30px 0 0 rgba(245, 105, 145, 0.4),
                    inset 0 30px 0 rgba(209, 242, 165, 0.4),
                    inset -30px 0 0 rgba(239, 250, 180, 0.4),
                    inset 0 -30px 0 rgba(255, 196, 140, 0.4);
            }

            50% {
                box-shadow: inset 30px 0 0 rgba(255, 196, 140, 0.4),
                    inset 0 30px 0 rgba(245, 105, 145, 0.4),
                    inset -30px 0 0 rgba(209, 242, 165, 0.4),
                    inset 0 -30px 0 rgba(239, 250, 180, 0.4);
            }

            75% {
                box-shadow: inset 30px 0 0 rgba(239, 250, 180, 0.4),
                    inset 0 30px 0 rgba(255, 196, 140, 0.4),
                    inset -30px 0 0 rgba(245, 105, 145, 0.4),
                    inset 0 -30px 0 rgba(209, 242, 165, 0.4);
            }

            100% {
                box-shadow: inset 30px 0 0 rgba(209, 242, 165, 0.4),
                    inset 0 30px 0 rgba(239, 250, 180, 0.4),
                    inset -30px 0 0 rgba(255, 196, 140, 0.4),
                    inset 0 -30px 0 rgba(245, 105, 145, 0.4);
            }
        }

        @keyframes colordancing {
            0% {
                color: #D1F2A5;
            }

            25% {
                color: #F56991;
            }

            50% {
                color: #FFC48C;
            }

            75% {
                color: #EFFAB4;
            }

            100% {
                color: #D1F2A5;
            }
        }

        @keyframes colordancing2 {
            0% {
                color: #FFC48C;
            }

            25% {
                color: #EFFAB4;
            }

            50% {
                color: #D1F2A5;
            }

            75% {
                color: #F56991;
            }

            100% {
                color: #FFC48C;
            }
        }

        * {
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
    </style>
     <script>
        window.addEventListener('online', function(e) {
            location.reload();
        });
    </script>
    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
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
                    <img src="{{ asset('assets/img/offline.png') }}" width="150px" height="150px" alt="">
                </section>
                <button onclick="location.reload()" class="btn btn-danger mt-5">
                    <i class="fa fa-refresh"></i> Actualiser
                </button>
            </div>
        </div>
    </div>
</body>

</html>

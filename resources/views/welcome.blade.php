<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/welcome.css') }}" rel="stylesheet">
        <style>
            html, body {
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            .welcome_wrap {
                display: flex;
                position: relative;
                width: 100%;
                height: 100%;
                background-color: rgba(255, 255, 255, 0.3);
            }

            .welcome_link {
                display: block;
                position: absolute;
                width: 100%;
                height: 100%;
                z-index: 9;
            }

            .content {
                display: flex;
                position: absolute;
                text-align: center;
                width: 100%;
                height: 100%;
            }

            .welcome_hashigo {
                display: flex;
                position: absolute;
                top: 50%;
                left: 50%;
                font-size: 8rem;
                transform: translateY(-50%) translateX(-50%);
                -webkit- transform: translateY(-50%) translateX(-50%);
            }
        </style>
    </head>
    <body>
        <div class="welcome_wrap">
            <a href="/top" class="welcome_link"></a>
            <div class="content">
                <div class="welcome_hashigo">
                    HashiGO!
                </div>
            </div>
        </div>
    </body>
</html>

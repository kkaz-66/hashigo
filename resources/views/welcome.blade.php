<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>HashiGO!!</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <link href="{{ asset('css/welcome.css') }}" rel="stylesheet">
    </head>
    <body>
        <div class="welcome_wrap">
            <a href="/top" class="welcome_link"></a>
            <div class="content">
                <div class="welcome_hashigo">
                    HashiGO!!
                </div>
            </div>
        </div>
    </body>
</html>

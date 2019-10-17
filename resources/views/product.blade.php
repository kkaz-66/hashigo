<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Google_Map</title>
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
    <body>

        <div id="app">
            <product-component test="{{ json_encode($product, JSON_UNESCAPED_UNICODE)}}" test2="{{ json_encode($place, JSON_UNESCAPED_UNICODE) }}">
            </product-component>
        </div>

        <script src=" {{ mix('js/app.js') }} "></script>
    </body>
</html>
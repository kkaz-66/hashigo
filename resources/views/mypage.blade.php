<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>mypage</title>
</head>
<body>
    <div id="app">
        <header-component></header-component>
        <footer-component></footer-component>
    </div>

    <script src=" {{ mix('js/app.js') }} "></script>
</body>
</html>
@extends('layouts.app')
@section('content')
@inject('hahigo_history','App\Http\Controllers\MypageController')
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
   
    @foreach ($hashigos as $hashigo)
        @if($user->id == $hashigo->id)
            {{$hashigo->member_id}}
            {{$hashigo->id}}
            {{$hashigo->first_store_id}}
            {{$hashigo->second_store_id}}
            {{$hashigo->third_store_id}}
            {{$hashigo->created_at}}
            <br>
        @endif
    @endforeach

    <div id="app">
        <header-component></header-component>
        <mypage-component></mypage-component>
        <footer-component></footer-component>
    </div>

    <script src=" {{ mix('js/app.js') }} "></script>
</body>
</html>
@endsection

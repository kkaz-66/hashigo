@extends('layouts.app')
@section('content')
@inject('hashigo_arr','App\Http\Controllers\MypageController')

{{$hashigos}}<br>
@foreach($hashigos as $hashigo)
{{ $hashigo->id }}
@endforeach


@php
var_dump($hashigos);
@endphp
<mypage-component></mypage-component>
<footer-component></footer-component>
@endsection
@extends('layouts.app')
@section('content')
@php
var_dump($user_history);
@endphp
<mypage-component history="{{ json_encode($user_history, JSON_UNESCAPED_UNICODE)}}"></mypage-component>
@endsection
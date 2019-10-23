@extends('layouts.app')
@section('content')
<mypage-component history="{{ json_encode($user_history, JSON_UNESCAPED_UNICODE)}}"></mypage-component>
<footer-component></footer-component>
@endsection
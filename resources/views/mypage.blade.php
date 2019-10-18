@extends('layouts.app')
@section('content')
@inject('hashigo_arr','App\Http\Controllers\MypageController')
@foreach ($hashigos as $hashigo)
    @if($user->id == $hashigo->member_id)
        {{$hashigo->member_id}}
        {{$hashigo->id}}
        {{$hashigo->first_store_id}}
        {{$hashigo->second_store_id}}
        {{$hashigo->third_store_id}}
        {{$hashigo->created_at}}<br>
        hashigo shop: {{ ($hashigo_arr["results"]["shop"]) -> seach_shop($hashigo->first_store_id) }}
        <br>
    @endif
@endforeach
<mypage-component></mypage-component>
<footer-component></footer-component>
@endsection
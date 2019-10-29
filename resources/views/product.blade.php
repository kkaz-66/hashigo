@extends('layouts.app')
@section('content')
@if (!empty($listid))
    <product-component 
        product="{{ json_encode($product, JSON_UNESCAPED_UNICODE)}}" 
        place="{{ json_encode($place, JSON_UNESCAPED_UNICODE) }}" 
        userid="{{ Auth::id() }}" 
        listid="{{ $listid }}"
        hisname="{{ $hisname }}">
    </product-component>
@else
    <product-component 
        product="{{ json_encode($product, JSON_UNESCAPED_UNICODE)}}" 
        place="{{ json_encode($place, JSON_UNESCAPED_UNICODE) }}" 
        userid="{{ Auth::id() }}">
    </product-component>
@endif
  
@endsection
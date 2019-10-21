@extends('layouts.app')
@section('content')
<product-component product="{{ json_encode($product, JSON_UNESCAPED_UNICODE)}}" place="{{ json_encode($place, JSON_UNESCAPED_UNICODE) }}">
            </product-component>
@endsection
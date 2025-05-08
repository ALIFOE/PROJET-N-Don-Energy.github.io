@extends('layouts.app')

@section('content')
    @include('admin.products.form', ['product' => $product])
@endsection
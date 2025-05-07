@extends('admin.layouts.admin')

@section('content')
    @include('admin.products.form', ['product' => $product])
@endsection
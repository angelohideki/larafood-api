@extends('adminlte::page')

@section('title', "Editar Produto: {$product->title}")

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0">
                <i class="fas fa-edit text-primary"></i>
                Editar Produto
            </h1>
            <small class="text-muted">Editando: <strong>{{ $product->title }}</strong></small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produtos</a></li>
                <li class="breadcrumb-item active">Editar</li>
            </ol>
        </nav>
    </div>
@stop

@section('content')
    <form action="{{ route('products.update', $product->id) }}"
        class="form"
        method="POST"
        enctype="multipart/form-data"
        id="productForm">
        @csrf
        @method('PUT')
        @include('admin.pages.products._partials.form')
    </form>
@endsection

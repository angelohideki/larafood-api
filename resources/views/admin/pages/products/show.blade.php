@extends('adminlte::page')

@section('title', "Detalhes do produto {$product->title}")

@section('content_header')
    <h1>Detalhes do produto <b>{{ $product->title }}</b></h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            <ul>
                <li>
                    {{ $product->image }}
                </li>
                <li>
                    <strong>Título: </strong> {{ $product->title }}
                </li>
                <li>
                    <strong>Descrição: </strong> {{ $product->description }}
                </li>
                <li>
                    <strong>Flag: </strong> {{ $product->flag }}
                </li>
            </ul>

            @include('admin.includes.alerts')

            <form action="{{ route('products.destroy', $product->id) }}" method="POST">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="fas fa-trash"></i> DELETAR O PRODUTO {{ $product->title }}</button>
            </form>
        </div>
    </div>
@endsection

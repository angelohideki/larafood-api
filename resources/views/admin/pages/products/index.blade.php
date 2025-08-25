@extends('adminlte::page')

@section('title', 'Produtos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Produtos</li>
            </ol>
            <h1 class="m-0">Produtos</h1>
        </div>
        <a href="{{ route('products.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Novo Produto
        </a>
    </div>
@stop

@section('content')
    {{-- Incluir o sistema de alerts/toast --}}
    @include('admin.includes.alerts')
    
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <form action="{{ route('products.search') }}" method="POST" class="form-inline">
                        @csrf
                        <div class="input-group" style="width: 100%; max-width: 400px;">
                            <input type="text" name="filter" placeholder="Buscar produtos..."
                                class="form-control" value="{{ $filters['filter'] ?? '' }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-4 text-right">
                    <small class="text-muted">
                        Total: {{ $products->total() }} produto(s)
                    </small>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            @if($products->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th width="120" class="text-center">Imagem</th>
                                <th>Produto</th>
                                <th width="120" class="text-center">Preço</th>
                                <th width="100" class="text-center">Status</th>
                                <th width="200" class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($products as $product)
                                <tr>
                                    <td class="text-center align-middle">
                                        <div class="product-image-container">
                                            @if($product->image)
                                                <img src="{{ url("storage/{$product->image}") }}"
                                                    alt="{{ $product->title }}"
                                                    class="product-thumbnail rounded"
                                                    style="width: 80px; height: 80px; object-fit: cover; border: 2px solid #e9ecef;">
                                            @else
                                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                    style="width: 80px; height: 80px; border: 2px solid #e9ecef;">
                                                    <i class="fas fa-image text-muted"></i>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <div>
                                            <h6 class="mb-1 font-weight-bold">{{ $product->title }}</h6>
                                            @if($product->description)
                                                <small class="text-muted">{{ Str::limit($product->description, 60) }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center align-middle">
                                        @if($product->price)
                                            <span class="font-weight-bold text-success">
                                                R$ {{ number_format($product->price, 2, ',', '.') }}
                                            </span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td class="text-center align-middle">
                                        <span class="badge badge-success">Ativo</span>
                                    </td>
                                    <td class="text-center align-middle">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('products.show', $product->id) }}"
                                               class="btn btn-sm btn-outline-info" title="Visualizar">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('products.edit', $product->id) }}"
                                               class="btn btn-sm btn-outline-primary" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('products.categories', $product->id) }}"
                                               class="btn btn-sm btn-outline-warning" title="Categorias">
                                                <i class="fas fa-layer-group"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-box-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhum produto encontrado</h5>
                    <p class="text-muted">Comece criando seu primeiro produto.</p>
                    <a href="{{ route('products.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Criar Produto
                    </a>
                </div>
            @endif
        </div>

        @if($products->count() > 0)
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">
                            Mostrando {{ $products->firstItem() }} a {{ $products->lastItem() }}
                            de {{ $products->total() }} resultados
                        </small>
                    </div>
                    <div>
                        @if (isset($filters))
                            {!! $products->appends($filters)->links() !!}
                        @else
                            {!! $products->links() !!}
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
@stop

@section('css')
    <style>
        .product-thumbnail {
            transition: transform 0.2s ease-in-out;
        }

        .product-thumbnail:hover {
            transform: scale(1.1);
            cursor: pointer;
        }

        .table td {
            vertical-align: middle;
        }

        .btn-group .btn {
            margin: 0 1px;
        }

        .card {
            border: none;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        .product-image-container {
            position: relative;
        }

        .badge {
            font-size: 0.75em;
        }
    </style>
@stop

@section('js')
    <script>
        // Adicionar funcionalidade de visualização rápida da imagem
        $(document).ready(function() {
            $('.product-thumbnail').on('click', function() {
                const src = $(this).attr('src');
                const title = $(this).attr('alt');

                // Criar modal para visualização da imagem
                const modal = `
                    <div class="modal fade" id="imageModal" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">${title}</h5>
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="${src}" class="img-fluid" alt="${title}">
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                // Remover modal anterior se existir
                $('#imageModal').remove();

                // Adicionar e mostrar novo modal
                $('body').append(modal);
                $('#imageModal').modal('show');
            });
        });
    </script>
@stop

@extends('adminlte::page')

@section('title', "Detalhes do produto {$product->title}")

@section('content_header')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">
                        <i class="fas fa-box-open text-primary"></i>
                        Detalhes do Produto
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produtos</a></li>
                        <li class="breadcrumb-item active">{{ $product->title }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Informações Principais -->
            <div class="col-md-8">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-info-circle"></i>
                            Informações do Produto
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Voltar
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <span class="info-box-icon bg-primary">
                                        <i class="fas fa-tag"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Título</span>
                                        <span class="info-box-number">{{ $product->title }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <span class="info-box-icon bg-success">
                                        <i class="fas fa-dollar-sign"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Preço</span>
                                        <span class="info-box-number">R$ {{ number_format($product->price, 2, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <span class="info-box-icon bg-info">
                                        <i class="fas fa-flag"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Flag</span>
                                        <span class="info-box-number">{{ $product->flag ?? 'Não definida' }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box bg-light">
                                    <span class="info-box-icon bg-warning">
                                        <i class="fas fa-calendar"></i>
                                    </span>
                                    <div class="info-box-content">
                                        <span class="info-box-text">Criado em</span>
                                        <span class="info-box-number">{{ $product->created_at->format('d/m/Y H:i') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mt-3">
                            <div class="col-12">
                                <div class="form-group">
                                    <label><i class="fas fa-align-left"></i> Descrição:</label>
                                    <div class="bg-light p-3 rounded">
                                        {{ $product->description }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Categorias do Produto -->
                <div class="card card-info card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-tags"></i>
                            Categorias
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('products.categories', $product->id) }}" class="btn btn-info btn-sm">
                                <i class="fas fa-cog"></i> Gerenciar
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($product->categories->count() > 0)
                            <div class="row">
                                @foreach($product->categories as $category)
                                    <div class="col-md-4 mb-2">
                                        <span class="badge badge-info badge-lg">
                                            <i class="fas fa-tag"></i> {{ $category->name }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                Este produto ainda não possui categorias associadas.
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Imagem e Ações -->
            <div class="col-md-4">
                <!-- Imagem do Produto -->
                <div class="card card-widget widget-user">
                    <div class="widget-user-header bg-primary">
                        <h3 class="widget-user-username">{{ $product->title }}</h3>
                        <h5 class="widget-user-desc">Produto</h5>
                    </div>
                    <div class="widget-user-image">
                        @if($product->image)
                            <img class="img-circle elevation-2"
                                 src="{{ url("storage/{$product->image}") }}"
                                 alt="{{ $product->title }}">
                        @else
                            <div class="img-circle elevation-2 bg-secondary d-flex align-items-center justify-content-center"
                                 style="width: 90px; height: 90px; margin: 0 auto;">
                                <i class="fas fa-image fa-2x text-white"></i>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer">
                        <div class="row">
                            <div class="col-sm-6 border-right">
                                <div class="description-block">
                                    <h5 class="description-header text-success">
                                        R$ {{ number_format($product->price, 2, ',', '.') }}
                                    </h5>
                                    <span class="description-text">PREÇO</span>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="description-block">
                                    <h5 class="description-header text-info">
                                        {{ $product->categories->count() }}
                                    </h5>
                                    <span class="description-text">CATEGORIAS</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ações -->
                <div class="card card-danger card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-cogs"></i>
                            Ações
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="btn-group-vertical w-100" role="group">
                            <a href="{{ route('products.edit', $product->id) }}"
                               class="btn btn-primary mb-2">
                                <i class="fas fa-edit"></i> Editar Produto
                            </a>

                            <a href="{{ route('products.categories', $product->id) }}"
                               class="btn btn-info mb-2">
                                <i class="fas fa-tags"></i> Gerenciar Categorias
                            </a>

                            <button type="button"
                                    class="btn btn-danger"
                                    data-toggle="modal"
                                    data-target="#deleteModal">
                                <i class="fas fa-trash"></i> Excluir Produto
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de Confirmação de Exclusão -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h5 class="modal-title text-white" id="deleteModalLabel">
                        <i class="fas fa-exclamation-triangle"></i> Confirmar Exclusão
                    </h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                        <h5>Tem certeza que deseja excluir este produto?</h5>
                        <p class="text-muted">
                            <strong>{{ $product->title }}</strong><br>
                            Esta ação não pode ser desfeita!
                        </p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i> Cancelar
                    </button>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash"></i> Sim, Excluir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('admin.includes.alerts')
@endsection

@section('css')
<style>
.info-box-number {
    font-size: 1.1rem !important;
    font-weight: 600;
}

.badge-lg {
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
}

.description-block {
    text-align: center;
}

.widget-user-image img,
.widget-user-image div {
    width: 90px;
    height: 90px;
}

.card-outline {
    border-top: 3px solid;
}

.btn-group-vertical .btn {
    border-radius: 0.25rem !important;
}

.modal-header.bg-danger {
    border-bottom: 1px solid #dc3545;
}

@media (max-width: 768px) {
    .col-md-8, .col-md-4 {
        margin-bottom: 1rem;
    }

    .info-box {
        margin-bottom: 1rem;
    }
}
</style>
@endsection

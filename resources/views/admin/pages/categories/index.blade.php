@extends('adminlte::page')

@section('title', 'Categorias')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Categorias</li>
            </ol>
            <h1 class="m-0">Categorias</h1>
        </div>
        @can('add_cat')
            <a href="{{ route('categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Nova Categoria
            </a>
        @endcan
    </div>
@stop

@section('content')
    {{-- Incluir o sistema de alerts/toast --}}
    @include('admin.includes.alerts')
    
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <form action="{{ route('categories.search') }}" method="POST" class="form-inline">
                        @csrf
                        <div class="input-group" style="width: 100%; max-width: 400px;">
                            <input type="text" name="filter" placeholder="Buscar categorias..."
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
                        Total: {{ $categories->total() }} categoria(s)
                    </small>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            @if($categories->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0">Nome</th>
                                <th class="border-0">Descrição</th>
                                <th class="border-0 text-center" width="150">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td class="align-middle">
                                        <strong>{{ $category->name }}</strong>
                                    </td>
                                    <td class="align-middle">
                                        <span class="text-muted">{{ Str::limit($category->description, 50) }}</span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('categories.show', $category->id) }}" 
                                               class="btn btn-sm btn-outline-info" 
                                               title="Visualizar">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @can('edit_cat')
                                                <a href="{{ route('categories.edit', $category->id) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhuma categoria encontrada</h5>
                    <p class="text-muted">Comece criando sua primeira categoria.</p>
                    @can('add_cat')
                        <a href="{{ route('categories.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Nova Categoria
                        </a>
                    @endcan
                </div>
            @endif
        </div>

        @if($categories->count() > 0)
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">
                            Mostrando {{ $categories->firstItem() }} a {{ $categories->lastItem() }} 
                            de {{ $categories->total() }} resultados
                        </small>
                    </div>
                    <div>
                        @if (isset($filters))
                            {!! $categories->appends($filters)->links() !!}
                        @else
                            {!! $categories->links() !!}
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
@stop

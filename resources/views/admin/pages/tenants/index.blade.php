@extends('adminlte::page')

@section('title', 'Empresas')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0">
                <i class="fas fa-building text-muted"></i>
                Empresas
            </h1>
            <small class="text-muted">Gerencie as empresas do sistema</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Empresas</li>
            </ol>
        </nav>
    </div>
@stop

@section('content')
    {{-- Incluir o sistema de alerts/toast --}}
    @include('admin.includes.alerts')

    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-search text-muted mr-2"></i>
                        <h5 class="card-title mb-0">Buscar Empresas</h5>
                    </div>
                    <a href="{{ route('tenants.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Nova Empresa
                    </a>
                </div>
                <div class="card-body">
                    <form action="{{ route('tenants.search') }}" method="POST" class="form-inline mb-3">
                        @csrf
                        <div class="input-group" style="width: 300px;">
                            <input type="text"
                                   name="filter"
                                   placeholder="Filtrar por nome, email ou CNPJ..."
                                   class="form-control"
                                   value="{{ $filters['filter'] ?? '' }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-outline-secondary">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                        @if(isset($filters['filter']) && $filters['filter'])
                            <a href="{{ route('tenants.index') }}" class="btn btn-outline-danger btn-sm ml-2">
                                <i class="fas fa-times"></i> Limpar
                            </a>
                        @endif
                    </form>

                    @if(isset($filters['filter']) && $filters['filter'])
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            Exibindo resultados para: <strong>{{ $filters['filter'] }}</strong>
                            <span class="badge badge-primary ml-2">{{ $tenants->total() }} {{ $tenants->total() == 1 ? 'empresa encontrada' : 'empresas encontradas' }}</span>
                        </div>
                    @else
                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i>
                                Total de empresas: <strong>{{ $tenants->total() }}</strong>
                            </small>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list text-secondary"></i>
                        Lista de Empresas
                    </h5>
                </div>
                <div class="card-body p-0">
                    @if($tenants->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th width="100" class="text-center">Logo</th>
                                        <th>Nome</th>
                                        <th>Email</th>
                                        <th>CNPJ</th>
                                        <th width="100" class="text-center">Status</th>
                                        <th width="150" class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($tenants as $tenant)
                                        <tr>
                                            <td class="text-center align-middle">
                                                @if($tenant->logo)
                                                    <img src="{{ url("storage/{$tenant->logo}") }}"
                                                         alt="{{ $tenant->name }}"
                                                         class="img-thumbnail"
                                                         style="max-width: 60px; max-height: 60px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                                         style="width: 60px; height: 60px;">
                                                        <i class="fas fa-building text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                <div>
                                                    <strong>{{ $tenant->name }}</strong>
                                                    @if($tenant->url)
                                                        <br><small class="text-muted">{{ $tenant->url }}</small>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="align-middle">{{ $tenant->email }}</td>
                                            <td class="align-middle">{{ $tenant->cnpj }}</td>
                                            <td class="text-center align-middle">
                                                @if($tenant->active == 'Y')
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check"></i> Ativo
                                                    </span>
                                                @else
                                                    <span class="badge badge-danger">
                                                        <i class="fas fa-times"></i> Inativo
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="text-center align-middle">
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('tenants.show', $tenant->id) }}"
                                                       class="btn btn-sm btn-outline-info"
                                                       title="Visualizar">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @can('edit_tenant')
                                                        <a href="{{ route('tenants.edit', $tenant->id) }}"
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
                            <i class="fas fa-building fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">Nenhuma empresa encontrada</h5>
                            @if(isset($filters['filter']) && $filters['filter'])
                                <p class="text-muted">Tente ajustar os filtros de busca</p>
                                <a href="{{ route('tenants.index') }}" class="btn btn-outline-primary">
                                    <i class="fas fa-list"></i> Ver todas as empresas
                                </a>
                            @else
                                <p class="text-muted">Comece cadastrando sua primeira empresa</p>
                                <a href="{{ route('tenants.create') }}" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Cadastrar Empresa
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
                @if($tenants->hasPages())
                    <div class="card-footer bg-white">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                Exibindo {{ $tenants->firstItem() }} a {{ $tenants->lastItem() }}
                                de {{ $tenants->total() }} empresas
                            </small>
                            <div>
                                @if (isset($filters))
                                    {!! $tenants->appends($filters)->links() !!}
                                @else
                                    {!! $tenants->links() !!}
                                @endif
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@stop

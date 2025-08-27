@extends('adminlte::page')

@section('title', 'Planos')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Planos</li>
            </ol>
            <h1 class="m-0">Planos</h1>
        </div>
        @can('add_plan')
            <a href="{{ route('plans.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Novo Plano
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
                    <form action="{{ route('plans.search') }}" method="POST" class="form-inline">
                        @csrf
                        <div class="input-group" style="width: 100%; max-width: 400px;">
                            <input type="text" name="filter" placeholder="Buscar planos..."
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
                        Total: {{ $plans->total() }} plano(s)
                    </small>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            @if($plans->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0">Nome</th>
                                <th class="border-0">Preço</th>
                                <th class="border-0 text-center" width="200">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($plans as $plan)
                                <tr>
                                    <td class="align-middle">
                                        <strong>{{ $plan->name }}</strong>
                                        @if($plan->description)
                                            <br><small class="text-muted">{{ Str::limit($plan->description, 50) }}</small>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <span class="badge badge-success font-weight-bold">
                                            R$ {{ number_format($plan->price, 2, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('plans.show', $plan->url) }}" 
                                               class="btn btn-sm btn-outline-info" 
                                               title="Visualizar">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('details.plan.index', $plan->url) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Detalhes">
                                                <i class="fas fa-list"></i>
                                            </a>
                                            @can('edit_plan')
                                                <a href="{{ route('plans.edit', $plan->url) }}" 
                                                   class="btn btn-sm btn-outline-warning" 
                                                   title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endcan
                                            <a href="{{ route('plans.profiles', $plan->id) }}" 
                                               class="btn btn-sm btn-outline-secondary" 
                                               title="Perfis">
                                                <i class="fas fa-users"></i>
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
                    <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhum plano encontrado</h5>
                    <p class="text-muted">Comece criando seu primeiro plano.</p>
                    @can('add_plan')
                        <a href="{{ route('plans.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Novo Plano
                        </a>
                    @endcan
                </div>
            @endif
        </div>

        @if($plans->count() > 0)
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">
                            Mostrando {{ $plans->firstItem() }} a {{ $plans->lastItem() }} 
                            de {{ $plans->total() }} resultados
                        </small>
                    </div>
                    <div>
                        @if (isset($filters))
                            {!! $plans->appends($filters)->links() !!}
                        @else
                            {!! $plans->links() !!}
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
@stop

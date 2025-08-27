@extends('adminlte::page')

@section('title', 'Perfis')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item active">Perfis</li>
            </ol>
            <h1 class="m-0">Perfis</h1>
        </div>
        <a href="{{ route('profiles.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Novo Perfil
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
                    <form action="{{ route('profiles.search') }}" method="POST" class="form-inline">
                        @csrf
                        <div class="input-group" style="width: 100%; max-width: 400px;">
                            <input type="text" name="filter" placeholder="Buscar perfis..."
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
                        Total: {{ $profiles->total() }} perfil(s)
                    </small>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if($profiles->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0">Nome</th>
                                <th class="border-0">Descrição</th>
                                <th class="border-0 text-center" width="250">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($profiles as $profile)
                                <tr>
                                    <td class="align-middle">
                                        <strong>{{ $profile->name }}</strong>
                                    </td>
                                    <td class="align-middle">
                                        @if($profile->description)
                                            <small class="text-muted">{{ Str::limit($profile->description, 50) }}</small>
                                        @else
                                            <small class="text-muted">Sem descrição</small>
                                        @endif
                                    </td>
                                    <td class="align-middle text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('profiles.show', $profile->id) }}" 
                                               class="btn btn-sm btn-outline-info" 
                                               title="Visualizar">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('profiles.edit', $profile->id) }}" 
                                               class="btn btn-sm btn-outline-warning" 
                                               title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="{{ route('profiles.permissions', $profile->id) }}" 
                                               class="btn btn-sm btn-outline-secondary" 
                                               title="Permissões">
                                                <i class="fas fa-lock"></i>
                                            </a>
                                            <a href="{{ route('profiles.plans', $profile->id) }}" 
                                               class="btn btn-sm btn-outline-primary" 
                                               title="Planos">
                                                <i class="fas fa-list-alt"></i>
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
                    <i class="fas fa-user-tag fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhum perfil encontrado</h5>
                    <p class="text-muted">Comece criando seu primeiro perfil.</p>
                    <a href="{{ route('profiles.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Novo Perfil
                    </a>
                </div>
            @endif
        </div>
        @if($profiles->count() > 0)
            <div class="card-footer bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">
                            Mostrando {{ $profiles->firstItem() }} a {{ $profiles->lastItem() }} 
                            de {{ $profiles->total() }} resultados
                        </small>
                    </div>
                    <div>
                        @if (isset($filters))
                            {!! $profiles->appends($filters)->links() !!}
                        @else
                            {!! $profiles->links() !!}
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
@stop

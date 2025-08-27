@extends('adminlte::page')

@section('title', "Detalhes do perfil {$profile->name}")

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profiles.index') }}">Perfis</a></li>
                <li class="breadcrumb-item active">{{ $profile->name }}</li>
            </ol>
            <h1 class="m-0">Detalhes do Perfil</h1>
        </div>
        <div>
            <a href="{{ route('profiles.edit', $profile->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Editar
            </a>
            <a href="{{ route('profiles.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
@stop

@section('content')
    @include('admin.includes.alerts')
    
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-user-tag"></i> {{ $profile->name }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0 text-muted">Nome:</h6>
                        </div>
                        <div class="col-sm-9">
                            <strong>{{ $profile->name }}</strong>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0 text-muted">Descrição:</h6>
                        </div>
                        <div class="col-sm-9">
                            {{ $profile->description ?? 'Sem descrição informada' }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0 text-muted">Criado em:</h6>
                        </div>
                        <div class="col-sm-9">
                            {{ $profile->created_at ? $profile->created_at->format('d/m/Y às H:i') : 'N/A' }}
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-3">
                            <h6 class="mb-0 text-muted">Atualizado em:</h6>
                        </div>
                        <div class="col-sm-9">
                            {{ $profile->updated_at ? $profile->updated_at->format('d/m/Y às H:i') : 'N/A' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h6 class="mb-0">
                        <i class="fas fa-cogs"></i> Ações Disponíveis
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('profiles.permissions', $profile->id) }}" 
                           class="btn btn-outline-primary btn-block mb-2">
                            <i class="fas fa-lock"></i> Gerenciar Permissões
                        </a>
                        <a href="{{ route('profiles.plans', $profile->id) }}" 
                           class="btn btn-outline-info btn-block mb-2">
                            <i class="fas fa-list-alt"></i> Gerenciar Planos
                        </a>
                        <a href="{{ route('profiles.edit', $profile->id) }}" 
                           class="btn btn-outline-warning btn-block mb-2">
                            <i class="fas fa-edit"></i> Editar Perfil
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="card shadow-sm mt-3">
                <div class="card-header bg-danger text-white">
                    <h6 class="mb-0">
                        <i class="fas fa-exclamation-triangle"></i> Zona de Perigo
                    </h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-3">
                        A exclusão do perfil é irreversível. Certifique-se de que não há usuários vinculados a este perfil.
                    </p>
                    @can('delete_profile')
                        <button type="button" 
                                class="btn btn-danger btn-block" 
                                onclick="confirmDelete()">
                            <i class="fas fa-trash"></i> Excluir Perfil
                        </button>
                        
                        <form id="deleteForm" 
                              action="{{ route('profiles.destroy', $profile->id) }}" 
                              method="POST" 
                              style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection

@push('js')
<script>
function confirmDelete() {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Tem certeza?',
            text: 'Esta ação não pode ser desfeita!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, deletar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm').submit();
            }
        });
    } else {
        if (confirm('Tem certeza que deseja deletar o perfil "{{ $profile->name }}"? Esta ação não pode ser desfeita!')) {
            document.getElementById('deleteForm').submit();
        }
    }
}
</script>
@endpush

@extends('adminlte::page')

@section('title', "Editar o Perfil {$profile->name}")

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profiles.index') }}">Perfis</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profiles.show', $profile->id) }}">{{ $profile->name }}</a></li>
                <li class="breadcrumb-item active">Editar</li>
            </ol>
            <h1 class="m-0">Editar Perfil</h1>
        </div>
        <div>
            <a href="{{ route('profiles.show', $profile->id) }}" class="btn btn-info">
                <i class="fas fa-eye"></i> Visualizar
            </a>
            <a href="{{ route('profiles.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
@stop

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-edit"></i> Editar Perfil: {{ $profile->name }}
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profiles.update', $profile->id) }}" class="form" method="POST">
                        @method('PUT')
                        @include('admin.pages.profiles._partials.form')
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cogs text-secondary"></i>
                        Ações
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('profiles.show', $profile->id) }}" 
                           class="btn btn-info btn-block mb-2">
                            <i class="fas fa-eye"></i> Visualizar Perfil
                        </a>
                        
                        <a href="{{ route('profiles.index') }}" 
                           class="btn btn-secondary btn-block mb-3">
                            <i class="fas fa-arrow-left"></i> Voltar à Lista
                        </a>
                        
                        @can('delete_profile')
                            <hr>
                            <div class="alert alert-warning">
                                <small><i class="fas fa-exclamation-triangle"></i> Zona de Perigo</small>
                            </div>
                            
                            <button type="button" 
                                    class="btn btn-danger btn-block" 
                                    onclick="confirmDelete()">
                                <i class="fas fa-trash"></i> Deletar Perfil
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
    </div>
@endsection

@push('js')
<script>
function confirmDelete() {
    // Verificar se SweetAlert2 está carregado
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
        // Fallback para confirm nativo
        if (confirm('Tem certeza que deseja deletar o perfil "{{ $profile->name }}"? Esta ação não pode ser desfeita!')) {
            document.getElementById('deleteForm').submit();
        }
    }
}
</script>
@endpush

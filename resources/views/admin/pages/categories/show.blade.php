@extends('adminlte::page')

@section('title', "Detalhes da categoria {$category->name}")

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0">
                <i class="fas fa-eye text-info"></i>
                Detalhes da Categoria
            </h1>
            <small class="text-muted">Visualizando: <strong>{{ $category->name }}</strong></small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categorias</a></li>
                <li class="breadcrumb-item active">{{ $category->name }}</li>
            </ol>
        </nav>
    </div>
@stop

@section('content')
    {{-- Incluir o sistema de alerts/toast --}}
    @include('admin.includes.alerts')
    
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle text-info"></i>
                        Informações da Categoria
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold text-muted">Nome:</label>
                                <p class="h5">{{ $category->name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="font-weight-bold text-muted">URL:</label>
                                <p class="text-monospace">{{ $category->url }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold text-muted">Descrição:</label>
                        <p class="text-justify">{{ $category->description }}</p>
                    </div>
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
                        @can('edit_cat')
                            <a href="{{ route('categories.edit', $category->id) }}" 
                               class="btn btn-primary btn-block mb-2">
                                <i class="fas fa-edit"></i> Editar Categoria
                            </a>
                        @endcan
                        
                        <a href="{{ route('categories.index') }}" 
                           class="btn btn-secondary btn-block mb-3">
                            <i class="fas fa-arrow-left"></i> Voltar à Lista
                        </a>
                        
                        @can('delete_cat')
                            <hr>
                            <div class="alert alert-warning">
                                <small><i class="fas fa-exclamation-triangle"></i> Zona de Perigo</small>
                            </div>
                            
                            <button type="button" 
                                    class="btn btn-danger btn-block" 
                                    onclick="confirmDelete()">
                                <i class="fas fa-trash"></i> Deletar Categoria
                            </button>
                            
                            <form id="deleteForm" 
                                  action="{{ route('categories.destroy', $category->id) }}" 
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
        if (confirm('Tem certeza que deseja deletar a categoria "{{ $category->name }}"? Esta ação não pode ser desfeita!')) {
            document.getElementById('deleteForm').submit();
        }
    }
}
</script>
@endpush

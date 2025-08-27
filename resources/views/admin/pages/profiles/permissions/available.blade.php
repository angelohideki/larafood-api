@extends('adminlte::page')

@section('title', "Permissões disponíveis perfil {$profile->name}")

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profiles.index') }}">Perfis</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profiles.show', $profile->id) }}">{{ $profile->name }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profiles.permissions', $profile->id) }}">Permissões</a></li>
                <li class="breadcrumb-item active">Adicionar</li>
            </ol>
            <h1 class="m-0">
                <i class="fas fa-plus-circle text-success"></i>
                Adicionar Permissões
            </h1>
            <small class="text-muted">Perfil: <strong>{{ $profile->name }}</strong></small>
        </div>
        <div>
            <a href="{{ route('profiles.permissions', $profile->id) }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
@stop

@section('content')
    {{-- Incluir o sistema de alerts/toast --}}
    @include('admin.includes.alerts')
    
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <form action="{{ route('profiles.permissions.available', $profile->id) }}" method="POST" class="form-inline">
                        @csrf
                        <div class="input-group" style="width: 100%; max-width: 400px;">
                            <input type="text" name="filter" placeholder="Buscar permissões..."
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
                        Disponíveis: {{ $permissions->total() }} permissão(ões)
                    </small>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            @if($permissions->count() > 0)
                <form action="{{ route('profiles.permissions.attach', $profile->id) }}" method="POST" id="attachForm">
                    @csrf
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th class="border-0" width="50">
                                        <input type="checkbox" id="selectAll" title="Selecionar todos">
                                    </th>
                                    <th class="border-0">Nome da Permissão</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $permission)
                                    <tr>
                                        <td class="align-middle">
                                            <input type="checkbox" name="permissions[]" value="{{ $permission->id }}" class="permission-checkbox">
                                        </td>
                                        <td class="align-middle">
                                            <strong>{{ $permission->name }}</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted" id="selectedCount">0 permissão(ões) selecionada(s)</small>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-success" id="attachButton" disabled>
                                    <i class="fas fa-link"></i> Vincular Permissões
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                    <h5 class="text-muted">Todas as permissões já foram vinculadas</h5>
                    <p class="text-muted">Não há permissões disponíveis para vincular a este perfil.</p>
                    <a href="{{ route('profiles.permissions', $profile->id) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar às Permissões
                    </a>
                </div>
            @endif
        </div>

        @if($permissions->count() > 0)
            <div class="card-footer bg-white border-top">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">
                            Mostrando {{ $permissions->firstItem() }} a {{ $permissions->lastItem() }} 
                            de {{ $permissions->total() }} resultados
                        </small>
                    </div>
                    <div>
                        @if (isset($filters))
                            {!! $permissions->appends($filters)->links() !!}
                        @else
                            {!! $permissions->links() !!}
                        @endif
                    </div>
                </div>
            </div>
        @endif
    </div>
@stop

@push('js')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllCheckbox = document.getElementById('selectAll');
    const permissionCheckboxes = document.querySelectorAll('.permission-checkbox');
    const attachButton = document.getElementById('attachButton');
    const selectedCount = document.getElementById('selectedCount');
    
    // Função para atualizar contador e botão
    function updateSelection() {
        const checkedBoxes = document.querySelectorAll('.permission-checkbox:checked');
        const count = checkedBoxes.length;
        
        selectedCount.textContent = `${count} permissão(ões) selecionada(s)`;
        attachButton.disabled = count === 0;
        
        // Atualizar estado do "Selecionar todos"
        if (count === 0) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = false;
        } else if (count === permissionCheckboxes.length) {
            selectAllCheckbox.indeterminate = false;
            selectAllCheckbox.checked = true;
        } else {
            selectAllCheckbox.indeterminate = true;
        }
    }
    
    // Event listener para "Selecionar todos"
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            permissionCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            updateSelection();
        });
    }
    
    // Event listeners para checkboxes individuais
    permissionCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelection);
    });
    
    // Inicializar estado
    updateSelection();
});
</script>
@endpush

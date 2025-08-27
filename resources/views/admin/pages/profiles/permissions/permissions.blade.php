@extends('adminlte::page')

@section('title', "Permissões do perfil {$profile->name}")

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profiles.index') }}">Perfis</a></li>
                <li class="breadcrumb-item"><a href="{{ route('profiles.show', $profile->id) }}">{{ $profile->name }}</a></li>
                <li class="breadcrumb-item active">Permissões</li>
            </ol>
            <h1 class="m-0">
                <i class="fas fa-lock text-primary"></i>
                Permissões do Perfil
            </h1>
            <small class="text-muted">Gerenciando: <strong>{{ $profile->name }}</strong></small>
        </div>
        <div>
            <a href="{{ route('profiles.permissions.available', $profile->id) }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Adicionar Permissão
            </a>
            <a href="{{ route('profiles.show', $profile->id) }}" class="btn btn-secondary">
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
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list text-info"></i>
                        Permissões Vinculadas
                    </h5>
                </div>
                <div class="col-md-4 text-right">
                    <small class="text-muted">
                        Total: {{ $permissions->total() }} permissão(ões)
                    </small>
                </div>
            </div>
        </div>

        <div class="card-body p-0">
            @if($permissions->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="thead-light">
                            <tr>
                                <th class="border-0">Nome da Permissão</th>
                                <th class="border-0 text-center" width="150">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($permissions as $permission)
                                <tr>
                                    <td class="align-middle">
                                        <strong>{{ $permission->name }}</strong>
                                    </td>
                                    <td class="align-middle text-center">
                                        @can('detach_permission_profile')
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    onclick="confirmDetach('{{ $permission->id }}', '{{ $permission->name }}')"
                                                    title="Desvincular Permissão">
                                                <i class="fas fa-unlink"></i> Desvincular
                                            </button>
                                            
                                            <form id="detachForm_{{ $permission->id }}" 
                                                  action="{{ route('profiles.permission.detach', [$profile->id, $permission->id]) }}" 
                                                  method="GET" 
                                                  style="display: none;">
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-lock fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhuma permissão vinculada</h5>
                    <p class="text-muted">Este perfil ainda não possui permissões vinculadas.</p>
                    <a href="{{ route('profiles.permissions.available', $profile->id) }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> Adicionar Primeira Permissão
                    </a>
                </div>
            @endif
        </div>

        @if($permissions->count() > 0)
            <div class="card-footer bg-white">
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
function confirmDetach(permissionId, permissionName) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Tem certeza?',
            text: `Deseja realmente desvincular a permissão "${permissionName}" do perfil "{{ $profile->name }}"?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sim, desvincular!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('detachForm_' + permissionId).submit();
            }
        });
    } else {
        if (confirm(`Tem certeza que deseja desvincular a permissão "${permissionName}" do perfil "{{ $profile->name }}"?`)) {
            document.getElementById('detachForm_' + permissionId).submit();
        }
    }
}
</script>
@endpush

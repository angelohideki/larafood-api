@extends('adminlte::page')

@section('title', "Detalhes da Empresa {$tenant->name}")

@section('content')
<div class="container-fluid">
    <!-- Header com Breadcrumb -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-eye text-info me-2"></i>
                        Detalhes da Empresa
                    </h1>
                    <nav aria-label="breadcrumb" class="mt-2">
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('admin.index') }}" class="text-decoration-none">
                                    <i class="fas fa-home"></i> Dashboard
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ route('tenants.index') }}" class="text-decoration-none">
                                    <i class="fas fa-building"></i> Empresas
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <i class="fas fa-eye"></i> {{ $tenant->name }}
                            </li>
                        </ol>
                    </nav>
                </div>
                <div>
                    @can('edit_tenants')
                        <a href="{{ route('tenants.edit', $tenant->uuid) }}" class="btn btn-warning me-2">
                            <i class="fas fa-edit me-1"></i>
                            Editar
                        </a>
                    @endcan
                    <a href="{{ route('tenants.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Voltar
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Inclusão do sistema de alerts/toast -->
    @include('admin.includes.alerts')

    <!-- Cards de Informações -->
    <div class="row">
        <!-- Card Principal da Empresa -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-building me-2"></i>
                        Informações da Empresa
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 text-center mb-3">
                            @if($tenant->logo)
                                <img src="{{ url("storage/{$tenant->logo}") }}"
                                     alt="{{ $tenant->name }}"
                                     class="img-fluid rounded shadow-sm"
                                     style="max-width: 120px; max-height: 120px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center"
                                     style="width: 120px; height: 120px; margin: 0 auto;">
                                    <i class="fas fa-building fa-3x text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">
                                        <i class="fas fa-tag text-primary me-1"></i>
                                        Nome da Empresa
                                    </label>
                                    <p class="h5 text-dark">{{ $tenant->name }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">
                                        <i class="fas fa-link text-primary me-1"></i>
                                        URL/Domínio
                                    </label>
                                    <p class="h6 text-dark">
                                        @if($tenant->url)
                                            <a href="{{ $tenant->url }}" target="_blank" class="text-decoration-none">
                                                {{ $tenant->url }}
                                                <i class="fas fa-external-link-alt ms-1 text-muted"></i>
                                            </a>
                                        @else
                                            <span class="text-muted">Não informado</span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">
                                        <i class="fas fa-envelope text-primary me-1"></i>
                                        E-mail
                                    </label>
                                    <p class="h6 text-dark">
                                        <a href="mailto:{{ $tenant->email }}" class="text-decoration-none">
                                            {{ $tenant->email }}
                                        </a>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">
                                        <i class="fas fa-id-card text-primary me-1"></i>
                                        CNPJ
                                    </label>
                                    <p class="h6 text-dark">{{ $tenant->cnpj ?? 'Não informado' }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">
                                        <i class="fas fa-toggle-on text-primary me-1"></i>
                                        Status
                                    </label>
                                    <p class="h6">
                                        @if($tenant->active == 'Y')
                                            <span class="badge bg-success fs-6">
                                                <i class="fas fa-check-circle me-1"></i>
                                                Ativo
                                            </span>
                                        @else
                                            <span class="badge bg-danger fs-6">
                                                <i class="fas fa-times-circle me-1"></i>
                                                Inativo
                                            </span>
                                        @endif
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold text-muted">
                                        <i class="fas fa-calendar-plus text-primary me-1"></i>
                                        Plano Contratado
                                    </label>
                                    <p class="h6 text-dark">
                                        <span class="badge bg-info fs-6">
                                            <i class="fas fa-crown me-1"></i>
                                            {{ $tenant->plan->name }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card de Assinatura -->
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-credit-card me-2"></i>
                        Assinatura
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">
                            <i class="fas fa-calendar-plus text-success me-1"></i>
                            Data de Início
                        </label>
                        <p class="h6 text-dark">
                            {{ $tenant->subscription ? \Carbon\Carbon::parse($tenant->subscription)->format('d/m/Y') : 'Não informado' }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">
                            <i class="fas fa-calendar-times text-success me-1"></i>
                            Data de Expiração
                        </label>
                        <p class="h6 text-dark">
                            @if($tenant->expires_at)
                                @php
    $expiresAt = \Carbon\Carbon::parse($tenant->expires_at);
    $isExpired = $expiresAt->isPast();
    $daysUntilExpiry = $expiresAt->diffInDays(now(), false);
                                @endphp
                                <span class="badge {{ $isExpired ? 'bg-danger' : ($daysUntilExpiry > -30 ? 'bg-warning' : 'bg-success') }} fs-6">
                                    {{ $expiresAt->format('d/m/Y') }}
                                    @if($isExpired)
                                        (Expirado)
                                    @elseif($daysUntilExpiry > -30)
                                        ({{ abs($daysUntilExpiry) }} dias)
                                    @endif
                                </span>
                            @else
                                <span class="text-muted">Não informado</span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">
                            <i class="fas fa-key text-success me-1"></i>
                            Identificador
                        </label>
                        <p class="h6 text-dark">
                            {{ $tenant->subscription_id ?? 'Não informado' }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted">
                            <i class="fas fa-check-circle text-success me-1"></i>
                            Status da Assinatura
                        </label>
                        <p class="h6">
                            @if($tenant->subscription_active)
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Ativa
                                </span>
                            @else
                                <span class="badge bg-danger fs-6">
                                    <i class="fas fa-times-circle me-1"></i>
                                    Inativa
                                </span>
                            @endif
                        </p>
                    </div>

                    <div class="mb-0">
                        <label class="form-label fw-bold text-muted">
                            <i class="fas fa-ban text-success me-1"></i>
                            Cancelamento
                        </label>
                        <p class="h6">
                            @if($tenant->subscription_suspended)
                                <span class="badge bg-warning fs-6">
                                    <i class="fas fa-exclamation-triangle me-1"></i>
                                    Cancelada
                                </span>
                            @else
                                <span class="badge bg-success fs-6">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Normal
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Card de Ações -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cogs me-2"></i>
                        Ações Disponíveis
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2">
                        @can('edit_tenants')
                            <a href="{{ route('tenants.edit', $tenant->uuid) }}" class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i>
                                Editar Empresa
                            </a>
                        @endcan

                        @can('view_tenants')
                            <button type="button" class="btn btn-info" onclick="printTenantInfo()">
                                <i class="fas fa-print me-1"></i>
                                Imprimir Dados
                            </button>
                        @endcan

                        @can('delete_tenants')
                            <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                                <i class="fas fa-trash me-1"></i>
                                Excluir Empresa
                            </button>
                        @endcan

                        <a href="{{ route('tenants.index') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-list me-1"></i>
                            Listar Empresas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript para ações -->
<script>
    function printTenantInfo() {
        window.print();
    }

    function confirmDelete() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Tem certeza?',
                text: 'Esta ação não pode ser desfeita!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Criar formulário para DELETE
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("tenants.destroy", $tenant->uuid) }}';

                    const csrfToken = document.createElement('input');
                    csrfToken.type = 'hidden';
                    csrfToken.name = '_token';
                    csrfToken.value = '{{ csrf_token() }}';

                    const methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'DELETE';

                    form.appendChild(csrfToken);
                    form.appendChild(methodField);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        } else {
            if (confirm('Tem certeza que deseja excluir esta empresa? Esta ação não pode ser desfeita!')) {
                // Criar formulário para DELETE
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("tenants.destroy", $tenant->uuid) }}';

                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';

                const methodField = document.createElement('input');
                methodField.type = 'hidden';
                methodField.name = '_method';
                methodField.value = 'DELETE';

                form.appendChild(csrfToken);
                form.appendChild(methodField);
                document.body.appendChild(form);
                form.submit();
            }
        }
    }
</script>

<!-- Estilos para impressão -->
<style media="print">
    .btn, .card-header, nav, .no-print {
        display: none !important;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
    }

    body {
        background: white !important;
    }
</style>
@endsection

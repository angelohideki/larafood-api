@extends('adminlte::page')

@section('title', 'Editar Empresa')

@section('content')
<div class="container-fluid">
    <!-- Header com Breadcrumb -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">
                        <i class="fas fa-edit text-muted me-2"></i>
                        Editar Empresa
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
                            <li class="breadcrumb-item">
                                <a href="{{ route('tenants.show', $tenant->uuid) }}" class="text-decoration-none">
                                    <i class="fas fa-eye"></i> {{ $tenant->name }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                <i class="fas fa-edit"></i> Editar
                            </li>
                        </ol>
                    </nav>
                </div>
                <div>
                    <a href="{{ route('tenants.show', $tenant->uuid) }}" class="btn btn-outline-secondary me-2">
                        <i class="fas fa-eye me-1"></i>
                        Visualizar
                    </a>
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

    <!-- Card do Formulário -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-edit text-warning me-2"></i>
                        Editar Informações da Empresa
                    </h5>
                    <small class="text-muted">Empresa: <strong>{{ $tenant->name }}</strong></small>
                </div>
                <div class="card-body">
                    @include('admin.pages.tenants._partials.form')
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript para validação -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');

        if (form) {
            form.addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                }
            });
        }
    });

    function validateForm() {
        clearErrors();
        let isValid = true;

        // Validação do nome
        const name = document.querySelector('input[name="name"]');
        if (name && !name.value.trim()) {
            showFieldError(name, 'O nome da empresa é obrigatório.');
            isValid = false;
        }

        // Validação do email
        const email = document.querySelector('input[name="email"]');
        if (email && email.value.trim()) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value.trim())) {
                showFieldError(email, 'Por favor, insira um email válido.');
                isValid = false;
            }
        }

        // Validação do CNPJ
        const cnpj = document.querySelector('input[name="cnpj"]');
        if (cnpj && cnpj.value.trim()) {
            const cnpjRegex = /^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/;
            if (!cnpjRegex.test(cnpj.value.trim())) {
                showFieldError(cnpj, 'Por favor, insira um CNPJ válido (XX.XXX.XXX/XXXX-XX).');
                isValid = false;
            }
        }

        // Validação das datas
        const subscriptionDate = document.querySelector('input[name="subscription"]');
        const expiresDate = document.querySelector('input[name="expires_at"]');

        if (subscriptionDate && expiresDate && subscriptionDate.value && expiresDate.value) {
            const startDate = new Date(subscriptionDate.value);
            const endDate = new Date(expiresDate.value);

            if (endDate <= startDate) {
                showFieldError(expiresDate, 'A data de expiração deve ser posterior à data de início da assinatura.');
                isValid = false;
            }
        }

        if (!isValid) {
            showErrorMessage('Por favor, corrija os erros no formulário antes de continuar.');
        }

        return isValid;
    }

    function showFieldError(field, message) {
        field.classList.add('is-invalid');

        let errorDiv = field.parentNode.querySelector('.invalid-feedback');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            field.parentNode.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
    }

    function clearErrors() {
        document.querySelectorAll('.is-invalid').forEach(field => {
            field.classList.remove('is-invalid');
        });
        document.querySelectorAll('.invalid-feedback').forEach(error => {
            error.remove();
        });
    }

    function showErrorMessage(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Erro de Validação',
                text: message,
                confirmButtonColor: '#d33'
            });
        } else {
            alert('Erro: ' + message);
        }
    }
</script>
@endsection

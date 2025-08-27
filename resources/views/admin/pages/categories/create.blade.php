@extends('adminlte::page')

@section('title', 'Cadastrar Nova Categoria')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0">
                <i class="fas fa-plus-circle text-success"></i>
                Cadastrar Nova Categoria
            </h1>
            <small class="text-muted">Preencha as informações abaixo para adicionar uma nova categoria</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categorias</a></li>
                <li class="breadcrumb-item active">Nova Categoria</li>
            </ol>
        </nav>
    </div>
@stop

@section('content')
    <form action="{{ route('categories.store') }}"
        class="form"
        method="POST"
        id="categoryForm">
        @csrf
        @include('admin.pages.categories._partials.form')
    </form>
@endsection

@push('js')
<script>
// Verificar se SweetAlert2 está carregado
function checkSweetAlert() {
    return typeof Swal !== 'undefined';
}

// Função para mostrar erro em campo específico
function showFieldError(fieldId, message) {
    const field = document.getElementById(fieldId);
    const errorDiv = field.parentNode.querySelector('.invalid-feedback');

    field.classList.add('is-invalid');
    if (errorDiv) {
        errorDiv.textContent = message;
        errorDiv.style.display = 'block';
    }
}

// Função para limpar erros dos campos
function clearFieldErrors() {
    const invalidFields = document.querySelectorAll('.is-invalid');
    const errorDivs = document.querySelectorAll('.invalid-feedback');
    
    invalidFields.forEach(field => field.classList.remove('is-invalid'));
    errorDivs.forEach(div => div.style.display = 'none');
}

// Função de validação do formulário
function validateForm() {
    clearFieldErrors();
    let isValid = true;
    const errors = [];

    // Validar nome
    const name = document.getElementById('name');
    if (!name.value.trim()) {
        showFieldError('name', 'O nome da categoria é obrigatório.');
        errors.push('Nome da categoria é obrigatório');
        isValid = false;
    } else if (name.value.trim().length < 3) {
        showFieldError('name', 'O nome deve ter pelo menos 3 caracteres.');
        errors.push('Nome deve ter pelo menos 3 caracteres');
        isValid = false;
    }

    // Validar descrição
    const description = document.getElementById('description');
    if (!description.value.trim()) {
        showFieldError('description', 'A descrição da categoria é obrigatória.');
        errors.push('Descrição da categoria é obrigatória');
        isValid = false;
    } else if (description.value.trim().length < 10) {
        showFieldError('description', 'A descrição deve ter pelo menos 10 caracteres.');
        errors.push('Descrição deve ter pelo menos 10 caracteres');
        isValid = false;
    }

    // Se houver erros, exibir toast
    if (!isValid) {
        if (checkSweetAlert()) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Erro de validação',
                text: errors.join(', '),
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
            });
        } else {
            alert('Erro de validação: ' + errors.join(', '));
        }
    }

    return isValid;
}

// Event listeners
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('categoryForm');
    const nameField = document.getElementById('name');
    const descriptionField = document.getElementById('description');

    // Validação no envio do formulário
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                return false;
            }
        });
    }

    // Limpar erros quando o usuário digita
    if (nameField) {
        nameField.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                this.classList.remove('is-invalid');
                const errorDiv = this.parentNode.querySelector('.invalid-feedback');
                if (errorDiv) errorDiv.style.display = 'none';
            }
        });
    }

    if (descriptionField) {
        descriptionField.addEventListener('input', function() {
            if (this.classList.contains('is-invalid')) {
                this.classList.remove('is-invalid');
                const errorDiv = this.parentNode.querySelector('.invalid-feedback');
                if (errorDiv) errorDiv.style.display = 'none';
            }
        });
    }
});
</script>
@endpush

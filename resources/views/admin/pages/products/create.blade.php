@extends('adminlte::page')

@section('title', 'Cadastrar Novo Produto')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="m-0">
                <i class="fas fa-plus-circle text-success"></i>
                Cadastrar Novo Produto
            </h1>
            <small class="text-muted">Preencha as informações abaixo para adicionar um novo produto</small>
        </div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('admin.index') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Produtos</a></li>
                <li class="breadcrumb-item active">Novo Produto</li>
            </ol>
        </nav>
    </div>
@stop

@section('content')
    <form action="{{ route('products.store') }}"
        class="form"
        method="POST"
        enctype="multipart/form-data"
        id="productForm">
        @csrf
        @include('admin.pages.products._partials.form')
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

// Função para limpar erros
function clearFieldErrors() {
    document.querySelectorAll('.is-invalid').forEach(field => {
        field.classList.remove('is-invalid');
    });
    document.querySelectorAll('.invalid-feedback').forEach(error => {
        error.style.display = 'none';
    });
}

// Validação completa do formulário
function validateForm() {
    clearFieldErrors();
    let hasError = false;
    let errors = [];

    // Validar título
    const title = document.getElementById('title').value.trim();
    if (!title) {
        showFieldError('title', 'O título é obrigatório');
        errors.push('O título é obrigatório');
        hasError = true;
    } else if (title.length < 3) {
        showFieldError('title', 'O título deve ter pelo menos 3 caracteres');
        errors.push('O título deve ter pelo menos 3 caracteres');
        hasError = true;
    }

    // Validar preço
    const price = document.getElementById('price').value;
    if (!price) {
        showFieldError('price', 'O preço é obrigatório');
        errors.push('O preço é obrigatório');
        hasError = true;
    } else {
        // Converter preço formatado para número
        const numericPrice = parseFloat(price.replace(/\./g, '').replace(',', '.'));
        if (isNaN(numericPrice) || numericPrice <= 0) {
            showFieldError('price', 'O preço deve ser maior que zero');
            errors.push('O preço deve ser maior que zero');
            hasError = true;
        }
    }

    // Validar descrição
    const description = document.getElementById('description').value.trim();
    if (!description) {
        showFieldError('description', 'A descrição é obrigatória');
        errors.push('A descrição é obrigatória');
        hasError = true;
    } else if (description.length < 3) {
        showFieldError('description', 'A descrição deve ter pelo menos 3 caracteres');
        errors.push('A descrição deve ter pelo menos 3 caracteres');
        hasError = true;
    }

    // Validar imagem (apenas para criação)
    const imageInput = document.getElementById('image');
    const hasExistingImage = document.querySelector('#imagePreview img[src*="storage"]');
    const isCreating = !hasExistingImage;

    if (isCreating && (!imageInput.files || imageInput.files.length === 0)) {
        const imageButton = document.getElementById('imageButton');
        imageButton.classList.add('btn-outline-danger');
        imageButton.classList.remove('btn-outline-primary');
        errors.push('Por favor, selecione uma imagem para o produto');
        hasError = true;
    }

    // Mostrar erros
    if (hasError) {
        let errorList = errors.map(error => `• ${error}`).join('<br>');

        // Verificar se SweetAlert2 está disponível
        if (checkSweetAlert()) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 6000,
                timerProgressBar: true,
                icon: 'error',
                title: 'Erro de Validação!',
                html: errorList,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });
        } else {
            // Fallback para alert nativo se SweetAlert2 não estiver carregado
            alert('Erro de Validação:\n\n' + errors.join('\n'));
        }

        // Focar no primeiro campo com erro
        const firstErrorField = document.querySelector('.is-invalid');
        if (firstErrorField) {
            firstErrorField.focus();
        }

        return false;
    }

    return true;
}

// Adicionar evento ao formulário
document.addEventListener('DOMContentLoaded', function() {
    // Aguardar um pouco para garantir que todos os scripts foram carregados
    setTimeout(function() {
        const form = document.querySelector('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                if (!validateForm()) {
                    e.preventDefault();
                    return false;
                }
            });
        }

        // Limpar erros quando o usuário começar a digitar
        ['title', 'price', 'description'].forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (field) {
                field.addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                    const errorDiv = this.parentNode.querySelector('.invalid-feedback');
                    if (errorDiv) {
                        errorDiv.style.display = 'none';
                    }
                });
            }
        });

        // Limpar erro da imagem quando uma nova for selecionada
        const imageInput = document.getElementById('image');
        if (imageInput) {
            imageInput.addEventListener('change', function() {
                const imageButton = document.getElementById('imageButton');
                if (this.files && this.files.length > 0) {
                    imageButton.classList.remove('btn-outline-danger');
                    imageButton.classList.add('btn-outline-primary');
                }
            });
        }
    }, 100); // Aguarda 100ms
});

// Teste para verificar se SweetAlert2 está funcionando
console.log('SweetAlert2 disponível:', checkSweetAlert());
if (checkSweetAlert()) {
    console.log('✅ SweetAlert2 carregado com sucesso!');
} else {
    console.error('❌ SweetAlert2 não foi carregado!');
}
</script>
@endpush

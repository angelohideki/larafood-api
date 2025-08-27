@include('admin.includes.alerts')

<form action="{{ isset($tenant) ? route('tenants.update', $tenant->uuid) : route('tenants.store') }}" method="POST" enctype="multipart/form-data" id="tenantForm">
    @csrf
    @if(isset($tenant))
        @method('PUT')
    @endif

    <!-- Informações Básicas -->
    <div class="row">
        <div class="col-12">
            <h5 class="text-primary mb-3">
                <i class="fas fa-building me-2"></i>
                Informações da Empresa
            </h5>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label fw-bold">
                    <i class="fas fa-tag text-primary me-1"></i>
                    Nome <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       class="form-control form-control-lg" 
                       placeholder="Digite o nome da empresa" 
                       value="{{ $tenant->name ?? old('name') }}"
                       required>
                <div class="invalid-feedback"></div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label fw-bold">
                    <i class="fas fa-envelope text-primary me-1"></i>
                    E-mail <span class="text-danger">*</span>
                </label>
                <input type="email" 
                       name="email" 
                       class="form-control form-control-lg" 
                       placeholder="Digite o e-mail da empresa" 
                       value="{{ $tenant->email ?? old('email') }}"
                       required>
                <div class="invalid-feedback"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label fw-bold">
                    <i class="fas fa-id-card text-primary me-1"></i>
                    CNPJ <span class="text-danger">*</span>
                </label>
                <input type="text" 
                       name="cnpj" 
                       class="form-control form-control-lg" 
                       placeholder="XX.XXX.XXX/XXXX-XX" 
                       value="{{ $tenant->cnpj ?? old('cnpj') }}"
                       maxlength="18"
                       required>
                <div class="invalid-feedback"></div>
                <small class="form-text text-muted">Formato: XX.XXX.XXX/XXXX-XX</small>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label fw-bold">
                    <i class="fas fa-image text-primary me-1"></i>
                    Logo da Empresa
                </label>
                <input type="file" 
                       name="logo" 
                       class="form-control form-control-lg" 
                       accept="image/*">
                <div class="invalid-feedback"></div>
                <small class="form-text text-muted">Formatos aceitos: JPG, PNG, GIF (máx. 2MB)</small>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-4">
                <label class="form-label fw-bold">
                    <i class="fas fa-toggle-on text-primary me-1"></i>
                    Status da Empresa <span class="text-danger">*</span>
                </label>
                <select name="active" class="form-select form-select-lg" required>
                    <option value="">Selecione o status</option>
                    <option value="Y" @if(isset($tenant) && $tenant->active == 'Y') selected @endif>
                        <i class="fas fa-check-circle"></i> Ativo
                    </option>
                    <option value="N" @if(isset($tenant) && $tenant->active == 'N') selected @endif>
                        <i class="fas fa-times-circle"></i> Inativo
                    </option>
                </select>
                <div class="invalid-feedback"></div>
            </div>
        </div>
    </div>

    <!-- Separador -->
    <hr class="my-4">

    <!-- Informações da Assinatura -->
    <div class="row">
        <div class="col-12">
            <h5 class="text-success mb-3">
                <i class="fas fa-credit-card me-2"></i>
                Informações da Assinatura
            </h5>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label fw-bold">
                    <i class="fas fa-calendar-plus text-success me-1"></i>
                    Data de Início da Assinatura
                </label>
                <input type="date" 
                       name="subscription" 
                       class="form-control form-control-lg" 
                       value="{{ $tenant->subscription ?? old('subscription') }}">
                <div class="invalid-feedback"></div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="form-group mb-3">
                <label class="form-label fw-bold">
                    <i class="fas fa-calendar-times text-success me-1"></i>
                    Data de Expiração
                </label>
                <input type="date" 
                       name="expires_at" 
                       class="form-control form-control-lg" 
                       value="{{ $tenant->expires_at ?? old('expires_at') }}">
                <div class="invalid-feedback"></div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label class="form-label fw-bold">
                    <i class="fas fa-key text-success me-1"></i>
                    Identificador da Assinatura
                </label>
                <input type="text" 
                       name="subscription_id" 
                       class="form-control form-control-lg" 
                       placeholder="ID da assinatura" 
                       value="{{ $tenant->subscription_id ?? old('subscription_id') }}">
                <div class="invalid-feedback"></div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label class="form-label fw-bold">
                    <i class="fas fa-check-circle text-success me-1"></i>
                    Assinatura Ativa? <span class="text-danger">*</span>
                </label>
                <select name="subscription_active" class="form-select form-select-lg" required>
                    <option value="">Selecione</option>
                    <option value="1" @if(isset($tenant) && $tenant->subscription_active) selected @endif>
                        Sim - Ativa
                    </option>
                    <option value="0" @if(isset($tenant) && !$tenant->subscription_active) selected @endif>
                        Não - Inativa
                    </option>
                </select>
                <div class="invalid-feedback"></div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="form-group mb-3">
                <label class="form-label fw-bold">
                    <i class="fas fa-ban text-success me-1"></i>
                    Assinatura Cancelada? <span class="text-danger">*</span>
                </label>
                <select name="subscription_suspended" class="form-select form-select-lg" required>
                    <option value="">Selecione</option>
                    <option value="0" @if(isset($tenant) && !$tenant->subscription_suspended) selected @endif>
                        Não - Normal
                    </option>
                    <option value="1" @if(isset($tenant) && $tenant->subscription_suspended) selected @endif>
                        Sim - Cancelada
                    </option>
                </select>
                <div class="invalid-feedback"></div>
            </div>
        </div>
    </div>

    <!-- Botões de Ação -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-end gap-2">
                <a href="{{ route('tenants.index') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="fas fa-times me-1"></i>
                    Cancelar
                </a>
                <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                    <i class="fas fa-save me-1"></i>
                    {{ isset($tenant) ? 'Atualizar Empresa' : 'Cadastrar Empresa' }}
                </button>
            </div>
        </div>
    </div>
</form>

<!-- JavaScript para validação e máscaras -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('tenantForm');
        const cnpjInput = document.querySelector('input[name="cnpj"]');
        const submitBtn = document.getElementById('submitBtn');
        
        // Máscara para CNPJ
        if (cnpjInput) {
            cnpjInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                
                if (value.length <= 14) {
                    value = value.replace(/(\d{2})(\d)/, '$1.$2');
                    value = value.replace(/(\d{3})(\d)/, '$1.$2');
                    value = value.replace(/(\d{3})(\d)/, '$1/$2');
                    value = value.replace(/(\d{4})(\d)/, '$1-$2');
                }
                
                e.target.value = value;
            });
        }
        
        // Validação do formulário
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (validateTenantForm()) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Processando...';
                    
                    // Simular delay para mostrar loading
                    setTimeout(() => {
                        form.submit();
                    }, 500);
                }
            });
        }
    });

    function validateTenantForm() {
        clearFormErrors();
        let isValid = true;
        
        // Validação do nome
        const name = document.querySelector('input[name="name"]');
        if (!name.value.trim()) {
            showFieldError(name, 'O nome da empresa é obrigatório.');
            isValid = false;
        } else if (name.value.trim().length < 2) {
            showFieldError(name, 'O nome deve ter pelo menos 2 caracteres.');
            isValid = false;
        }
        
        // Validação do email
        const email = document.querySelector('input[name="email"]');
        if (!email.value.trim()) {
            showFieldError(email, 'O e-mail é obrigatório.');
            isValid = false;
        } else {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value.trim())) {
                showFieldError(email, 'Por favor, insira um e-mail válido.');
                isValid = false;
            }
        }
        
        // Validação do CNPJ
        const cnpj = document.querySelector('input[name="cnpj"]');
        if (!cnpj.value.trim()) {
            showFieldError(cnpj, 'O CNPJ é obrigatório.');
            isValid = false;
        } else {
            const cnpjRegex = /^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/;
            if (!cnpjRegex.test(cnpj.value.trim())) {
                showFieldError(cnpj, 'Por favor, insira um CNPJ válido (XX.XXX.XXX/XXXX-XX).');
                isValid = false;
            }
        }
        
        // Validação do status
        const active = document.querySelector('select[name="active"]');
        if (!active.value) {
            showFieldError(active, 'Selecione o status da empresa.');
            isValid = false;
        }
        
        // Validação da assinatura ativa
        const subscriptionActive = document.querySelector('select[name="subscription_active"]');
        if (!subscriptionActive.value && subscriptionActive.value !== '0') {
            showFieldError(subscriptionActive, 'Selecione se a assinatura está ativa.');
            isValid = false;
        }
        
        // Validação da assinatura cancelada
        const subscriptionSuspended = document.querySelector('select[name="subscription_suspended"]');
        if (!subscriptionSuspended.value && subscriptionSuspended.value !== '0') {
            showFieldError(subscriptionSuspended, 'Selecione se a assinatura está cancelada.');
            isValid = false;
        }
        
        // Validação das datas
        const subscriptionDate = document.querySelector('input[name="subscription"]');
        const expiresDate = document.querySelector('input[name="expires_at"]');
        
        if (subscriptionDate.value && expiresDate.value) {
            const startDate = new Date(subscriptionDate.value);
            const endDate = new Date(expiresDate.value);
            
            if (endDate <= startDate) {
                showFieldError(expiresDate, 'A data de expiração deve ser posterior à data de início.');
                isValid = false;
            }
        }
        
        // Validação do arquivo de logo
        const logo = document.querySelector('input[name="logo"]');
        if (logo.files.length > 0) {
            const file = logo.files[0];
            const maxSize = 2 * 1024 * 1024; // 2MB
            const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            
            if (file.size > maxSize) {
                showFieldError(logo, 'O arquivo deve ter no máximo 2MB.');
                isValid = false;
            }
            
            if (!allowedTypes.includes(file.type)) {
                showFieldError(logo, 'Apenas arquivos JPG, PNG e GIF são permitidos.');
                isValid = false;
            }
        }
        
        if (!isValid) {
            showToastError('Por favor, corrija os erros no formulário antes de continuar.');
            
            // Scroll para o primeiro erro
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }
        
        return isValid;
    }

    function showFieldError(field, message) {
        field.classList.add('is-invalid');
        
        let errorDiv = field.parentNode.querySelector('.invalid-feedback');
        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        }
    }

    function clearFormErrors() {
        document.querySelectorAll('.is-invalid').forEach(field => {
            field.classList.remove('is-invalid');
        });
        document.querySelectorAll('.invalid-feedback').forEach(error => {
            error.style.display = 'none';
        });
    }

    function showToastError(message) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Erro de Validação',
                text: message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true
            });
        } else if (typeof toastr !== 'undefined') {
            toastr.error(message, 'Erro de Validação');
        } else {
            alert('Erro: ' + message);
        }
    }
</script>

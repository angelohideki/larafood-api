@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@php( $login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login') )
@php( $register_url = View::getSection('register_url') ?? config('adminlte.register_url', 'register') )
@php( $password_reset_url = View::getSection('password_reset_url') ?? config('adminlte.password_reset_url', 'password/reset') )

@if (config('adminlte.use_route_url', false))
    @php( $login_url = $login_url ? route($login_url) : '' )
    @php( $register_url = $register_url ? route($register_url) : '' )
    @php( $password_reset_url = $password_reset_url ? route($password_reset_url) : '' )
@else
    @php( $login_url = $login_url ? url($login_url) : '' )
    @php( $register_url = $register_url ? url($register_url) : '' )
    @php( $password_reset_url = $password_reset_url ? url($password_reset_url) : '' )
@endif

@section('auth_header', __('Entrar no Sistema'))

@section('auth_body')
    <!-- Enhanced Login Form -->
    <form action="{{ $login_url }}" method="post" id="loginForm">
        @csrf
        
        {{-- Email Address Field --}}
        <div class="input-group mb-3">
            <input type="email" 
                   name="email" 
                   id="email"
                   class="form-control @error('email') is-invalid @enderror" 
                   value="{{ old('email') }}"
                   placeholder="{{ __('E-mail') }}"
                   autocomplete="email"
                   autofocus
                   required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-envelope {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
            @error('email')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Password Field --}}
        <div class="input-group mb-3">
            <input type="password" 
                   name="password" 
                   id="password"
                   class="form-control @error('password') is-invalid @enderror" 
                   placeholder="{{ __('Senha') }}"
                   autocomplete="current-password"
                   required>
            <div class="input-group-append">
                <div class="input-group-text">
                    <span class="fas fa-lock {{ config('adminlte.classes_auth_icon', '') }}"></span>
                </div>
            </div>
            @error('password')
                <span class="invalid-feedback d-block" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        {{-- Remember Me and Forgot Password Row --}}
        <div class="row mb-3">
            <div class="col-6">
                <div class="icheck-primary">
                    <input type="checkbox" 
                           name="remember" 
                           id="remember" 
                           {{ old('remember') ? 'checked' : '' }}>
                    <label for="remember">
                        {{ __('Lembrar-me') }}
                    </label>
                </div>
            </div>
            <div class="col-6 text-right">
                @if($password_reset_url)
                    <a href="{{ $password_reset_url }}" class="text-sm text-decoration-none">
                        {{ __('Esqueci minha senha') }}
                    </a>
                @endif
            </div>
        </div>

        {{-- Submit Button --}}
        <button type="submit" 
                class="btn btn-primary btn-block {{ config('adminlte.classes_auth_btn', 'btn-flat') }}"
                id="loginButton">
            <span class="btn-text">
                <i class="fas fa-sign-in-alt mr-2"></i>
                {{ __('Entrar') }}
            </span>
            <span class="btn-loading d-none">
                <i class="fas fa-spinner fa-spin mr-2"></i>
                {{ __('Entrando...') }}
            </span>
        </button>

    </form>
@stop

@section('auth_footer')
    {{-- Registration Link --}}
    @if($register_url)
        <div class="text-center mt-3">
            <p class="mb-0">
                {{ __('Não tem uma conta?') }}
                <a href="{{ $register_url }}" class="text-primary font-weight-bold text-decoration-none">
                    {{ __('Registre-se aqui') }}
                </a>
            </p>
        </div>
    @endif
    
    {{-- Footer Info --}}
    <div class="text-center mt-4">
        <p class="text-muted small mb-0">
            <i class="fas fa-shield-alt text-success mr-1"></i>
            {{ __('Sistema Seguro LaraFood') }}
        </p>
        <p class="text-muted small mb-0">
            &copy; {{ date('Y') }} LaraFood. {{ __('Todos os direitos reservados.') }}
        </p>
    </div>
@stop

@section('adminlte_css')
<style>
/* Custom Login Page Styles */
.login-page {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    min-height: 100vh;
}

.login-box {
    margin: 5% auto;
}

.card {
    border-radius: 15px;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.card-header {
    background: rgba(255, 255, 255, 0.1);
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: 15px 15px 0 0 !important;
}

.card-header h1 {
    color: #333;
    font-weight: 600;
    text-align: center;
    margin: 0;
    font-size: 1.8rem;
}

.card-body {
    padding: 2rem;
    background: rgba(255, 255, 255, 0.95);
}

.input-group {
    margin-bottom: 1.5rem;
}

.form-control {
    border-radius: 10px 0 0 10px;
    border: 2px solid #e9ecef;
    padding: 12px 15px;
    font-size: 16px;
    transition: all 0.3s ease;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.input-group-text {
    border-radius: 0 10px 10px 0;
    border: 2px solid #e9ecef;
    border-left: none;
    background: linear-gradient(135deg, #667eea, #764ba2);
    color: white;
    width: 45px;
    justify-content: center;
}

.form-control:focus + .input-group-append .input-group-text {
    border-color: #667eea;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    border-radius: 10px;
    padding: 12px;
    font-size: 16px;
    font-weight: 600;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

.btn-primary:active {
    transform: translateY(0);
}

.icheck-primary > input:checked + label::before {
    background-color: #667eea;
    border-color: #667eea;
}

.invalid-feedback {
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.text-primary {
    color: #667eea !important;
}

.text-primary:hover {
    color: #764ba2 !important;
    text-decoration: none !important;
}

/* Loading Animation */
.btn-loading .fa-spinner {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .login-box {
        margin: 2% auto;
        width: 95%;
    }
    
    .card-body {
        padding: 1.5rem;
    }
}

/* Error state styles */
.form-control.is-invalid {
    border-color: #dc3545;
}

.form-control.is-invalid:focus {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.form-control.is-invalid + .input-group-append .input-group-text {
    border-color: #dc3545;
    background: #dc3545;
}
</style>
@stop

@section('adminlte_js')
<script>
// Enhanced Login Form JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const loginButton = document.getElementById('loginButton');
    const btnText = loginButton.querySelector('.btn-text');
    const btnLoading = loginButton.querySelector('.btn-loading');
    const emailField = document.getElementById('email');
    const passwordField = document.getElementById('password');
    
    // Form validation
    function validateForm() {
        let isValid = true;
        
        // Clear previous error states
        [emailField, passwordField].forEach(field => {
            field.classList.remove('is-invalid');
            const feedback = field.parentNode.parentNode.querySelector('.invalid-feedback');
            if (feedback && !feedback.querySelector('strong')) {
                feedback.style.display = 'none';
            }
        });
        
        // Validate email
        if (!emailField.value.trim()) {
            showFieldError(emailField, 'O campo e-mail é obrigatório.');
            isValid = false;
        } else if (!isValidEmail(emailField.value)) {
            showFieldError(emailField, 'Por favor, insira um e-mail válido.');
            isValid = false;
        }
        
        // Validate password
        if (!passwordField.value.trim()) {
            showFieldError(passwordField, 'O campo senha é obrigatório.');
            isValid = false;
        } else if (passwordField.value.length < 6) {
            showFieldError(passwordField, 'A senha deve ter pelo menos 6 caracteres.');
            isValid = false;
        }
        
        return isValid;
    }
    
    function showFieldError(field, message) {
        field.classList.add('is-invalid');
        let feedback = field.parentNode.parentNode.querySelector('.invalid-feedback');
        if (!feedback) {
            feedback = document.createElement('span');
            feedback.className = 'invalid-feedback d-block';
            feedback.setAttribute('role', 'alert');
            field.parentNode.parentNode.appendChild(feedback);
        }
        if (!feedback.querySelector('strong')) {
            feedback.innerHTML = `<strong>${message}</strong>`;
        }
        feedback.style.display = 'block';
    }
    
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    // Form submission
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                
                // Show error toast if SweetAlert2 is available
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Erro de validação',
                        text: 'Por favor, corrija os erros no formulário.',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                }
                
                return false;
            }
            
            // Show loading state
            loginButton.disabled = true;
            btnText.classList.add('d-none');
            btnLoading.classList.remove('d-none');
        });
    }
    
    // Real-time validation
    [emailField, passwordField].forEach(field => {
        if (field) {
            field.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    this.classList.remove('is-invalid');
                    const feedback = this.parentNode.parentNode.querySelector('.invalid-feedback');
                    if (feedback && !feedback.querySelector('strong')) {
                        feedback.style.display = 'none';
                    }
                }
            });
        }
    });
    
    // Focus management
    if (emailField && !emailField.value) {
        emailField.focus();
    } else if (passwordField && !passwordField.value) {
        passwordField.focus();
    }
});
</script>
@stop
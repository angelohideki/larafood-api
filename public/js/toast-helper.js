// Toast Helper Functions
const Toast = {
    // Configuração padrão do toast
    config: {
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    },

    // Toast de sucesso
    success: function(message, title = 'Sucesso!') {
        Swal.fire({
            ...this.config,
            icon: 'success',
            title: title,
            text: message
        });
    },

    // Toast de erro
    error: function(message, title = 'Erro!') {
        Swal.fire({
            ...this.config,
            icon: 'error',
            title: title,
            text: message,
            timer: 6000 // Erros ficam mais tempo
        });
    },

    // Toast de aviso
    warning: function(message, title = 'Atenção!') {
        Swal.fire({
            ...this.config,
            icon: 'warning',
            title: title,
            text: message
        });
    },

    // Toast de informação
    info: function(message, title = 'Informação') {
        Swal.fire({
            ...this.config,
            icon: 'info',
            title: title,
            text: message
        });
    },

    // Toast customizado
    custom: function(options) {
        Swal.fire({
            ...this.config,
            ...options
        });
    },

    // Função para exibir múltiplos erros de validação
    validationErrors: function(errors) {
        let errorList = '';
        if (Array.isArray(errors)) {
            errorList = errors.map(error => `• ${error}`).join('<br>');
        } else if (typeof errors === 'object') {
            errorList = Object.values(errors).flat().map(error => `• ${error}`).join('<br>');
        } else {
            errorList = errors;
        }

        Swal.fire({
            ...this.config,
            icon: 'error',
            title: 'Erro!',
            html: errorList,
            timer: 8000
        });
    }
};

// Função para processar mensagens do Laravel (sessão)
function processLaravelMessages() {
    // Verifica se há mensagens na sessão (passadas pelo Laravel)
    if (typeof laravelMessages !== 'undefined') {
        // Cria chaves baseadas no conteúdo das mensagens para evitar duplicatas
        if (laravelMessages.success) {
            const successKey = 'toast_success_' + btoa(laravelMessages.success).replace(/[^a-zA-Z0-9]/g, '');
            if (!sessionStorage.getItem(successKey)) {
                Toast.success(laravelMessages.success);
                sessionStorage.setItem(successKey, Date.now());
            }
        }
        if (laravelMessages.error) {
            const errorKey = 'toast_error_' + btoa(laravelMessages.error).replace(/[^a-zA-Z0-9]/g, '');
            if (!sessionStorage.getItem(errorKey)) {
                Toast.error(laravelMessages.error);
                sessionStorage.setItem(errorKey, Date.now());
            }
        }
        if (laravelMessages.warning || laravelMessages.info) {
            const message = laravelMessages.warning || laravelMessages.info;
            const warningKey = 'toast_warning_' + btoa(message).replace(/[^a-zA-Z0-9]/g, '');
            if (!sessionStorage.getItem(warningKey)) {
                Toast.warning(message);
                sessionStorage.setItem(warningKey, Date.now());
            }
        }
        if (laravelMessages.errors) {
            const errorsKey = 'toast_errors_' + btoa(JSON.stringify(laravelMessages.errors)).replace(/[^a-zA-Z0-9]/g, '');
            if (!sessionStorage.getItem(errorsKey)) {
                Toast.validationErrors(laravelMessages.errors);
                sessionStorage.setItem(errorsKey, Date.now());
            }
        }
        
        // Limpa mensagens antigas do sessionStorage (mais de 1 hora)
        cleanOldMessages();
    }
}

// Função para limpar mensagens antigas do sessionStorage
function cleanOldMessages() {
    const oneHourAgo = Date.now() - (60 * 60 * 1000);
    
    for (let i = sessionStorage.length - 1; i >= 0; i--) {
        const key = sessionStorage.key(i);
        if (key && key.startsWith('toast_')) {
            const timestamp = parseInt(sessionStorage.getItem(key));
            if (timestamp && timestamp < oneHourAgo) {
                sessionStorage.removeItem(key);
            }
        }
    }
}

// Executa quando o documento estiver pronto
document.addEventListener('DOMContentLoaded', function() {
    processLaravelMessages();
});

// Torna o Toast disponível globalmente
window.Toast = Toast;

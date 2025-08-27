/**
 * Enhanced Toast Helper for LaraFood Admin
 * Provides beautiful, non-intrusive notifications
 */

class ToastHelper {
    constructor() {
        this.init();
    }

    init() {
        // Create toast container if it doesn't exist
        if (!document.querySelector('.toast-container')) {
            const container = document.createElement('div');
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            container.style.zIndex = '9999';
            document.body.appendChild(container);
        }

        // Process Laravel messages on page load
        this.processLaravelMessages();
    }

    processLaravelMessages() {
        if (typeof window.laravelMessages !== 'undefined') {
            const messages = window.laravelMessages;
            
            if (messages.success) {
                this.success(messages.success);
            }
            
            if (messages.error) {
                this.error(messages.error);
            }
            
            if (messages.info) {
                this.info(messages.info);
            }
            
            if (messages.errors && Array.isArray(messages.errors)) {
                messages.errors.forEach(error => {
                    this.error(error);
                });
            }
        }
    }

    show(message, type = 'info', duration = 5000) {
        const container = document.querySelector('.toast-container');
        const toastId = `toast-${Date.now()}-${Math.random().toString(36).substr(2, 9)}`;
        
        const icons = {
            success: 'fas fa-check-circle',
            error: 'fas fa-exclamation-circle',
            warning: 'fas fa-exclamation-triangle',
            info: 'fas fa-info-circle'
        };

        const colors = {
            success: 'text-success',
            error: 'text-danger',
            warning: 'text-warning',
            info: 'text-info'
        };

        const toast = document.createElement('div');
        toast.id = toastId;
        toast.className = 'toast fade show';
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
            <div class="toast-header bg-dark text-white border-0">
                <i class="${icons[type]} ${colors[type]} me-2"></i>
                <strong class="me-auto">${this.getTypeLabel(type)}</strong>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
            <div class="toast-body bg-dark text-white">
                ${message}
            </div>
        `;

        container.appendChild(toast);

        // Auto remove after duration
        setTimeout(() => {
            this.remove(toastId);
        }, duration);

        // Add click to remove functionality
        toast.querySelector('.btn-close').addEventListener('click', () => {
            this.remove(toastId);
        });

        return toastId;
    }

    success(message, duration = 5000) {
        return this.show(message, 'success', duration);
    }

    error(message, duration = 7000) {
        return this.show(message, 'error', duration);
    }

    warning(message, duration = 6000) {
        return this.show(message, 'warning', duration);
    }

    info(message, duration = 5000) {
        return this.show(message, 'info', duration);
    }

    remove(toastId) {
        const toast = document.getElementById(toastId);
        if (toast) {
            toast.classList.remove('show');
            toast.classList.add('hide');
            setTimeout(() => {
                if (toast.parentNode) {
                    toast.parentNode.removeChild(toast);
                }
            }, 300);
        }
    }

    getTypeLabel(type) {
        const labels = {
            success: 'Sucesso',
            error: 'Erro',
            warning: 'Atenção',
            info: 'Informação'
        };
        return labels[type] || 'Notificação';
    }

    // Form validation helpers
    showValidationErrors(errors) {
        if (typeof errors === 'object') {
            Object.keys(errors).forEach(field => {
                if (Array.isArray(errors[field])) {
                    errors[field].forEach(error => {
                        this.error(error);
                    });
                } else {
                    this.error(errors[field]);
                }
            });
        }
    }

    // Loading state helpers
    setButtonLoading(buttonSelector, loadingText = 'Carregando...') {
        const button = document.querySelector(buttonSelector);
        if (button) {
            button.disabled = true;
            button.innerHTML = `<i class="fas fa-spinner fa-spin me-2"></i>${loadingText}`;
        }
    }

    resetButton(buttonSelector, originalText, originalIcon = '') {
        const button = document.querySelector(buttonSelector);
        if (button) {
            button.disabled = false;
            button.innerHTML = originalIcon ? `<i class="${originalIcon} me-2"></i>${originalText}` : originalText;
        }
    }
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    window.toastHelper = new ToastHelper();
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = ToastHelper;
}
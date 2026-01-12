{{-- Sistema de Notificações Toast --}}
<div id="toast-container" class="fixed top-4 right-4 z-50 space-y-3" style="max-width: 400px;">
    {{-- Os toasts serão inseridos aqui via JavaScript --}}
</div>

<style>
.toast {
    display: flex;
    align-items: center;
    padding: 1rem 1.25rem;
    border-radius: 0.5rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
    animation: slideIn 0.3s ease-out;
    backdrop-filter: blur(10px);
    min-width: 300px;
}

.toast-success {
    background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    border-left: 4px solid #047857;
}

.toast-error {
    background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
    border-left: 4px solid #b91c1c;
}

.toast-warning {
    background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    border-left: 4px solid #b45309;
}

.toast-info {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
    border-left: 4px solid #1d4ed8;
}

.toast-icon {
    flex-shrink: 0;
    width: 24px;
    height: 24px;
    margin-right: 0.75rem;
}

.toast-content {
    flex: 1;
    color: white;
}

.toast-title {
    font-weight: 600;
    font-size: 0.95rem;
    margin-bottom: 0.25rem;
}

.toast-message {
    font-size: 0.875rem;
    opacity: 0.95;
    line-height: 1.4;
}

.toast-close {
    flex-shrink: 0;
    width: 20px;
    height: 20px;
    margin-left: 0.75rem;
    cursor: pointer;
    opacity: 0.8;
    transition: opacity 0.2s;
}

.toast-close:hover {
    opacity: 1;
}

.toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 3px;
    background: rgba(255, 255, 255, 0.3);
    animation: progress 5s linear;
}

@keyframes slideIn {
    from {
        transform: translateX(400px);
        opacity: 0;
    }
    to {
        transform: translateX(0);
        opacity: 1;
    }
}

@keyframes slideOut {
    from {
        transform: translateX(0);
        opacity: 1;
    }
    to {
        transform: translateX(400px);
        opacity: 0;
    }
}

@keyframes progress {
    from { width: 100%; }
    to { width: 0%; }
}

.toast.removing {
    animation: slideOut 0.3s ease-out forwards;
}
</style>

<script>
// Sistema de Toast
const ToastManager = {
    container: null,
    
    init() {
        this.container = document.getElementById('toast-container');
    },
    
    show(type, title, message, duration = 5000) {
        if (!this.container) this.init();
        
        const toast = this.createToast(type, title, message);
        this.container.appendChild(toast);
        
        // Auto-remove após duração
        if (duration > 0) {
            setTimeout(() => this.remove(toast), duration);
        }
        
        return toast;
    },
    
    createToast(type, title, message) {
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.style.position = 'relative';
        toast.style.overflow = 'hidden';
        
        const icons = {
            success: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
            error: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>',
            warning: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>',
            info: '<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
        };
        
        toast.innerHTML = `
            <div class="toast-icon">${icons[type]}</div>
            <div class="toast-content">
                <div class="toast-title">${title}</div>
                ${message ? `<div class="toast-message">${message}</div>` : ''}
            </div>
            <div class="toast-close">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </div>
            <div class="toast-progress"></div>
        `;
        
        // Botão de fechar
        toast.querySelector('.toast-close').addEventListener('click', () => {
            this.remove(toast);
        });
        
        return toast;
    },
    
    remove(toast) {
        toast.classList.add('removing');
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    },
    
    success(title, message) {
        return this.show('success', title, message);
    },
    
    error(title, message) {
        return this.show('error', title, message);
    },
    
    warning(title, message) {
        return this.show('warning', title, message);
    },
    
    info(title, message) {
        return this.show('info', title, message);
    }
};

// Inicializa quando o DOM carregar
document.addEventListener('DOMContentLoaded', () => {
    ToastManager.init();
});

// Expor globalmente
window.toast = ToastManager;
</script>
// Import Bootstrap and dependencies
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';

// Import FontAwesome
import '@fortawesome/fontawesome-free/css/all.min.css';

// Import Chart.js
import Chart from 'chart.js/auto';
window.Chart = Chart;

// Import SweetAlert2
import Swal from 'sweetalert2';
window.Swal = Swal;

// Import Flatpickr
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.min.css';
import { Portuguese } from 'flatpickr/dist/l10n/pt';
flatpickr.localize(Portuguese);
window.flatpickr = flatpickr;

// Import InputMask
import Inputmask from 'inputmask';
window.Inputmask = Inputmask;

// Custom JavaScript for the application
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Initialize popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Auto-hide alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(function(alert) {
        if (alert.classList.contains('alert-dismissible')) {
            setTimeout(function() {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        }
    });

    // Initialize date pickers
    const datePickers = document.querySelectorAll('.date-picker');
    datePickers.forEach(function(picker) {
        flatpickr(picker, {
            locale: 'pt',
            dateFormat: 'd/m/Y',
            allowInput: true
        });
    });

    // Initialize currency masks
    const currencyInputs = document.querySelectorAll('.currency-input');
    currencyInputs.forEach(function(input) {
        Inputmask('currency', {
            prefix: 'R$ ',
            groupSeparator: '.',
            radixPoint: ',',
            digits: 2,
            autoGroup: true,
            rightAlign: false
        }).mask(input);
    });

    // Initialize CPF masks
    const cpfInputs = document.querySelectorAll('.cpf-input');
    cpfInputs.forEach(function(input) {
        Inputmask('999.999.999-99').mask(input);
    });

    // Initialize CNPJ masks
    const cnpjInputs = document.querySelectorAll('.cnpj-input');
    cnpjInputs.forEach(function(input) {
        Inputmask('99.999.999/9999-99').mask(input);
    });

    // Initialize phone masks
    const phoneInputs = document.querySelectorAll('.phone-input');
    phoneInputs.forEach(function(input) {
        Inputmask('(99) 99999-9999').mask(input);
    });

    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Confirm delete modals
    const deleteButtons = document.querySelectorAll('[data-confirm-delete]');
    deleteButtons.forEach(function(button) {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const title = this.dataset.title || 'Confirmar Exclusão';
            const message = this.dataset.message || 'Tem certeza que deseja excluir este item?';
            const url = this.href || this.dataset.url;
            
            Swal.fire({
                title: title,
                text: message,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, excluir',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    if (this.tagName === 'FORM') {
                        this.submit();
                    } else {
                        window.location.href = url;
                    }
                }
            });
        });
    });

    // Auto-save functionality for forms
    const autoSaveForms = document.querySelectorAll('[data-auto-save]');
    autoSaveForms.forEach(function(form) {
        const inputs = form.querySelectorAll('input, select, textarea');
        let timeout;

        inputs.forEach(function(input) {
            input.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    saveFormData(form);
                }, 2000); // Auto-save after 2 seconds of inactivity
            });
        });
    });

    // Sidebar toggle for mobile
    const sidebarToggle = document.querySelector('#sidebarToggle');
    const sidebar = document.querySelector('#sidebar');
    
    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
        });
    }

    // Click outside to close sidebar on mobile
    document.addEventListener('click', function(e) {
        if (sidebar && sidebar.classList.contains('show')) {
            if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                sidebar.classList.remove('show');
            }
        }
    });
});

// Auto-save function
function saveFormData(form) {
    const formData = new FormData(form);
    const data = {};
    
    for (let [key, value] of formData.entries()) {
        data[key] = value;
    }

    // Show saving indicator
    const indicator = document.createElement('div');
    indicator.className = 'alert alert-info alert-sm';
    indicator.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Salvando...';
    indicator.style.position = 'fixed';
    indicator.style.top = '20px';
    indicator.style.right = '20px';
    indicator.style.zIndex = '9999';
    document.body.appendChild(indicator);

    // Simulate auto-save (replace with actual AJAX call)
    setTimeout(function() {
        indicator.className = 'alert alert-success alert-sm';
        indicator.innerHTML = '<i class="fas fa-check"></i> Salvo automaticamente';
        
        setTimeout(function() {
            indicator.remove();
        }, 2000);
    }, 1000);
}

// Global functions
window.formatCurrency = function(amount) {
    return new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL'
    }).format(amount);
};

window.showToast = function(message, type = 'success') {
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: type,
        title: message,
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
};

window.confirmAction = function(message, callback) {
    Swal.fire({
        title: 'Confirmação',
        text: message,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed && callback) {
            callback();
        }
    });
};

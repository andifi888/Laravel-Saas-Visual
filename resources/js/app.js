import axios from 'axios';
import './bootstrap';
import 'datatables.net-bs5';
import 'datatables.net-buttons-bs5';
import 'datatables.net-buttons/js/buttons.html5';
import 'datatables.net-buttons/js/buttons.print';

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found');
}

window.App = {
    theme: localStorage.getItem('theme') || 'light',
    
    init() {
        this.setTheme(this.theme);
        this.setupSidebar();
        this.setupDataTables();
        this.setupCharts();
    },
    
    setTheme(theme) {
        this.theme = theme;
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        
        const themeIcon = document.querySelector('.theme-icon');
        if (themeIcon) {
            themeIcon.className = theme === 'dark' ? 'fas fa-sun theme-icon' : 'fas fa-moon theme-icon';
        }
    },
    
    toggleTheme() {
        this.setTheme(this.theme === 'dark' ? 'light' : 'dark');
        this.refreshCharts();
    },
    
    setupSidebar() {
        const toggleBtn = document.querySelector('.sidebar-toggle');
        const sidebar = document.querySelector('.sidebar');
        
        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('active');
            });
        }
    },
    
    setupDataTables() {
        if (typeof $.fn.DataTable !== 'undefined') {
            $.extend(true, $.fn.DataTable.defaults, {
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        previous: "Previous",
                        next: "Next"
                    }
                },
                dom: 'Bfrtip',
                buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
            });
        }
    },
    
    setupCharts() {
        window.refreshCharts = () => {
            const charts = window.echartsInstances || [];
            charts.forEach(chart => {
                if (chart) {
                    chart.dispose();
                }
            });
            window.echartsInstances = [];
            if (typeof initCharts === 'function') {
                initCharts();
            }
        };
    },
    
    notify(message, type = 'success') {
        const container = document.querySelector('.toast-container') || this.createToastContainer();
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="d-flex align-items-center">
                <i class="fas fa-${type === 'success' ? 'check-circle text-success' : 'exclamation-circle text-danger'} me-2"></i>
                <span>${message}</span>
            </div>
        `;
        container.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    },
    
    createToastContainer() {
        const container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
        return container;
    },
    
    formatCurrency(amount) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(amount);
    },
    
    formatNumber(num) {
        return new Intl.NumberFormat('en-US').format(num);
    },
    
    formatDate(date) {
        return new Intl.DateTimeFormat('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        }).format(new Date(date));
    }
};

document.addEventListener('DOMContentLoaded', () => {
    App.init();
});

window.App = App;

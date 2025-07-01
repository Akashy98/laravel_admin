/**
 * Admin Panel Common JavaScript Functions
 * Reusable methods for datatables, toasters, and other common functionality
 */

// Global variables
let adminCommon = {
    csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'),
    baseUrl: window.location.origin,
    isMobile: window.innerWidth <= 768
};

/**
 * Toast Notification System
 */
const Toast = {
    // Toast container
    container: null,

    // Initialize toast container
    init() {
        if (!this.container) {
            this.container = document.createElement('div');
            this.container.id = 'toast-container';
            this.container.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                z-index: 9999;
                max-width: 350px;
            `;
            document.body.appendChild(this.container);
        }
    },

    // Show toast notification
    show(message, type = 'info', duration = 5000) {
        this.init();

        const toast = document.createElement('div');
        toast.className = `toast-notification toast-${type}`;
        toast.style.cssText = `
            background: ${this.getToastColor(type)};
            color: white;
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transform: translateX(100%);
            transition: transform 0.3s ease;
            position: relative;
            overflow: hidden;
        `;

        // Add icon
        const icon = document.createElement('i');
        icon.className = `fas ${this.getToastIcon(type)} me-2`;
        toast.appendChild(icon);

        // Add message
        const text = document.createElement('span');
        text.textContent = message;
        toast.appendChild(text);

        // Add close button
        const closeBtn = document.createElement('button');
        closeBtn.innerHTML = '&times;';
        closeBtn.style.cssText = `
            position: absolute;
            top: 5px;
            right: 10px;
            background: none;
            border: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            opacity: 0.7;
        `;
        closeBtn.onclick = () => this.hide(toast);
        toast.appendChild(closeBtn);

        this.container.appendChild(toast);

        // Animate in
        setTimeout(() => {
            toast.style.transform = 'translateX(0)';
        }, 100);

        // Auto hide
        if (duration > 0) {
            setTimeout(() => {
                this.hide(toast);
            }, duration);
        }

        return toast;
    },

    // Hide toast
    hide(toast) {
        toast.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (toast.parentNode) {
                toast.parentNode.removeChild(toast);
            }
        }, 300);
    },

    // Get toast color based on type
    getToastColor(type) {
        const colors = {
            success: 'linear-gradient(135deg, #11998e 0%, #38ef7d 100%)',
            error: 'linear-gradient(135deg, #ff6b6b 0%, #ee5a24 100%)',
            warning: 'linear-gradient(135deg, #f093fb 0%, #f5576c 100%)',
            info: 'linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)'
        };
        return colors[type] || colors.info;
    },

    // Get toast icon based on type
    getToastIcon(type) {
        const icons = {
            success: 'fa-check-circle',
            error: 'fa-exclamation-circle',
            warning: 'fa-exclamation-triangle',
            info: 'fa-info-circle'
        };
        return icons[type] || icons.info;
    },

    // Convenience methods
    success(message, duration) {
        return this.show(message, 'success', duration);
    },

    error(message, duration) {
        return this.show(message, 'error', duration);
    },

    warning(message, duration) {
        return this.show(message, 'warning', duration);
    },

    info(message, duration) {
        return this.show(message, 'info', duration);
    }
};

/**
 * DataTable Wrapper
 */
const AdminDataTable = {
    instances: new Map(),

    // Initialize DataTable with proper checks
    init: function(selector, options = {}) {
        const table = $(selector);

        // Check if table exists
        if (table.length === 0) {
            console.warn('Table not found:', selector);
            return null;
        }

        // Check if DataTable library is available
        if (typeof $.fn.DataTable === 'undefined') {
            console.warn('DataTable library not loaded');
            return null;
        }

        // Check if already initialized
        if ($.fn.DataTable.isDataTable(selector)) {
            console.log('DataTable already initialized, destroying first');
            this.destroy(selector);
        }

        // Default options
        const defaultOptions = {
            responsive: true,
            pageLength: 10,
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                infoEmpty: "Showing 0 to 0 of 0 entries",
                infoFiltered: "(filtered from _MAX_ total entries)"
            },
            dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
                 '<"row"<"col-sm-12"tr>>' +
                 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
        };

        // Merge options
        const finalOptions = { ...defaultOptions, ...options };

        // Initialize DataTable
        const dataTable = table.DataTable(finalOptions);

        // Store instance
        this.instances.set(selector, dataTable);

        return dataTable;
    },

    // Destroy DataTable
    destroy: function(selector) {
        if ($.fn.DataTable.isDataTable(selector)) {
            $(selector).DataTable().destroy();
            this.instances.delete(selector);
        }
    },

    // Get DataTable instance
    get: function(selector) {
        return this.instances.get(selector);
    },

    // Refresh DataTable
    refresh: function(selector) {
        const instance = this.get(selector);
        if (instance) {
            instance.columns.adjust().responsive.recalc();
        }
    },

    // Clear all instances
    clearAll: function() {
        this.instances.forEach((instance, selector) => {
            this.destroy(selector);
        });
    }
};

/**
 * AJAX Helper
 */
const Ajax = {
    // Make AJAX request
    request(url, options = {}) {
        const defaultOptions = {
            method: 'GET',
            headers: {
                'X-CSRF-TOKEN': adminCommon.csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            }
        };

        const finalOptions = { ...defaultOptions, ...options };

        return fetch(url, finalOptions)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .catch(error => {
                console.error('AJAX Error:', error);
                Toast.error('An error occurred while processing your request');
                throw error;
            });
    },

    // GET request
    get(url, options = {}) {
        return this.request(url, { ...options, method: 'GET' });
    },

    // POST request
    post(url, data = {}, options = {}) {
        return this.request(url, {
            ...options,
            method: 'POST',
            body: JSON.stringify(data)
        });
    },

    // PUT request
    put(url, data = {}, options = {}) {
        return this.request(url, {
            ...options,
            method: 'PUT',
            body: JSON.stringify(data)
        });
    },

    // DELETE request
    delete(url, options = {}) {
        return this.request(url, { ...options, method: 'DELETE' });
    }
};

/**
 * Form Helper
 */
const FormHelper = {
    // Serialize form data
    serialize(form) {
        const formData = new FormData(form);
        const data = {};

        for (let [key, value] of formData.entries()) {
            data[key] = value;
        }

        return data;
    },

    // Validate form
    validate(form, rules = {}) {
        const errors = [];
        const formData = this.serialize(form);

        for (const [field, rule] of Object.entries(rules)) {
            const value = formData[field];

            if (rule.required && (!value || value.trim() === '')) {
                errors.push(`${field} is required`);
            }

            if (rule.email && value && !this.isValidEmail(value)) {
                errors.push(`${field} must be a valid email`);
            }

            if (rule.minLength && value && value.length < rule.minLength) {
                errors.push(`${field} must be at least ${rule.minLength} characters`);
            }
        }

        return errors;
    },

    // Check if email is valid
    isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    },

    // Reset form
    reset(form) {
        form.reset();
        // Clear validation states
        form.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
    },

    // Show form errors
    showErrors(form, errors) {
        // Clear previous errors
        form.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });

        // Show new errors
        errors.forEach(error => {
            const field = error.field || error;
            const input = form.querySelector(`[name="${field}"]`);
            if (input) {
                input.classList.add('is-invalid');

                // Add error message
                let errorDiv = input.parentNode.querySelector('.invalid-feedback');
                if (!errorDiv) {
                    errorDiv = document.createElement('div');
                    errorDiv.className = 'invalid-feedback';
                    input.parentNode.appendChild(errorDiv);
                }
                errorDiv.textContent = error.message || error;
            }
        });
    }
};

/**
 * Modal Helper
 */
const ModalHelper = {
    // Show modal
    show(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            const bootstrapModal = new bootstrap.Modal(modal);
            bootstrapModal.show();
            return bootstrapModal;
        }
        return null;
    },

    // Hide modal
    hide(modalId) {
        const modal = document.getElementById(modalId);
        if (modal) {
            const bootstrapModal = bootstrap.Modal.getInstance(modal);
            if (bootstrapModal) {
                bootstrapModal.hide();
            }
        }
    },

    // Set modal content
    setContent(modalId, title, body) {
        const modal = document.getElementById(modalId);
        if (modal) {
            const titleEl = modal.querySelector('.modal-title');
            const bodyEl = modal.querySelector('.modal-body');

            if (titleEl) titleEl.textContent = title;
            if (bodyEl) bodyEl.innerHTML = body;
        }
    }
};

/**
 * Utility Functions
 */
const Utils = {
    // Debounce function
    debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },

    // Throttle function
    throttle(func, limit) {
        let inThrottle;
        return function() {
            const args = arguments;
            const context = this;
            if (!inThrottle) {
                func.apply(context, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    },

    // Format date
    formatDate(date, format = 'YYYY-MM-DD') {
        const d = new Date(date);
        const year = d.getFullYear();
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');

        return format
            .replace('YYYY', year)
            .replace('MM', month)
            .replace('DD', day);
    },

    // Format currency
    formatCurrency(amount, currency = 'USD') {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: currency
        }).format(amount);
    },

    // Copy to clipboard
    copyToClipboard(text) {
        if (navigator.clipboard) {
            return navigator.clipboard.writeText(text);
        } else {
            // Fallback for older browsers
            const textArea = document.createElement('textarea');
            textArea.value = text;
            document.body.appendChild(textArea);
            textArea.select();
            document.execCommand('copy');
            document.body.removeChild(textArea);
            return Promise.resolve();
        }
    },

    // Generate random ID
    generateId(length = 8) {
        return Math.random().toString(36).substring(2, length + 2);
    },

    // Check if element is in viewport
    isInViewport(element) {
        const rect = element.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    }
};

/**
 * Sidebar Management
 */
const Sidebar = {
    // Toggle sidebar
    toggle() {
        const sidebar = document.getElementById('sidebar');
        if (sidebar) {
            sidebar.classList.toggle('show');
        }
    },

    // Close sidebar
    close() {
        const sidebar = document.getElementById('sidebar');
        if (sidebar) {
            sidebar.classList.remove('show');
        }
    },

    // Initialize sidebar events
    init() {
        // Toggle button
        const toggleBtn = document.querySelector('.sidebar-toggle');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', this.toggle);
        }

        // Close on outside click (mobile)
        document.addEventListener('click', (event) => {
            if (adminCommon.isMobile) {
                const sidebar = document.getElementById('sidebar');
                const toggleBtn = document.querySelector('.sidebar-toggle');

                if (sidebar && !sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                    this.close();
                }
            }
        });

        // Handle window resize
        window.addEventListener('resize', Utils.debounce(() => {
            adminCommon.isMobile = window.innerWidth <= 768;
            if (!adminCommon.isMobile) {
                this.close();
            }
        }, 250));
    }
};

/**
 * Loading Spinner
 */
const Loading = {
    // Show loading spinner
    show(container = document.body) {
        const spinner = document.createElement('div');
        spinner.className = 'loading-overlay';
        spinner.innerHTML = `
            <div class="loading-spinner-container">
                <div class="loading-spinner"></div>
                <p>Loading...</p>
            </div>
        `;
        spinner.style.cssText = `
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        `;

        const spinnerContainer = spinner.querySelector('.loading-spinner-container');
        spinnerContainer.style.cssText = `
            text-align: center;
            color: white;
        `;

        container.appendChild(spinner);
        return spinner;
    },

    // Hide loading spinner
    hide(spinner) {
        if (spinner && spinner.parentNode) {
            spinner.parentNode.removeChild(spinner);
        }
    }
};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize sidebar
    Sidebar.init();

    // Auto-hide alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            if (alert.parentNode) {
                alert.style.opacity = '0';
                setTimeout(() => {
                    if (alert.parentNode) {
                        alert.parentNode.removeChild(alert);
                    }
                }, 300);
            }
        }, 5000);
    });
});

// Export for use in other files
window.AdminCommon = {
    Toast,
    AdminDataTable,
    Ajax,
    FormHelper,
    ModalHelper,
    Utils,
    Sidebar,
    Loading
};

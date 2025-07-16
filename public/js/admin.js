/**
 * Admin Panel JavaScript
 * Specific functionality for admin dashboard and user management
 */

// User Management Functions
const UserManagement = {
    // Edit user
    editUser(userId) {
        Loading.show();

        Ajax.get(`/admin/users/${userId}/edit`)
            .then(user => {
                Loading.hide();

                // Populate edit modal
                document.getElementById('edit_name').value = user.name;
                document.getElementById('edit_email').value = user.email;
                document.getElementById('edit_is_admin').checked = user.role_id === 1;
                document.getElementById('editUserForm').action = `/admin/users/${userId}`;

                // Show modal
                ModalHelper.show('editUserModal');
            })
            .catch(error => {
                Loading.hide();
                Toast.error('Failed to load user data');
            });
    },

    // Make user admin
    makeAdmin(userId) {
        if (confirm('Are you sure you want to make this user an admin?')) {
            Loading.show();

            Ajax.post(`/admin/users/${userId}/make-admin`)
                .then(response => {
                    Loading.hide();
                    Toast.success('User promoted to admin successfully');
                    setTimeout(() => window.location.reload(), 1000);
                })
                .catch(error => {
                    Loading.hide();
                    Toast.error('Failed to promote user to admin');
                });
        }
    },

    // Delete user
    deleteUser(userId) {
        if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
            Loading.show();

            Ajax.delete(`/admin/users/${userId}`)
                .then(response => {
                    Loading.hide();
                    Toast.success('User deleted successfully');
                    setTimeout(() => window.location.reload(), 1000);
                })
                .catch(error => {
                    Loading.hide();
                    Toast.error('Failed to delete user');
                });
        }
    },

    // Initialize user management
    init() {
        // Add event listeners for user actions
        document.addEventListener('click', (e) => {
            if (e.target.matches('[data-action="edit-user"]')) {
                const userId = e.target.dataset.userId;
                this.editUser(userId);
            }

            if (e.target.matches('[data-action="make-admin"]')) {
                const userId = e.target.dataset.userId;
                this.makeAdmin(userId);
            }

            if (e.target.matches('[data-action="delete-user"]')) {
                const userId = e.target.dataset.userId;
                this.deleteUser(userId);
            }
        });

        // Initialize DataTable for users table
        const usersTable = document.getElementById('usersTable');
        if (usersTable) {
            DataTable.init('#usersTable', {
                pageLength: 10,
                order: [[0, 'desc']],
                columnDefs: [
                    { orderable: false, targets: -1 } // Disable sorting on actions column
                ]
            });
        }
    }
};

// Dashboard Functions
const Dashboard = {
    // Initialize dashboard
    init() {
        this.initCharts();
        this.initStatsCards();
        this.loadRecentActivity();
    },

    // Initialize charts (if Chart.js is available)
    initCharts() {
        if (typeof Chart !== 'undefined') {
            this.initUserChart();
            this.initActivityChart();
        }
    },

    // Initialize user statistics chart
    initUserChart() {
        const ctx = document.getElementById('userChart');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Regular Users', 'Admin Users'],
                datasets: [{
                    data: [window.userStats?.regular || 0, window.userStats?.admin || 0],
                    backgroundColor: [
                        '#667eea',
                        '#764ba2'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    },

    // Initialize activity chart
    initActivityChart() {
        const ctx = document.getElementById('activityChart');
        if (!ctx) return;

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'User Registrations',
                    data: [12, 19, 3, 5, 2, 3],
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    },

    // Initialize stats cards with animations
    initStatsCards() {
        const cards = document.querySelectorAll('.stats-card');

        // Add click handlers for stats cards
        cards.forEach(card => {
            card.addEventListener('click', () => {
                // Add click animation
                card.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    card.style.transform = '';
                }, 150);
            });
        });

        // Animate stats numbers
        this.animateNumbers();
    },

    // Animate number counters
    animateNumbers() {
        const numbers = document.querySelectorAll('.stats-card h3');

        numbers.forEach(number => {
            const target = parseInt(number.textContent);
            const duration = 2000;
            const increment = target / (duration / 16);
            let current = 0;

            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                number.textContent = Math.floor(current);
            }, 16);
        });
    },

    // Load recent activity
    loadRecentActivity() {
        const activityContainer = document.getElementById('recentActivity');
        if (!activityContainer) return;

        // Simulate loading recent activity
        // In a real application, this would be an AJAX call
        setTimeout(() => {
            activityContainer.innerHTML = `
                <div class="activity-item d-flex align-items-center mb-3">
                    <div class="activity-icon bg-primary-gradient rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div>
                        <div class="fw-bold">New user registered</div>
                        <small class="text-muted">John Doe joined the platform</small>
                    </div>
                    <small class="text-muted ms-auto">2 hours ago</small>
                </div>
                <div class="activity-item d-flex align-items-center mb-3">
                    <div class="activity-icon bg-success-gradient rounded-circle me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <div class="fw-bold">User promoted to admin</div>
                        <small class="text-muted">Jane Smith is now an admin</small>
                    </div>
                    <small class="text-muted ms-auto">1 day ago</small>
                </div>
            `;
        }, 1000);
    }
};

// Form Validation
const FormValidation = {
    // Initialize form validation
    init() {
        this.initUserForm();
        this.initEditUserForm();
    },

    // Initialize add user form validation
    initUserForm() {
        const form = document.getElementById('addUserForm');
        if (!form) return;

        form.addEventListener('submit', (e) => {
            const errors = FormHelper.validate(form, {
                name: { required: true, minLength: 2 },
                email: { required: true, email: true },
                password: { required: true, minLength: 8 }
            });

            if (errors.length > 0) {
                e.preventDefault();
                FormHelper.showErrors(form, errors);
                Toast.error('Please fix the errors in the form');
            }
        });
    },

    // Initialize edit user form validation
    initEditUserForm() {
        const form = document.getElementById('editUserForm');
        if (!form) return;

        form.addEventListener('submit', (e) => {
            const errors = FormHelper.validate(form, {
                name: { required: true, minLength: 2 },
                email: { required: true, email: true }
            });

            if (errors.length > 0) {
                e.preventDefault();
                FormHelper.showErrors(form, errors);
                Toast.error('Please fix the errors in the form');
            }
        });
    }
};

// Search and Filter Functions
const SearchFilter = {
    // Initialize search functionality
    init() {
        this.initUserSearch();
        this.initTableFilters();
    },

    // Initialize user search
    initUserSearch() {
        const searchInput = document.getElementById('userSearch');
        if (!searchInput) return;

        const searchFunction = Utils.debounce((query) => {
            const rows = document.querySelectorAll('#usersTable tbody tr');

            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const matches = text.includes(query.toLowerCase());
                row.style.display = matches ? '' : 'none';
            });
        }, 300);

        searchInput.addEventListener('input', (e) => {
            searchFunction(e.target.value);
        });
    },

    // Initialize table filters
    initTableFilters() {
        const filterSelects = document.querySelectorAll('.table-filter');

        filterSelects.forEach(select => {
            select.addEventListener('change', (e) => {
                const filterValue = e.target.value;
                const filterType = e.target.dataset.filter;

                // Apply filter logic here
                this.applyFilter(filterType, filterValue);
            });
        });
    },

    // Apply filter to table
    applyFilter(type, value) {
        const rows = document.querySelectorAll('#usersTable tbody tr');

        rows.forEach(row => {
            let show = true;

            if (type === 'role') {
                const roleCell = row.querySelector('[data-role]');
                if (roleCell && value !== 'all') {
                    show = roleCell.dataset.role === value;
                }
            }

            row.style.display = show ? '' : 'none';
        });
    }
};

// Export Functions
const ExportFunctions = {
    // Export users to CSV
    exportUsersCSV() {
        const table = document.getElementById('usersTable');
        if (!table) return;

        const rows = table.querySelectorAll('tbody tr');
        let csv = 'Name,Email,Role,Created\n';

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const name = cells[1]?.textContent?.trim() || '';
            const email = cells[2]?.textContent?.trim() || '';
            const role = cells[3]?.textContent?.trim() || '';
            const created = cells[4]?.textContent?.trim() || '';

            csv += `"${name}","${email}","${role}","${created}"\n`;
        });

        this.downloadCSV(csv, 'users-export.csv');
    },

    // Download CSV file
    downloadCSV(csv, filename) {
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        a.click();
        window.URL.revokeObjectURL(url);
    }
};

// Initialize everything when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Initialize all modules
    UserManagement.init();
    Dashboard.init();
    FormValidation.init();
    SearchFilter.init();

    // Add export functionality
    const exportBtn = document.getElementById('exportUsers');
    if (exportBtn) {
        exportBtn.addEventListener('click', ExportFunctions.exportUsersCSV);
    }

    // Add keyboard shortcuts
    document.addEventListener('keydown', (e) => {
        // Ctrl/Cmd + K to focus search
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            const searchInput = document.getElementById('userSearch');
            if (searchInput) {
                searchInput.focus();
            }
        }

        // Escape to close modals
        if (e.key === 'Escape') {
            const modals = document.querySelectorAll('.modal.show');
            modals.forEach(modal => {
                const bootstrapModal = bootstrap.Modal.getInstance(modal);
                if (bootstrapModal) {
                    bootstrapModal.hide();
                }
            });
        }
    });

    // Add tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Add popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
});

// Export for use in other files
window.AdminJS = {
    UserManagement,
    Dashboard,
    FormValidation,
    SearchFilter,
    ExportFunctions
};

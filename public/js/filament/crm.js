/**
 * Enterprise CRM - JavaScript Functions
 * Handles all interactive features and AJAX operations
 */

(function() {
    'use strict';

    // ==========================================
    // Initialize App
    // ==========================================
    document.addEventListener('DOMContentLoaded', function() {
        initSidebar();
        initModals();
        initForms();
        initSearch();
        initKanban();
        initCharts();
        initNotifications();
        initDataTables();
    });

    // ==========================================
    // Sidebar Toggle
    // ==========================================
    function initSidebar() {
        const menuToggle = document.querySelector('.crm-header__menu-toggle');
        const sidebar = document.querySelector('.crm-sidebar');
        const overlay = document.createElement('div');

        overlay.className = 'crm-overlay';
        document.body.appendChild(overlay);

        if (menuToggle) {
            menuToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            });
        }

        overlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            overlay.classList.remove('active');
        });

        // Close sidebar on escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && sidebar.classList.contains('active')) {
                sidebar.classList.remove('active');
                overlay.classList.remove('active');
            }
        });
    }

    // ==========================================
    // Modal Management
    // ==========================================
    function initModals() {
        const modalTriggers = document.querySelectorAll('[data-modal-target]');
        const modalCloses = document.querySelectorAll('.crm-modal__close, [data-modal-close]');

        modalTriggers.forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                e.preventDefault();
                const modalId = this.getAttribute('data-modal-target');
                const modal = document.getElementById(modalId);
                if (modal) {
                    openModal(modal);
                }
            });
        });

        modalCloses.forEach(close => {
            close.addEventListener('click', function() {
                const modal = this.closest('.crm-modal');
                if (modal) {
                    closeModal(modal);
                }
            });
        });

        // Close on outside click
        document.querySelectorAll('.crm-modal').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeModal(this);
                }
            });
        });

        // Close on escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const activeModal = document.querySelector('.crm-modal.active');
                if (activeModal) {
                    closeModal(activeModal);
                }
            }
        });
    }

    function openModal(modal) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(modal) {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    // ==========================================
    // Form Handling
    // ==========================================
    function initForms() {
        const forms = document.querySelectorAll('.crm-form[data-ajax]');

        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                handleFormSubmit(this);
            });
        });

        // Real-time validation
        const inputs = document.querySelectorAll('.crm-form__input[required]');
        inputs.forEach(input => {
            input.addEventListener('blur', function() {
                validateField(this);
            });
        });
    }

    function handleFormSubmit(form) {
        const formData = new FormData(form);
        const url = form.getAttribute('action');
        const method = form.getAttribute('method') || 'POST';

        showLoadingState(form);

        fetch(url, {
            method: method,
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoadingState(form);
            if (data.success) {
                showNotification('Success!', data.message, 'success');
                form.reset();
                if (data.redirect) {
                    setTimeout(() => {
                        window.location.href = data.redirect;
                    }, 1000);
                }
            } else {
                showNotification('Error!', data.message, 'danger');
                displayFormErrors(form, data.errors);
            }
        })
        .catch(error => {
            hideLoadingState(form);
            showNotification('Error!', 'Something went wrong. Please try again.', 'danger');
            console.error('Form submission error:', error);
        });
    }

    function validateField(field) {
        const value = field.value.trim();
        const type = field.getAttribute('type');
        let isValid = true;
        let errorMessage = '';

        if (field.hasAttribute('required') && !value) {
            isValid = false;
            errorMessage = 'This field is required';
        } else if (type === 'email' && value && !isValidEmail(value)) {
            isValid = false;
            errorMessage = 'Please enter a valid email address';
        } else if (type === 'url' && value && !isValidUrl(value)) {
            isValid = false;
            errorMessage = 'Please enter a valid URL';
        }

        if (!isValid) {
            showFieldError(field, errorMessage);
        } else {
            clearFieldError(field);
        }

        return isValid;
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function isValidUrl(url) {
        try {
            new URL(url);
            return true;
        } catch {
            return false;
        }
    }

    function showFieldError(field, message) {
        clearFieldError(field);
        field.classList.add('crm-form__input--error');
        const error = document.createElement('div');
        error.className = 'crm-form__error';
        error.textContent = message;
        field.parentNode.appendChild(error);
    }

    function clearFieldError(field) {
        field.classList.remove('crm-form__input--error');
        const error = field.parentNode.querySelector('.crm-form__error');
        if (error) {
            error.remove();
        }
    }

    function displayFormErrors(form, errors) {
        Object.keys(errors).forEach(fieldName => {
            const field = form.querySelector(`[name="${fieldName}"]`);
            if (field) {
                showFieldError(field, errors[fieldName][0]);
            }
        });
    }

    function showLoadingState(form) {
        const submitBtn = form.querySelector('[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.dataset.originalText = submitBtn.textContent;
            submitBtn.innerHTML = '<span class="crm-spinner"></span> Processing...';
        }
    }

    function hideLoadingState(form) {
        const submitBtn = form.querySelector('[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = submitBtn.dataset.originalText || 'Submit';
        }
    }

    // ==========================================
    // Search Functionality
    // ==========================================
    function initSearch() {
        const searchInput = document.querySelector('.crm-search__input');

        if (searchInput) {
            let searchTimeout;

            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const query = this.value.trim();

                searchTimeout = setTimeout(() => {
                    if (query.length >= 2) {
                        performSearch(query);
                    } else {
                        clearSearchResults();
                    }
                }, 300);
            });
        }
    }

    function performSearch(query) {
        fetch(`/api/search?q=${encodeURIComponent(query)}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            displaySearchResults(data);
        })
        .catch(error => {
            console.error('Search error:', error);
        });
    }

    function displaySearchResults(results) {
        const container = document.querySelector('.crm-search__results');
        if (!container) return;

        if (results.length === 0) {
            container.innerHTML = '<div class="crm-search__empty">No results found</div>';
            return;
        }

        const html = results.map(result => `
            <a href="${result.url}" class="crm-search__result">
                <div class="crm-search__result-title">${result.title}</div>
                <div class="crm-search__result-type">${result.type}</div>
            </a>
        `).join('');

        container.innerHTML = html;
    }

    function clearSearchResults() {
        const container = document.querySelector('.crm-search__results');
        if (container) {
            container.innerHTML = '';
        }
    }

    // ==========================================
    // Kanban Board
    // ==========================================
    function initKanban() {
        const kanbanCards = document.querySelectorAll('.crm-kanban-card');
        const kanbanColumns = document.querySelectorAll('.crm-kanban__cards');

        kanbanCards.forEach(card => {
            card.setAttribute('draggable', 'true');

            card.addEventListener('dragstart', function(e) {
                e.dataTransfer.effectAllowed = 'move';
                e.dataTransfer.setData('text/html', this.innerHTML);
                this.classList.add('dragging');
            });

            card.addEventListener('dragend', function() {
                this.classList.remove('dragging');
            });
        });

        kanbanColumns.forEach(column => {
            column.addEventListener('dragover', function(e) {
                e.preventDefault();
                const dragging = document.querySelector('.dragging');
                this.appendChild(dragging);
            });

            column.addEventListener('drop', function(e) {
                e.preventDefault();
                const card = document.querySelector('.dragging');
                const newStage = this.closest('.crm-kanban__column').dataset.stage;
                updateCardStage(card.dataset.id, newStage);
            });
        });
    }

    function updateCardStage(cardId, newStage) {
        fetch(`/api/deals/${cardId}/stage`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ stage: newStage })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showNotification('Success!', 'Deal stage updated', 'success');
            }
        })
        .catch(error => {
            console.error('Update error:', error);
            showNotification('Error!', 'Failed to update deal stage', 'danger');
        });
    }

    // ==========================================
    // Charts Initialization
    // ==========================================
    function initCharts() {
        // Revenue Chart
        const revenueCanvas = document.getElementById('revenueChart');
        if (revenueCanvas && typeof Chart !== 'undefined') {
            const ctx = revenueCanvas.getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Revenue',
                        data: [12000, 19000, 15000, 25000, 22000, 30000],
                        borderColor: '#4f46e5',
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
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
                    }
                }
            });
        }

        // Sales Chart
        const salesCanvas = document.getElementById('salesChart');
        if (salesCanvas && typeof Chart !== 'undefined') {
            const ctx = salesCanvas.getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    datasets: [{
                        label: 'Sales',
                        data: [12, 19, 15, 25, 22, 30, 28],
                        backgroundColor: '#10b981'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }
    }

    // ==========================================
    // Notifications
    // ==========================================
    function initNotifications() {
        // Create notification container if it doesn't exist
        if (!document.querySelector('.crm-notifications')) {
            const container = document.createElement('div');
            container.className = 'crm-notifications';
            document.body.appendChild(container);
        }
    }

    function showNotification(title, message, type = 'info') {
        const container = document.querySelector('.crm-notifications');
        const notification = document.createElement('div');
        notification.className = `crm-notification crm-notification--${type}`;

        notification.innerHTML = `
            <div class="crm-notification__content">
                <div class="crm-notification__title">${title}</div>
                <div class="crm-notification__message">${message}</div>
            </div>
            <button class="crm-notification__close">&times;</button>
        `;

        container.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.classList.add('active');
        }, 10);

        // Close button
        notification.querySelector('.crm-notification__close').addEventListener('click', function() {
            closeNotification(notification);
        });

        // Auto close after 5 seconds
        setTimeout(() => {
            closeNotification(notification);
        }, 5000);
    }

    function closeNotification(notification) {
        notification.classList.remove('active');
        setTimeout(() => {
            notification.remove();
        }, 300);
    }

    // ==========================================
    // DataTables Enhancement
    // ==========================================
    function initDataTables() {
        const tables = document.querySelectorAll('.crm-table[data-sortable]');

        tables.forEach(table => {
            const headers = table.querySelectorAll('th[data-sortable]');

            headers.forEach(header => {
                header.style.cursor = 'pointer';
                header.addEventListener('click', function() {
                    sortTable(table, this);
                });
            });
        });
    }

    function sortTable(table, header) {
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const column = header.cellIndex;
        const currentOrder = header.dataset.order || 'asc';
        const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';

        rows.sort((a, b) => {
            const aValue = a.cells[column].textContent.trim();
            const bValue = b.cells[column].textContent.trim();

            if (newOrder === 'asc') {
                return aValue.localeCompare(bValue, undefined, { numeric: true });
            } else {
                return bValue.localeCompare(aValue, undefined, { numeric: true });
            }
        });

        rows.forEach(row => tbody.appendChild(row));
        header.dataset.order = newOrder;

        // Update sort indicators
        table.querySelectorAll('th').forEach(th => {
            th.classList.remove('sorted-asc', 'sorted-desc');
        });
        header.classList.add(`sorted-${newOrder}`);
    }

    // ==========================================
    // Export Functions
    // ==========================================
    window.CRM = {
        openModal: openModal,
        closeModal: closeModal,
        showNotification: showNotification,
        validateField: validateField
    };

})();
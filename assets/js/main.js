// Modern SIRH Application JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Loading Indicator Management
    const loadingIndicator = document.querySelector('.loading');
    
    function showLoading() {
        loadingIndicator.classList.add('active');
    }
    
    function hideLoading() {
        loadingIndicator.classList.remove('active');
    }

    // Theme Management
    const themeToggle = document.querySelector('.theme-toggle');
    if (themeToggle) {
        const savedTheme = localStorage.getItem('theme') || 'light';
        document.body.setAttribute('data-theme', savedTheme);
        updateThemeIcon(savedTheme);

        themeToggle.addEventListener('click', () => {
            const currentTheme = document.body.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.body.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        });
    }

    // Mobile Navigation Toggle with improved touch handling
    const navToggle = document.querySelector('.nav-toggle');
    const sidebar = document.querySelector('.app-sidebar');
    if (navToggle && sidebar) {
        navToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.toggle('show');
            document.body.classList.toggle('sidebar-open');
        });

        // Close sidebar when clicking outside
        document.addEventListener('click', (e) => {
            if (window.innerWidth < 992 && 
                sidebar.classList.contains('show') && 
                !sidebar.contains(e.target) && 
                !navToggle.contains(e.target)) {
                sidebar.classList.remove('show');
                document.body.classList.remove('sidebar-open');
            }
        });

        // Handle touch events for better mobile experience
        let touchStartX = 0;
        document.addEventListener('touchstart', (e) => {
            touchStartX = e.touches[0].clientX;
        }, false);

        document.addEventListener('touchmove', (e) => {
            if (!touchStartX) return;

            const touchEndX = e.touches[0].clientX;
            const diff = touchStartX - touchEndX;

            // Swipe left to close sidebar
            if (diff > 50 && sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
                document.body.classList.remove('sidebar-open');
            }
            // Swipe right to open sidebar
            else if (diff < -50 && !sidebar.classList.contains('show')) {
                sidebar.classList.add('show');
                document.body.classList.add('sidebar-open');
            }
        }, false);

        document.addEventListener('touchend', () => {
            touchStartX = 0;
        }, false);
    }

    // AJAX Form Submissions
    document.querySelectorAll('form[data-ajax="true"]').forEach(form => {
        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            showLoading();

            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: form.method,
                    body: formData
                });

                if (!response.ok) throw new Error('Network response was not ok');

                const result = await response.json();
                if (result.success) {
                    if (result.redirect) {
                        window.location.href = result.redirect;
                    } else {
                        // Show success message
                        showAlert('success', result.message);
                    }
                } else {
                    showAlert('danger', result.message || 'Une erreur est survenue');
                }
            } catch (error) {
                console.error('Error:', error);
                showAlert('danger', 'Une erreur est survenue lors de la soumission du formulaire');
            } finally {
                hideLoading();
            }
        });
    });

    // Alert Management
    function showAlert(type, message) {
        const alertHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <div class="alert-content">
                    <i class="bi bi-${type === 'success' ? 'check-circle' : 'exclamation-triangle'}"></i>
                    <span>${message}</span>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        const alertContainer = document.createElement('div');
        alertContainer.innerHTML = alertHTML;
        document.querySelector('.page-content').insertAdjacentElement('afterbegin', alertContainer.firstChild);
    }

    // Auto-dismiss alerts after 5 seconds
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            const closeButton = alert.querySelector('.btn-close');
            if (closeButton) closeButton.click();
        }, 5000);
    });

    // Fullscreen Toggle
    const fullscreenBtn = document.querySelector('.btn-fullscreen');
    if (fullscreenBtn) {
        fullscreenBtn.addEventListener('click', () => {
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen();
                fullscreenBtn.querySelector('i').classList.replace('bi-arrows-fullscreen', 'bi-fullscreen-exit');
            } else {
                if (document.exitFullscreen) {
                    document.exitFullscreen();
                    fullscreenBtn.querySelector('i').classList.replace('bi-fullscreen-exit', 'bi-arrows-fullscreen');
                }
            }
        });
    }

    // Search Functionality
    const searchInput = document.querySelector('.search-form input');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', (e) => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                handleSearch(e.target.value);
            }, 500);
        });
    }

    // Initialize Statistics Charts
    const statsData = document.getElementById('statisticsData');
    if (statsData) {
        initializeCharts(JSON.parse(statsData.dataset.stats));
    }

    // Form Validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!validateForm(form)) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });

    // Initialize tooltips
    const tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltips.map(function (tooltipTrigger) {
        return new bootstrap.Tooltip(tooltipTrigger);
    });
});

// Theme Icon Update
function updateThemeIcon(theme) {
    const icon = document.querySelector('.theme-toggle i');
    if (icon) {
        icon.classList.remove('bi-sun', 'bi-moon-stars');
        icon.classList.add(theme === 'dark' ? 'bi-sun' : 'bi-moon-stars');
    }
}

// Form Validation
function validateForm(form) {
    let isValid = true;
    
    // Required fields validation
    const requiredFields = form.querySelectorAll('[required]');
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });

    // Date validations
    const dateFields = {
        'date_naissance': { maxAge: 70, minAge: 18 },
        'date_recrutement': { maxPast: 50, future: false },
        'date_prise_service': { maxPast: 50, future: false }
    };

    Object.entries(dateFields).forEach(([fieldId, rules]) => {
        const field = form.querySelector(`#${fieldId}`);
        if (field && field.value) {
            if (!validateDateField(field, rules)) {
                isValid = false;
            }
        }
    });

    return isValid;
}

// Date Field Validation
function validateDateField(field, rules) {
    const date = new Date(field.value);
    const now = new Date();
    
    if (rules.minAge) {
        const minDate = new Date();
        minDate.setFullYear(minDate.getFullYear() - rules.minAge);
        if (date > minDate) {
            field.classList.add('is-invalid');
            return false;
        }
    }
    
    if (rules.maxAge) {
        const maxDate = new Date();
        maxDate.setFullYear(maxDate.getFullYear() - rules.maxAge);
        if (date < maxDate) {
            field.classList.add('is-invalid');
            return false;
        }
    }
    
    if (!rules.future && date > now) {
        field.classList.add('is-invalid');
        return false;
    }
    
    if (rules.maxPast) {
        const maxPastDate = new Date();
        maxPastDate.setFullYear(maxPastDate.getFullYear() - rules.maxPast);
        if (date < maxPastDate) {
            field.classList.add('is-invalid');
            return false;
        }
    }
    
    field.classList.remove('is-invalid');
    return true;
}

// Charts Initialization
function initializeCharts(stats) {
    if (!stats) return;

    const chartConfigs = {
        corpsChart: {
            type: 'doughnut',
            title: 'Distribution par Corps',
            colors: ['#6366f1', '#22c55e', '#eab308']
        },
        provinceChart: {
            type: 'bar',
            title: 'Distribution par Province',
            colors: ['#6366f1']
        },
        milieuChart: {
            type: 'pie',
            title: 'Distribution Urbain/Rural',
            colors: ['#22c55e', '#eab308']
        }
    };

    Object.entries(chartConfigs).forEach(([chartId, config]) => {
        const canvas = document.getElementById(chartId);
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        new Chart(ctx, {
            type: config.type,
            data: generateChartData(stats, chartId, config.colors),
            options: generateChartOptions(config)
        });
    });
}

// Generate Chart Data
function generateChartData(stats, chartId, colors) {
    let labels, data;

    switch (chartId) {
        case 'corpsChart':
            labels = stats.by_corps.map(item => item.corps);
            data = stats.by_corps.map(item => item.count);
            break;
        case 'provinceChart':
            labels = stats.by_province.map(item => item.province_etablissement);
            data = stats.by_province.map(item => item.count);
            break;
        case 'milieuChart':
            labels = stats.by_milieu.map(item => item.milieu_etablissement);
            data = stats.by_milieu.map(item => item.count);
            break;
    }

    return {
        labels: labels,
        datasets: [{
            data: data,
            backgroundColor: colors,
            borderWidth: 0
        }]
    };
}

// Generate Chart Options
function generateChartOptions(config) {
    const theme = document.body.getAttribute('data-theme');
    const textColor = theme === 'dark' ? '#f8fafc' : '#0f172a';

    return {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: textColor,
                    padding: 20,
                    font: {
                        size: 12,
                        family: "'Plus Jakarta Sans', sans-serif"
                    }
                }
            },
            title: {
                display: true,
                text: config.title,
                color: textColor,
                font: {
                    size: 16,
                    family: "'Plus Jakarta Sans', sans-serif",
                    weight: '600'
                },
                padding: {
                    bottom: 20
                }
            }
        }
    };
}

// Excel Export
function exportToExcel() {
    showLoading();
    window.location.href = 'index.php?action=exportToExcel';
}

// Delete Confirmation
function confirmDelete(employeeId, employeeName) {
    if (confirm(`Êtes-vous sûr de vouloir supprimer l'employé ${employeeName} ?`)) {
        showLoading();
        window.location.href = `index.php?action=delete&id=${employeeId}`;
    }
}

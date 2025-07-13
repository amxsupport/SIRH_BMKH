// Modern SIRH Application JavaScript

document.addEventListener('DOMContentLoaded', function() {
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

    // Mobile Navigation Toggle
    const navToggle = document.querySelector('.nav-toggle');
    const sidebar = document.querySelector('.app-sidebar');
    if (navToggle && sidebar) {
        navToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            document.body.classList.toggle('sidebar-open');
        });

        // Close sidebar when clicking outside
        document.addEventListener('click', (e) => {
            if (window.innerWidth < 992) {
                if (!sidebar.contains(e.target) && !navToggle.contains(e.target)) {
                    sidebar.classList.remove('show');
                    document.body.classList.remove('sidebar-open');
                }
            }
        });
    }

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

// Loading Indicators
function showLoading() {
    const loading = document.createElement('div');
    loading.className = 'loading';
    document.body.appendChild(loading);
}

function hideLoading() {
    const loading = document.querySelector('.loading');
    if (loading) {
        loading.remove();
    }
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

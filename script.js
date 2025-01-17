// DOM Elements
const sidebar = document.querySelector('.sidebar');
const menuToggle = document.querySelector('.menu-toggle');
const mainContent = document.querySelector('.main-content');
const searchBar = document.querySelector('.search-bar input');
const actionButtons = document.querySelectorAll('.action-btn');
const navLinks = document.querySelectorAll('.nav-links a');

// Toggle Sidebar
menuToggle.addEventListener('click', () => {
    sidebar.classList.toggle('active');
});

// Close sidebar when clicking outside
document.addEventListener('click', (e) => {
    if (!sidebar.contains(e.target) && !menuToggle.contains(e.target)) {
        sidebar.classList.remove('active');
    }
});

// Active Navigation Link
navLinks.forEach(link => {
    link.addEventListener('click', (e) => {
        // Remove active class from all links
        navLinks.forEach(l => l.parentElement.classList.remove('active'));
        // Add active class to clicked link
        link.parentElement.classList.add('active');
    });
});

// Search Functionality
searchBar.addEventListener('input', (e) => {
    const searchTerm = e.target.value.toLowerCase();
    const patientRows = document.querySelectorAll('.patients-list tbody tr');

    patientRows.forEach(row => {
        const patientName = row.querySelector('.patient-info span').textContent.toLowerCase();
        const patientService = row.querySelector('td:nth-child(2)').textContent.toLowerCase();

        if (patientName.includes(searchTerm) || patientService.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});

// Notifications System
function createNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 
                         type === 'error' ? 'exclamation-circle' : 
                         'info-circle'}"></i>
        <span>${message}</span>
    `;

    document.body.appendChild(notification);

    // Remove notification after 3 seconds
    setTimeout(() => {
        notification.classList.add('fade-out');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Action Buttons Handler
actionButtons.forEach(button => {
    button.addEventListener('click', () => {
        const patientName = button.closest('tr').querySelector('.patient-info span').textContent;
        createNotification(`Détails du patient ${patientName} en cours de chargement...`, 'info');
    });
});

// Stats Counter Animation
function animateValue(element, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        const value = Math.floor(progress * (end - start) + start);
        element.textContent = value;
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

// Animate stats on page load
document.addEventListener('DOMContentLoaded', () => {
    const statsNumbers = document.querySelectorAll('.card-info h2');
    statsNumbers.forEach(stat => {
        const finalValue = parseInt(stat.textContent);
        animateValue(stat, 0, finalValue, 1500);
    });
});

// Chart Data (using Chart.js if included)
if (typeof Chart !== 'undefined') {
    // Example chart configuration
    const ctx = document.getElementById('activitiesChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
                datasets: [{
                    label: 'Patients',
                    data: [12, 19, 15, 17, 14, 10, 8],
                    borderColor: '#4e73df',
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    }
}

// Date and Time Update
function updateDateTime() {
    const now = new Date();
    const dateTimeElement = document.querySelector('.datetime');
    if (dateTimeElement) {
        dateTimeElement.textContent = now.toLocaleString('fr-FR', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }
}

// Update date/time every minute
setInterval(updateDateTime, 60000);
updateDateTime();

// Add smooth scrolling to all links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });
});

// Form Validation (if needed)
const forms = document.querySelectorAll('form');
forms.forEach(form => {
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('error');
                createNotification(`Le champ ${field.getAttribute('name')} est requis`, 'error');
            } else {
                field.classList.remove('error');
            }
        });

        if (isValid) {
            // Handle form submission
            createNotification('Formulaire soumis avec succès', 'success');
        }
    });
});

// Add loading states to buttons
function addLoadingState(button) {
    button.disabled = true;
    const originalText = button.textContent;
    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Chargement...';
    return () => {
        button.disabled = false;
        button.textContent = originalText;
    };
}

// Example usage for action buttons
actionButtons.forEach(button => {
    button.addEventListener('click', () => {
        const removeLoading = addLoadingState(button);
        // Simulate API call
        setTimeout(removeLoading, 1500);
    });
});

// Handle window resize for responsive adjustments
let resizeTimer;
window.addEventListener('resize', () => {
    clearTimeout(resizeTimer);
    resizeTimer = setTimeout(() => {
        // Handle resize operations
        if (window.innerWidth > 992 && sidebar.classList.contains('active')) {
            sidebar.classList.remove('active');
        }
    }, 250);
});

// Add keyboard navigation
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        sidebar.classList.remove('active');
    }
});

// Initialize tooltips if needed
const tooltips = document.querySelectorAll('[data-tooltip]');
tooltips.forEach(tooltip => {
    tooltip.addEventListener('mouseenter', (e) => {
        const tip = document.createElement('div');
        tip.className = 'tooltip';
        tip.textContent = e.target.dataset.tooltip;
        document.body.appendChild(tip);

        const rect = e.target.getBoundingClientRect();
        tip.style.top = `${rect.top - tip.offsetHeight - 10}px`;
        tip.style.left = `${rect.left + (rect.width - tip.offsetWidth) / 2}px`;

        e.target.addEventListener('mouseleave', () => tip.remove());
    });
});
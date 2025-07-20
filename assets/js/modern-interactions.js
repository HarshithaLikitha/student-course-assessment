// Modern Course Assessment System JavaScript

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all interactive features
    initializeAnimations();
    initializeFormValidation();
    initializeTableFeatures();
    initializeNotifications();
});

// Animation and Interaction Functions
function initializeAnimations() {
    // Fade in animation for cards
    const cards = document.querySelectorAll('.nav-card, .form-container, .table-container');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        
        setTimeout(() => {
            card.style.transition = 'all 0.6s ease';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });

    // Parallax effect for hero section
    const hero = document.querySelector('.hero');
    if (hero) {
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const rate = scrolled * -0.5;
            hero.style.transform = `translateY(${rate}px)`;
        });
    }

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
}

// Form Validation and Enhancement
function initializeFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        const inputs = form.querySelectorAll('.form-input');
        
        inputs.forEach(input => {
            // Real-time validation
            input.addEventListener('input', function() {
                validateField(this);
            });
            
            // Enhanced focus effects
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
                validateField(this);
            });
        });
        
        // Form submission with loading state
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('input[type="submit"], button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="loading"></span> Processing...';
                
                // Re-enable after 3 seconds (in case of errors)
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = submitBtn.getAttribute('data-original-text') || 'Submit';
                }, 3000);
            }
        });
    });
}

function validateField(field) {
    const value = field.value.trim();
    const fieldType = field.type;
    const isRequired = field.hasAttribute('required');
    
    // Remove existing validation classes
    field.classList.remove('valid', 'invalid');
    
    // Check if field is empty and required
    if (isRequired && !value) {
        field.classList.add('invalid');
        showFieldError(field, 'This field is required');
        return false;
    }
    
    // Validate based on field type
    switch (fieldType) {
        case 'email':
            if (value && !isValidEmail(value)) {
                field.classList.add('invalid');
                showFieldError(field, 'Please enter a valid email address');
                return false;
            }
            break;
        case 'number':
            const min = field.getAttribute('min');
            const max = field.getAttribute('max');
            const numValue = parseFloat(value);
            
            if (value && isNaN(numValue)) {
                field.classList.add('invalid');
                showFieldError(field, 'Please enter a valid number');
                return false;
            }
            
            if (min && numValue < parseFloat(min)) {
                field.classList.add('invalid');
                showFieldError(field, `Value must be at least ${min}`);
                return false;
            }
            
            if (max && numValue > parseFloat(max)) {
                field.classList.add('invalid');
                showFieldError(field, `Value must not exceed ${max}`);
                return false;
            }
            break;
    }
    
    // If we get here, field is valid
    if (value) {
        field.classList.add('valid');
        hideFieldError(field);
    }
    
    return true;
}

function isValidEmail(email) {
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return emailRegex.test(email);
}

function showFieldError(field, message) {
    hideFieldError(field); // Remove existing error
    
    const errorDiv = document.createElement('div');
    errorDiv.className = 'field-error';
    errorDiv.textContent = message;
    errorDiv.style.color = 'var(--danger-color)';
    errorDiv.style.fontSize = '0.875rem';
    errorDiv.style.marginTop = '0.25rem';
    
    field.parentElement.appendChild(errorDiv);
}

function hideFieldError(field) {
    const existingError = field.parentElement.querySelector('.field-error');
    if (existingError) {
        existingError.remove();
    }
}

// Table Enhancement Features
function initializeTableFeatures() {
    const tables = document.querySelectorAll('.data-table');
    
    tables.forEach(table => {
        // Add sorting functionality
        const headers = table.querySelectorAll('th');
        headers.forEach((header, index) => {
            if (header.textContent.trim()) {
                header.style.cursor = 'pointer';
                header.style.userSelect = 'none';
                header.addEventListener('click', () => sortTable(table, index));
                
                // Add sort indicator
                const sortIcon = document.createElement('span');
                sortIcon.className = 'sort-icon';
                sortIcon.innerHTML = ' ↕️';
                sortIcon.style.opacity = '0.5';
                header.appendChild(sortIcon);
            }
        });
        
        // Add row hover effects
        const rows = table.querySelectorAll('tbody tr');
        rows.forEach(row => {
            row.addEventListener('mouseenter', function() {
                this.style.backgroundColor = 'var(--bg-secondary)';
                this.style.transform = 'scale(1.01)';
            });
            
            row.addEventListener('mouseleave', function() {
                this.style.backgroundColor = '';
                this.style.transform = 'scale(1)';
            });
        });
    });
}

function sortTable(table, columnIndex) {
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    // Determine sort direction
    const header = table.querySelectorAll('th')[columnIndex];
    const currentDirection = header.getAttribute('data-sort-direction') || 'asc';
    const newDirection = currentDirection === 'asc' ? 'desc' : 'asc';
    
    // Clear all sort indicators
    table.querySelectorAll('th').forEach(th => {
        th.removeAttribute('data-sort-direction');
        const icon = th.querySelector('.sort-icon');
        if (icon) icon.innerHTML = ' ↕️';
    });
    
    // Set new sort direction
    header.setAttribute('data-sort-direction', newDirection);
    const icon = header.querySelector('.sort-icon');
    if (icon) icon.innerHTML = newDirection === 'asc' ? ' ↑' : ' ↓';
    
    // Sort rows
    rows.sort((a, b) => {
        const aValue = a.cells[columnIndex].textContent.trim();
        const bValue = b.cells[columnIndex].textContent.trim();
        
        // Try to parse as numbers
        const aNum = parseFloat(aValue);
        const bNum = parseFloat(bValue);
        
        if (!isNaN(aNum) && !isNaN(bNum)) {
            return newDirection === 'asc' ? aNum - bNum : bNum - aNum;
        } else {
            return newDirection === 'asc' 
                ? aValue.localeCompare(bValue)
                : bValue.localeCompare(aValue);
        }
    });
    
    // Reorder rows in DOM
    rows.forEach(row => tbody.appendChild(row));
    
    // Add animation
    rows.forEach((row, index) => {
        row.style.animation = `fadeInUp 0.3s ease ${index * 0.05}s both`;
    });
}

// Notification System
function initializeNotifications() {
    // Auto-hide success messages
    const successMessages = document.querySelectorAll('.alert-success');
    successMessages.forEach(message => {
        setTimeout(() => {
            fadeOut(message);
        }, 5000);
    });
    
    // Add close buttons to alerts
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        const closeBtn = document.createElement('button');
        closeBtn.innerHTML = '×';
        closeBtn.style.cssText = `
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            margin-left: auto;
            opacity: 0.7;
        `;
        closeBtn.addEventListener('click', () => fadeOut(alert));
        alert.appendChild(closeBtn);
    });
}

function fadeOut(element) {
    element.style.transition = 'opacity 0.3s ease, transform 0.3s ease';
    element.style.opacity = '0';
    element.style.transform = 'translateY(-10px)';
    
    setTimeout(() => {
        element.remove();
    }, 300);
}

// Utility Functions
function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `alert alert-${type}`;
    notification.innerHTML = `
        <span>${message}</span>
        <button onclick="fadeOut(this.parentElement)" style="background: none; border: none; font-size: 1.5rem; cursor: pointer; margin-left: auto; opacity: 0.7;">×</button>
    `;
    
    // Insert at top of main container
    const mainContainer = document.querySelector('.main-container');
    if (mainContainer) {
        mainContainer.insertBefore(notification, mainContainer.firstChild);
        
        // Auto-hide after 5 seconds
        setTimeout(() => fadeOut(notification), 5000);
    }
}

// Add CSS animations
const style = document.createElement('style');
style.textContent = `
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .form-input.valid {
        border-color: var(--success-color);
        box-shadow: 0 0 0 3px rgb(34 197 94 / 0.1);
    }
    
    .form-input.invalid {
        border-color: var(--danger-color);
        box-shadow: 0 0 0 3px rgb(239 68 68 / 0.1);
    }
    
    .form-group.focused .form-label {
        color: var(--primary-color);
    }
    
    .data-table th {
        transition: background-color 0.2s ease;
    }
    
    .data-table th:hover {
        background-color: var(--bg-tertiary);
    }
    
    .data-table tr {
        transition: all 0.2s ease;
    }
`;
document.head.appendChild(style);


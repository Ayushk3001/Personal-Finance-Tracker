// UI utilities

// Currency Formatter for Indian Rupees
function formatCurrency(amount, currency = 'INR') {
    if (!amount && amount !== 0) return '₹0.00';
    
    const num = parseFloat(amount);
    if (isNaN(num)) return '₹0.00';
    
    // Indian Rupee formatting
    if (currency === 'INR' || currency === '₹') {
        return '₹' + num.toLocaleString('en-IN', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }
    
    // Default formatting for other currencies
    return '$' + num.toLocaleString('en-US', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// Get currency from localStorage or default to INR
function getCurrency() {
    return localStorage.getItem('currency') || 'INR';
}

// Set currency
function setCurrency(currency) {
    localStorage.setItem('currency', currency);
    window.location.reload();
}

function openModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('active');
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('active');
    }
}

// Close modal on outside click
document.addEventListener('click', (e) => {
    if (e.target.classList.contains('modal')) {
        e.target.classList.remove('active');
    }
});

// Settings form
document.getElementById('settingsForm')?.addEventListener('submit', (e) => {
    e.preventDefault();
    
    const currency = document.getElementById('settings-currency').value;
    const timezone = document.getElementById('settings-timezone').value;
    
    localStorage.setItem('currency', currency);
    localStorage.setItem('timezone', timezone);
    
    alert('Settings saved successfully!');
});

// UI utilities
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
function loadSettings() {
    const currency = localStorage.getItem('currency') || 'INR';
    const timezone = localStorage.getItem('timezone') || 'Asia/Kolkata';
    const currencySelect = document.getElementById('settings-currency');
    const timezoneSelect = document.getElementById('settings-timezone');

    if (currencySelect) currencySelect.value = currency;
    if (timezoneSelect) timezoneSelect.value = timezone;

    if (!localStorage.getItem('currency')) localStorage.setItem('currency', currency);
    if (!localStorage.getItem('timezone')) localStorage.setItem('timezone', timezone);
}

document.getElementById('settingsForm')?.addEventListener('submit', (e) => {
    e.preventDefault();
    
    const currency = document.getElementById('settings-currency').value;
    const timezone = document.getElementById('settings-timezone').value;
    
    localStorage.setItem('currency', currency);
    localStorage.setItem('timezone', timezone);
    refreshCurrentView();
    
    alert('Settings saved successfully!');
});

loadSettings();

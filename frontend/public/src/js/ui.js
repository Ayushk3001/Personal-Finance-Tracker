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
document.getElementById('settingsForm')?.addEventListener('submit', (e) => {
    e.preventDefault();
    
    const currency = document.getElementById('settings-currency').value;
    const timezone = document.getElementById('settings-timezone').value;
    
    localStorage.setItem('currency', currency);
    localStorage.setItem('timezone', timezone);
    
    alert('Settings saved successfully!');
});

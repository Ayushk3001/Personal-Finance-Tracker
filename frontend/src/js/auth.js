// Authentication UI
function toggleForm() {
    document.getElementById('login-form').classList.toggle('hidden');
    document.getElementById('register-form').classList.toggle('hidden');
}

document.getElementById('loginForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();

    const email = document.getElementById('login-email').value;
    const password = document.getElementById('login-password').value;

    const response = await API.login(email, password);

    if (response?.success) {
        localStorage.setItem('authToken', response.data.token);
        localStorage.setItem('userId', response.data.userId);
        localStorage.setItem('username', response.data.username);
        API.token = response.data.token;

        showDashboard();
    } else {
        alert(response?.message || 'Login failed');
    }
});

document.getElementById('registerForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();

    const userData = {
        username: document.getElementById('register-username').value,
        email: document.getElementById('register-email').value,
        password: document.getElementById('register-password').value,
        firstName: document.getElementById('register-firstName').value,
        lastName: document.getElementById('register-lastName').value
    };

    const response = await API.register(userData);

    if (response?.success) {
        alert('Registration successful! Please login.');
        toggleForm();
    } else {
        alert(response?.message || 'Registration failed');
    }
});

function showDashboard() {
    document.getElementById('auth-container').style.display = 'none';
    document.getElementById('dashboard').classList.remove('hidden');
    
    const username = localStorage.getItem('username') || 'User';
    document.getElementById('user-greeting').textContent = `Welcome, ${username}!`;
    
    loadDashboardData();
}

function logout() {
    if (confirm('Are you sure you want to logout?')) {
        localStorage.removeItem('authToken');
        localStorage.removeItem('userId');
        localStorage.removeItem('username');
        API.token = null;

        document.getElementById('auth-container').style.display = 'flex';
        document.getElementById('dashboard').classList.add('hidden');
        
        document.getElementById('loginForm').reset();
        document.getElementById('registerForm').reset();
    }
}

// Check if already logged in
window.addEventListener('load', () => {
    const token = localStorage.getItem('authToken');
    if (token) {
        API.token = token;
        showDashboard();
    }
});

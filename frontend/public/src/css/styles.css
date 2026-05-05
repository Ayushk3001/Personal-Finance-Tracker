:root {
    --primary: #4F46E5;
    --primary-dark: #4338CA;
    --primary-light: #6366F1;
    --secondary: #7C3AED;
    --success: #10B981;
    --danger: #EF4444;
    --warning: #F59E0B;
    --info: #3B82F6;
    
    --bg-primary: #FFFFFF;
    --bg-secondary: #F9FAFB;
    --bg-tertiary: #F3F4F6;
    
    --text-primary: #111827;
    --text-secondary: #6B7280;
    --text-light: #9CA3AF;
    
    --border-color: #E5E7EB;
    --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    
    --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    --radius-sm: 4px;
    --radius-md: 8px;
    --radius-lg: 12px;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html, body {
    height: 100%;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    background-color: var(--bg-secondary);
    color: var(--text-primary);
}

body {
    overflow-x: hidden;
}

/* Auth Container */
.auth-container {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    padding: 20px;
}

.auth-form {
    background: white;
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
    max-width: 450px;
    width: 100%;
    padding: 40px;
    animation: slideUp 0.5s ease;
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.auth-header {
    text-align: center;
    margin-bottom: 40px;
}

.auth-header h1 {
    font-size: 28px;
    color: var(--primary);
    margin-bottom: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.auth-header p {
    color: var(--text-secondary);
    font-size: 14px;
}

/* Forms */
.form-section {
    display: block;
}

.form-section.hidden {
    display: none;
}

.form-section h2 {
    font-size: 20px;
    margin-bottom: 24px;
    color: var(--text-primary);
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 8px;
    color: var(--text-primary);
}

.form-group input,
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid var(--border-color);
    border-radius: var(--radius-md);
    font-size: 14px;
    font-family: inherit;
    transition: var(--transition);
    background-color: var(--bg-primary);
    color: var(--text-primary);
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.form-group small {
    display: block;
    margin-top: 4px;
    font-size: 12px;
    color: var(--text-light);
}

.form-footer {
    text-align: center;
    margin-top: 24px;
    font-size: 14px;
    color: var(--text-secondary);
}

.form-footer a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
}

/* Buttons */
.btn {
    padding: 10px 20px;
    border: none;
    border-radius: var(--radius-md);
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-align: center;
    justify-content: center;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

.btn-primary {
    background-color: var(--primary);
    color: white;
}

.btn-primary:hover {
    background-color: var(--primary-dark);
    box-shadow: var(--shadow-md);
}

.btn-secondary {
    background-color: var(--bg-tertiary);
    color: var(--text-primary);
}

.btn-secondary:hover {
    background-color: var(--border-color);
}

.btn-block {
    width: 100%;
}

.btn-logout {
    background-color: var(--danger);
    color: white;
    width: 100%;
    margin-top: 16px;
}

.btn-logout:hover {
    background-color: #DC2626;
}

/* Cards */
.card {
    background-color: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 24px;
    box-shadow: var(--shadow-sm);
    transition: var(--transition);
    margin-bottom: 20px;
}

.card:hover {
    box-shadow: var(--shadow-md);
    border-color: var(--primary-light);
}

.card h3 {
    font-size: 18px;
    margin-bottom: 16px;
    color: var(--text-primary);
}

/* Responsive */
@media (max-width: 768px) {
    .auth-form {
        padding: 30px 20px;
    }
    
    .auth-header h1 {
        font-size: 24px;
    }
}

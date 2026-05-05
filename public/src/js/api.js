// API Configuration
const API_BASE_URL = '/api';

class API {
    static token = localStorage.getItem('authToken');

    static async request(endpoint, options = {}) {
        const url = `${API_BASE_URL}${endpoint}`;
        const headers = {
            'Content-Type': 'application/json',
            ...options.headers
        };

        if (this.token) {
            headers['Authorization'] = `Bearer ${this.token}`;
        }

        try {
            const response = await fetch(url, {
                ...options,
                headers
            });

            if (!response.ok) {
                if (response.status === 401) {
                    // Token expired, redirect to login
                    logout();
                    return null;
                }
                const errorData = await response.json().catch(() => null);
                return errorData || {
                    success: false,
                    message: `HTTP ${response.status}`
                };
            }

            return await response.json();
        } catch (error) {
            console.error('API Request Error:', error);
            return null;
        }
    }

    static async get(endpoint) {
        return this.request(endpoint, { method: 'GET' });
    }

    static async post(endpoint, data) {
        return this.request(endpoint, {
            method: 'POST',
            body: JSON.stringify(data)
        });
    }

    static async put(endpoint, data) {
        return this.request(endpoint, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
    }

    static async delete(endpoint) {
        return this.request(endpoint, { method: 'DELETE' });
    }

    // Auth endpoints
    static async register(userData) {
        return this.post('/auth/register', userData);
    }

    static async login(email, password) {
        return this.post('/auth/login', { email, password });
    }

    static async logout() {
        return this.post('/auth/logout', {});
    }

    static async changePassword(oldPassword, newPassword) {
        return this.post('/auth/change-password', { oldPassword, newPassword });
    }

    // Transactions endpoints
    static async createTransaction(data) {
        return this.post('/transactions/create', data);
    }

    static async getTransactions(filters = {}) {
        const query = new URLSearchParams(filters).toString();
        return this.get(`/transactions?${query}`);
    }

    static async getTransaction(id) {
        return this.get(`/transactions/${id}`);
    }

    static async updateTransaction(id, data) {
        return this.put(`/transactions/${id}`, data);
    }

    static async deleteTransaction(id) {
        return this.delete(`/transactions/${id}`);
    }

    static async getMonthlySummary(year, month) {
        return this.get(`/transactions/summary/${year}/${month}`);
    }

    static async getSpendingByCategory(startDate, endDate) {
        return this.get(`/transactions/spending?start=${startDate}&end=${endDate}`);
    }

    // Categories endpoints
    static async getCategories(type = null) {
        let url = '/categories';
        if (type) url += `?type=${type}`;
        return this.get(url);
    }

    static async createCategory(data) {
        return this.post('/categories/create', data);
    }

    static async updateCategory(id, data) {
        return this.put(`/categories/${id}`, data);
    }

    static async deleteCategory(id) {
        return this.delete(`/categories/${id}`);
    }

    // Budgets endpoints
    static async getBudgets() {
        return this.get('/budgets');
    }

    static async createBudget(data) {
        return this.post('/budgets/create', data);
    }

    static async updateBudget(id, data) {
        return this.put(`/budgets/${id}`, data);
    }

    static async deleteBudget(id) {
        return this.delete(`/budgets/${id}`);
    }

    // Savings Goals endpoints
    static async getGoals() {
        return this.get('/goals');
    }

    static async createGoal(data) {
        return this.post('/goals/create', data);
    }

    static async updateGoal(id, data) {
        return this.put(`/goals/${id}`, data);
    }

    static async deleteGoal(id) {
        return this.delete(`/goals/${id}`);
    }

    // Dashboard endpoints
    static async getDashboardData() {
        return this.get('/dashboard');
    }
}

// Dashboard functionality
let categoryChart, monthlyChart;

async function loadDashboardData() {
    const currentDate = new Date();
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth() + 1;

    // Load monthly summary
    const summaryResponse = await API.getMonthlySummary(year, month);
    if (summaryResponse?.data) {
        updateSummaryCards(summaryResponse.data);
    }

    // Load spending by category
    const startDate = `${year}-${String(month).padStart(2, '0')}-01`;
    const endDate = new Date(year, month, 0).toISOString().split('T')[0];
    
    const categoryResponse = await API.getSpendingByCategory(startDate, endDate);
    if (categoryResponse?.data) {
        updateCategoryChart(categoryResponse.data);
    }

    // Load recent transactions
    const transactionsResponse = await API.getTransactions({ limit: 5 });
    if (transactionsResponse?.data) {
        displayRecentTransactions(transactionsResponse.data);
    }

    // Load savings goals
    loadSavingsGoals();
}

function updateSummaryCards(data) {
    let totalIncome = 0;
    let totalExpenses = 0;

    data.forEach(item => {
        if (item.type === 'income') {
            totalIncome += parseFloat(item.total) || 0;
        } else {
            totalExpenses += parseFloat(item.total) || 0;
        }
    });

    const netBalance = totalIncome - totalExpenses;

    document.getElementById('total-income').textContent = formatCurrency(totalIncome);
    document.getElementById('total-expenses').textContent = formatCurrency(totalExpenses);
    document.getElementById('net-balance').textContent = formatCurrency(netBalance);
}

function updateCategoryChart(data) {
    const ctx = document.getElementById('categoryChart')?.getContext('2d');
    if (!ctx) return;

    const labels = data.map(item => item.name || 'Other');
    const values = data.map(item => parseFloat(item.total) || 0);
    const colors = data.map(item => item.color || '#4F46E5');

    if (categoryChart) {
        categoryChart.destroy();
    }

    categoryChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels,
            datasets: [{
                data: values,
                backgroundColor: colors,
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: { size: 12 },
                        padding: 15
                    }
                },
                tooltip: {
                    callbacks: {
                        label: (context) => {
                            return formatCurrency(context.parsed);
                        }
                    }
                }
            }
        }
    });
}

function displayRecentTransactions(transactions) {
    const container = document.getElementById('recent-transactions');
    
    if (transactions.length === 0) {
        container.innerHTML = '<p class="empty-state">No transactions yet</p>';
        return;
    }

    container.innerHTML = transactions.map(trans => `
        <div class="transaction-item">
            <div class="transaction-info">
                <div class="transaction-icon">
                    <i class="fas fa-${trans.type === 'income' ? 'arrow-down' : 'arrow-up'}"></i>
                </div>
                <div class="transaction-details">
                    <div class="transaction-description">${trans.description}</div>
                    <div class="transaction-category">${trans.category_name}</div>
                </div>
            </div>
            <div class="transaction-amount ${trans.type}">
                ${trans.type === 'income' ? '+' : '-'}${formatCurrency(trans.amount)}
            </div>
        </div>
    `).join('');
}

async function loadSavingsGoals() {
    const response = await API.getGoals();
    if (response?.data && response.data.length > 0) {
        const totalTarget = response.data.reduce((sum, goal) => sum + parseFloat(goal.target_amount), 0);
        const totalCurrent = response.data.reduce((sum, goal) => sum + parseFloat(goal.current_amount), 0);
        
        const percentage = Math.round((totalCurrent / totalTarget) * 100);
        document.getElementById('savings-progress').textContent = `${percentage}%`;
    }
}

function formatCurrency(amount) {
    if (!amount && amount !== 0) return '₹0.00';
    
    const num = parseFloat(amount);
    if (isNaN(num)) return '₹0.00';
    
    // Always format as Indian Rupees
    return '₹' + num.toLocaleString('en-IN', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

// Navigation
document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', (e) => {
        e.preventDefault();
        
        const view = item.dataset.view;
        
        // Update active nav item
        document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
        item.classList.add('active');
        
        // Update active view
        document.querySelectorAll('.view').forEach(v => v.classList.remove('active'));
        document.getElementById(`view-${view}`)?.classList.add('active');
        
        // Close sidebar on mobile
        document.querySelector('.sidebar').classList.remove('active');
        
        // Load view-specific data
        if (view === 'transactions') {
            loadTransactions();
        } else if (view === 'budgets') {
            loadBudgets();
        } else if (view === 'goals') {
            loadGoals();
        } else if (view === 'categories') {
            loadCategories();
        }
    });
});

// Sidebar toggle
function toggleSidebar() {
    document.querySelector('.sidebar').classList.toggle('active');
}

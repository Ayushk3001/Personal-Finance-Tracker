<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personal Finance Tracker</title>
    <link rel="stylesheet" href="src/css/styles.css">
    <link rel="stylesheet" href="src/css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div id="app"></div>

    <!-- Login Modal -->
    <div id="auth-container" class="auth-container">
        <div class="auth-form">
            <div class="auth-header">
                <h1><i class="fas fa-wallet"></i> Finance Tracker</h1>
                <p>Manage your money smartly</p>
            </div>

            <!-- Login Form -->
            <div id="login-form" class="form-section">
                <h2>Login</h2>
                <form id="loginForm">
                    <div class="form-group">
                        <label for="login-email">Email</label>
                        <input type="email" id="login-email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="login-password">Password</label>
                        <input type="password" id="login-password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Login</button>
                </form>
                <p class="form-footer">Don't have an account? <a href="#" onclick="toggleForm()">Register</a></p>
            </div>

            <!-- Register Form -->
            <div id="register-form" class="form-section hidden">
                <h2>Create Account</h2>
                <form id="registerForm">
                    <div class="form-group">
                        <label for="register-username">Username</label>
                        <input type="text" id="register-username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="register-email">Email</label>
                        <input type="email" id="register-email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="register-firstName">First Name</label>
                        <input type="text" id="register-firstName" name="firstName" required>
                    </div>
                    <div class="form-group">
                        <label for="register-lastName">Last Name</label>
                        <input type="text" id="register-lastName" name="lastName">
                    </div>
                    <div class="form-group">
                        <label for="register-password">Password</label>
                        <input type="password" id="register-password" name="password" required>
                        <small>Min 8 chars, uppercase, number</small>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Register</button>
                </form>
                <p class="form-footer">Already have an account? <a href="#" onclick="toggleForm()">Login</a></p>
            </div>
        </div>
    </div>

    <!-- Main Dashboard (Hidden until authenticated) -->
    <div id="dashboard" class="dashboard hidden">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2><i class="fas fa-wallet"></i> Finance Tracker</h2>
                <button class="sidebar-close" onclick="toggleSidebar()">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <nav class="sidebar-nav">
                <a href="#" data-view="dashboard" class="nav-item active">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
                <a href="#" data-view="transactions" class="nav-item">
                    <i class="fas fa-exchange-alt"></i>
                    <span>Transactions</span>
                </a>
                <a href="#" data-view="budgets" class="nav-item">
                    <i class="fas fa-chart-pie"></i>
                    <span>Budgets</span>
                </a>
                <a href="#" data-view="goals" class="nav-item">
                    <i class="fas fa-target"></i>
                    <span>Savings Goals</span>
                </a>
                <a href="#" data-view="categories" class="nav-item">
                    <i class="fas fa-tags"></i>
                    <span>Categories</span>
                </a>
                <a href="#" data-view="settings" class="nav-item">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <button class="btn btn-logout" onclick="logout()">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Top Bar -->
            <div class="topbar">
                <button class="sidebar-toggle" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="topbar-right">
                    <span id="user-greeting" class="user-greeting"></span>
                    <img id="user-avatar" class="user-avatar" src="" alt="User">
                </div>
            </div>

            <!-- Content Area -->
            <div class="content">
                <!-- Dashboard View -->
                <div id="view-dashboard" class="view active">
                    <div class="page-header">
                        <h1>Dashboard</h1>
                        <button class="btn btn-primary" onclick="openAddTransactionModal()">
                            <i class="fas fa-plus"></i> Add Transaction
                        </button>
                    </div>

                    <!-- Summary Cards -->
                    <div class="summary-cards">
                        <div class="card">
                            <div class="card-icon income">
                                <i class="fas fa-arrow-down"></i>
                            </div>
                            <div class="card-content">
                                <p class="card-label">Total Income</p>
                                <h3 id="total-income" class="card-value">$0.00</h3>
                                <p class="card-period">This month</p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-icon expense">
                                <i class="fas fa-arrow-up"></i>
                            </div>
                            <div class="card-content">
                                <p class="card-label">Total Expenses</p>
                                <h3 id="total-expenses" class="card-value">$0.00</h3>
                                <p class="card-period">This month</p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-icon balance">
                                <i class="fas fa-wallet"></i>
                            </div>
                            <div class="card-content">
                                <p class="card-label">Net Balance</p>
                                <h3 id="net-balance" class="card-value">$0.00</h3>
                                <p class="card-period">This month</p>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-icon savings">
                                <i class="fas fa-piggy-bank"></i>
                            </div>
                            <div class="card-content">
                                <p class="card-label">Savings Goals</p>
                                <h3 id="savings-progress" class="card-value">0%</h3>
                                <p class="card-period">Overall progress</p>
                            </div>
                        </div>
                    </div>

                    <!-- Charts Section -->
                    <div class="charts-container">
                        <div class="chart-card">
                            <h3>Spending by Category</h3>
                            <canvas id="categoryChart"></canvas>
                        </div>

                        <div class="chart-card">
                            <h3>Income vs Expenses</h3>
                            <canvas id="monthlyChart"></canvas>
                        </div>
                    </div>

                    <!-- Recent Transactions -->
                    <div class="card">
                        <h3>Recent Transactions</h3>
                        <div id="recent-transactions" class="transactions-list">
                            <p class="empty-state">No transactions yet</p>
                        </div>
                    </div>
                </div>

                <!-- Transactions View -->
                <div id="view-transactions" class="view">
                    <div class="page-header">
                        <h1>Transactions</h1>
                        <button class="btn btn-primary" onclick="openAddTransactionModal()">
                            <i class="fas fa-plus"></i> Add Transaction
                        </button>
                    </div>
                    <div id="transactions-content" class="transactions-container"></div>
                </div>

                <!-- Budgets View -->
                <div id="view-budgets" class="view">
                    <div class="page-header">
                        <h1>Budgets</h1>
                        <button class="btn btn-primary" onclick="openAddBudgetModal()">
                            <i class="fas fa-plus"></i> Add Budget
                        </button>
                    </div>
                    <div id="budgets-content" class="budgets-container"></div>
                </div>

                <!-- Goals View -->
                <div id="view-goals" class="view">
                    <div class="page-header">
                        <h1>Savings Goals</h1>
                        <button class="btn btn-primary" onclick="openAddGoalModal()">
                            <i class="fas fa-plus"></i> Add Goal
                        </button>
                    </div>
                    <div id="goals-content" class="goals-container"></div>
                </div>

                <!-- Categories View -->
                <div id="view-categories" class="view">
                    <div class="page-header">
                        <h1>Categories</h1>
                        <button class="btn btn-primary" onclick="openAddCategoryModal()">
                            <i class="fas fa-plus"></i> Add Category
                        </button>
                    </div>
                    <div id="categories-content" class="categories-container"></div>
                </div>

                <!-- Settings View -->
                <div id="view-settings" class="view">
                    <div class="page-header">
                        <h1>Settings</h1>
                    </div>
                    <div class="card">
                        <h3>Account Settings</h3>
                        <form id="settingsForm">
                            <div class="form-group">
                                <label for="settings-currency">Currency</label>
                                <select id="settings-currency">
                                    <option value="USD">USD ($)</option>
                                    <option value="EUR">EUR (€)</option>
                                    <option value="GBP">GBP (£)</option>
                                    <option value="CAD">CAD ($)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="settings-timezone">Timezone</label>
                                <select id="settings-timezone">
                                    <option value="UTC">UTC</option>
                                    <option value="America/New_York">Eastern Time</option>
                                    <option value="America/Chicago">Central Time</option>
                                    <option value="America/Denver">Mountain Time</option>
                                    <option value="America/Los_Angeles">Pacific Time</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Save Settings</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modals -->
    <div id="transaction-modal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Add Transaction</h2>
                <button class="modal-close" onclick="closeModal('transaction-modal')">&times;</button>
            </div>
            <form id="transactionForm" class="modal-body">
                <div class="form-group">
                    <label for="trans-type">Type</label>
                    <select id="trans-type" required>
                        <option value="">Select Type</option>
                        <option value="income">Income</option>
                        <option value="expense">Expense</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="trans-category">Category</label>
                    <select id="trans-category" required></select>
                </div>
                <div class="form-group">
                    <label for="trans-amount">Amount</label>
                    <input type="number" id="trans-amount" step="0.01" min="0" required>
                </div>
                <div class="form-group">
                    <label for="trans-date">Date</label>
                    <input type="date" id="trans-date" required>
                </div>
                <div class="form-group">
                    <label for="trans-description">Description</label>
                    <input type="text" id="trans-description" required>
                </div>
                <div class="form-group">
                    <label for="trans-method">Payment Method</label>
                    <select id="trans-method">
                        <option value="">Select Method</option>
                        <option value="cash">Cash</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="debit_card">Debit Card</option>
                        <option value="bank_transfer">Bank Transfer</option>
                        <option value="digital_wallet">Digital Wallet</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="trans-notes">Notes</label>
                    <textarea id="trans-notes"></textarea>
                </div>
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeModal('transaction-modal')">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Transaction</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <script src="src/js/auth.js"></script>
    <script src="src/js/api.js"></script>
    <script src="src/js/dashboard.js"></script>
    <script src="src/js/transactions.js"></script>
    <script src="src/js/budgets.js"></script>
    <script src="src/js/goals.js"></script>
    <script src="src/js/categories.js"></script>
    <script src="src/js/ui.js"></script>
</body>
</html>

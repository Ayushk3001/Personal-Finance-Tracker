/* Dashboard Layout */
.dashboard {
    display: flex;
    height: 100vh;
    background-color: var(--bg-secondary);
}

.dashboard.hidden {
    display: none;
}

/* Sidebar */
.sidebar {
    width: 280px;
    background-color: var(--bg-primary);
    border-right: 1px solid var(--border-color);
    display: flex;
    flex-direction: column;
    position: relative;
    z-index: 1000;
    box-shadow: var(--shadow-sm);
}

.sidebar-header {
    padding: 24px;
    border-bottom: 1px solid var(--border-color);
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.sidebar-header h2 {
    font-size: 20px;
    color: var(--primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.sidebar-close {
    display: none;
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: var(--text-primary);
}

.sidebar-nav {
    flex: 1;
    padding: 16px 0;
    display: flex;
    flex-direction: column;
    overflow-y: auto;
}

.nav-item {
    padding: 12px 24px;
    color: var(--text-secondary);
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 12px;
    transition: var(--transition);
    font-size: 14px;
    font-weight: 500;
    border-left: 3px solid transparent;
}

.nav-item:hover {
    background-color: var(--bg-tertiary);
    color: var(--primary);
}

.nav-item.active {
    background-color: #F3F4F6;
    color: var(--primary);
    border-left-color: var(--primary);
}

.nav-item i {
    width: 20px;
    text-align: center;
}

.sidebar-footer {
    padding: 16px;
    border-top: 1px solid var(--border-color);
}

/* Main Content */
.main-content {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

/* Top Bar */
.topbar {
    background-color: var(--bg-primary);
    border-bottom: 1px solid var(--border-color);
    padding: 16px 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: var(--shadow-sm);
}

.sidebar-toggle {
    display: none;
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: var(--text-primary);
    margin-right: 16px;
}

.topbar-right {
    display: flex;
    align-items: center;
    gap: 16px;
}

.user-greeting {
    font-size: 14px;
    color: var(--text-secondary);
    font-weight: 500;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background-color: var(--primary);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
}

/* Content Area */
.content {
    flex: 1;
    overflow-y: auto;
    padding: 32px;
}

.view {
    display: none;
    animation: fadeIn 0.3s ease;
}

.view.active {
    display: block;
}

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
}

.page-header h1 {
    font-size: 28px;
    color: var(--text-primary);
    margin: 0;
}

/* Summary Cards */
.summary-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.card {
    display: flex;
    gap: 16px;
    align-items: flex-start;
}

.card-icon {
    width: 60px;
    height: 60px;
    border-radius: var(--radius-lg);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: white;
    flex-shrink: 0;
}

.card-icon.income {
    background: linear-gradient(135deg, #10B981, #059669);
}

.card-icon.expense {
    background: linear-gradient(135deg, #EF4444, #DC2626);
}

.card-icon.balance {
    background: linear-gradient(135deg, #3B82F6, #2563EB);
}

.card-icon.savings {
    background: linear-gradient(135deg, #F59E0B, #D97706);
}

.card-content {
    flex: 1;
}

.card-label {
    font-size: 12px;
    font-weight: 600;
    color: var(--text-secondary);
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

.card-value {
    font-size: 24px;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.card-period {
    font-size: 12px;
    color: var(--text-light);
}

/* Charts */
.charts-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    margin-bottom: 32px;
}

.chart-card {
    background-color: var(--bg-primary);
    border: 1px solid var(--border-color);
    border-radius: var(--radius-lg);
    padding: 24px;
    box-shadow: var(--shadow-sm);
}

.chart-card h3 {
    font-size: 16px;
    margin-bottom: 16px;
    color: var(--text-primary);
    font-weight: 600;
}

/* Transactions List */
.transactions-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.transaction-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px;
    background-color: var(--bg-tertiary);
    border-radius: var(--radius-md);
    transition: var(--transition);
}

.transaction-item:hover {
    background-color: #E5E7EB;
}

.transaction-info {
    display: flex;
    align-items: center;
    gap: 12px;
    flex: 1;
}

.transaction-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 18px;
    background-color: var(--primary);
    color: white;
}

.transaction-details {
    flex: 1;
}

.transaction-description {
    font-size: 14px;
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.transaction-category {
    font-size: 12px;
    color: var(--text-light);
}

.transaction-amount {
    font-size: 16px;
    font-weight: 600;
    text-align: right;
}

.transaction-amount.income {
    color: var(--success);
}

.transaction-amount.expense {
    color: var(--danger);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 40px 20px;
    color: var(--text-light);
    font-size: 14px;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 2000;
    align-items: center;
    justify-content: center;
    animation: fadeIn 0.3s ease;
}

.modal.active {
    display: flex;
}

.modal-content {
    background-color: var(--bg-primary);
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-lg);
    max-width: 500px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    animation: slideUp 0.3s ease;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px;
    border-bottom: 1px solid var(--border-color);
}

.modal-header h2 {
    font-size: 20px;
    color: var(--text-primary);
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: var(--text-secondary);
}

.modal-body {
    padding: 24px;
}

.modal-actions {
    display: flex;
    gap: 12px;
    justify-content: flex-end;
    padding-top: 24px;
    border-top: 1px solid var(--border-color);
}

.modal-actions .btn {
    flex: 1;
}

/* Responsive */
@media (max-width: 768px) {
    .sidebar {
        position: fixed;
        left: -280px;
        top: 0;
        height: 100vh;
        transition: var(--transition);
        z-index: 999;
    }

    .sidebar.active {
        left: 0;
    }

    .sidebar-close {
        display: block;
    }

    .sidebar-toggle {
        display: block;
    }

    .content {
        padding: 16px;
    }

    .page-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 16px;
    }

    .page-header h1 {
        font-size: 24px;
    }

    .summary-cards {
        grid-template-columns: 1fr;
    }

    .charts-container {
        grid-template-columns: 1fr;
    }

    .modal-content {
        width: 95%;
    }
}

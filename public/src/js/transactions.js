// Transaction management
async function loadTransactions() {
    const response = await API.getTransactions();
    
    if (!response?.data || response.data.length === 0) {
        document.getElementById('transactions-content').innerHTML = 
            '<p class="empty-state">No transactions found. Add one to get started!</p>';
        return;
    }

    const grouped = {};
    response.data.forEach(trans => {
        const date = trans.transaction_date;
        if (!grouped[date]) grouped[date] = [];
        grouped[date].push(trans);
    });

    let html = '';
    Object.entries(grouped).forEach(([date, transactions]) => {
        html += `
            <div class="card">
                <h4 style="margin-bottom: 16px; color: var(--text-secondary); font-size: 12px; text-transform: uppercase; font-weight: 600;">${formatDate(date)}</h4>
                <div class="transactions-list">
                    ${transactions.map(trans => `
                        <div class="transaction-item">
                            <div class="transaction-info">
                                <div class="transaction-icon" style="background: ${trans.color}">
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
                    `).join('')}
                </div>
            </div>
        `;
    });

    document.getElementById('transactions-content').innerHTML = html;
}

function openAddTransactionModal() {
    // Load categories
    loadCategoriesForModal();
    document.getElementById('trans-date').valueAsDate = new Date();
    openModal('transaction-modal');
}

async function loadCategoriesForModal() {
    const response = await API.getCategories();
    
    if (!response?.data) return;

    const incomeCategories = response.data.filter(c => c.type === 'income');
    const expenseCategories = response.data.filter(c => c.type === 'expense');

    document.getElementById('trans-category').innerHTML = `
        <optgroup label="Income">
            ${incomeCategories.map(c => `<option value="${c.id}">${c.name}</option>`).join('')}
        </optgroup>
        <optgroup label="Expenses">
            ${expenseCategories.map(c => `<option value="${c.id}">${c.name}</option>`).join('')}
        </optgroup>
    `;
}

document.getElementById('transactionForm')?.addEventListener('submit', async (e) => {
    e.preventDefault();

    const data = {
        category_id: document.getElementById('trans-category').value,
        description: document.getElementById('trans-description').value,
        amount: parseFloat(document.getElementById('trans-amount').value),
        transaction_date: document.getElementById('trans-date').value,
        type: document.getElementById('trans-type').value,
        payment_method: document.getElementById('trans-method').value || null,
        notes: document.getElementById('trans-notes').value || null
    };

    const response = await API.createTransaction(data);

    if (response?.success) {
        closeModal('transaction-modal');
        loadTransactions();
        alert('Transaction added successfully!');
    } else {
        alert(response?.message || 'Failed to add transaction');
    }
});

function formatDate(dateString) {
    return formatDisplayDate(dateString);
}

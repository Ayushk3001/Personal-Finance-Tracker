// Budgets management
async function loadBudgets() {
    const response = await API.getBudgets();

    if (!response?.data || response.data.length === 0) {
        document.getElementById('budgets-content').innerHTML = 
            '<p class="empty-state">No budgets created. Create one to track spending!</p>';
        return;
    }

    const html = response.data.map(budget => {
        const percentage = Math.round((budget.spent_amount / budget.limit_amount) * 100);
        const status = percentage > 100 ? 'exceeded' : percentage > 80 ? 'warning' : 'ok';

        return `
            <div class="card">
                <div style="display: flex; justify-content: space-between; margin-bottom: 16px;">
                    <div>
                        <h4 style="margin: 0 0 4px 0; color: var(--text-primary); font-size: 16px;">${budget.name}</h4>
                        <p style="margin: 0; font-size: 12px; color: var(--text-light);">${budget.category_name || 'All Categories'}</p>
                    </div>
                    <div style="text-align: right;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: var(--text-primary);">${formatCurrency(budget.spent_amount)} / ${formatCurrency(budget.limit_amount)}</p>
                        <p style="margin: 0; font-size: 12px; color: var(--text-light);">${percentage}%</p>
                    </div>
                </div>
                <div style="background: #E5E7EB; height: 8px; border-radius: 4px; overflow: hidden;">
                    <div style="background: ${status === 'exceeded' ? '#EF4444' : status === 'warning' ? '#F59E0B' : '#10B981'}; height: 100%; width: ${Math.min(percentage, 100)}%; transition: all 0.3s ease;"></div>
                </div>
                <p style="margin: 12px 0 0 0; font-size: 12px; color: var(--text-light);">Period: ${budget.period}</p>
            </div>
        `;
    }).join('');

    document.getElementById('budgets-content').innerHTML = html;
}

function openAddBudgetModal() {
    openModal('budget-modal');
}

// Categories management
async function loadCategories() {
    const response = await API.getCategories();

    if (!response?.data || response.data.length === 0) {
        document.getElementById('categories-content').innerHTML = 
            '<p class="empty-state">No categories found.</p>';
        return;
    }

    const incomeCategories = response.data.filter(c => c.type === 'income');
    const expenseCategories = response.data.filter(c => c.type === 'expense');

    let html = '';

    if (incomeCategories.length > 0) {
        html += `
            <div class="card">
                <h3>Income Categories</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 12px;">
                    ${incomeCategories.map(cat => `
                        <div style="padding: 12px; background: var(--bg-tertiary); border-radius: 8px; border-left: 4px solid ${cat.color};">
                            <p style="margin: 0 0 4px 0; font-weight: 600; color: var(--text-primary);">${cat.name}</p>
                            <p style="margin: 0; font-size: 12px; color: var(--text-light);">${cat.description || 'No description'}</p>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }

    if (expenseCategories.length > 0) {
        html += `
            <div class="card">
                <h3>Expense Categories</h3>
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 12px;">
                    ${expenseCategories.map(cat => `
                        <div style="padding: 12px; background: var(--bg-tertiary); border-radius: 8px; border-left: 4px solid ${cat.color};">
                            <p style="margin: 0 0 4px 0; font-weight: 600; color: var(--text-primary);">${cat.name}</p>
                            <p style="margin: 0; font-size: 12px; color: var(--text-light);">${cat.description || 'No description'}</p>
                        </div>
                    `).join('')}
                </div>
            </div>
        `;
    }

    document.getElementById('categories-content').innerHTML = html;
}

function openAddCategoryModal() {
    openModal('category-modal');
}

// Savings goals management
async function loadGoals() {
    const response = await API.getGoals();

    if (!response?.data || response.data.length === 0) {
        document.getElementById('goals-content').innerHTML = 
            '<p class="empty-state">No savings goals yet. Create one to start saving!</p>';
        return;
    }

    const html = response.data.map(goal => {
        const percentage = Math.round((goal.current_amount / goal.target_amount) * 100);
        const daysLeft = goal.target_date ? 
            Math.ceil((new Date(goal.target_date) - new Date()) / (1000 * 60 * 60 * 24)) : null;

        return `
            <div class="card">
                <div style="display: flex; justify-content: space-between; margin-bottom: 16px;">
                    <div>
                        <h4 style="margin: 0 0 4px 0; color: var(--text-primary); font-size: 16px;">${goal.name}</h4>
                        <p style="margin: 0; font-size: 12px; color: var(--text-light);">${goal.description || 'No description'}</p>
                    </div>
                    <div style="text-align: right;">
                        <p style="margin: 0 0 4px 0; font-size: 14px; font-weight: 600; color: var(--text-primary);">${formatCurrency(goal.current_amount)} / ${formatCurrency(goal.target_amount)}</p>
                        <p style="margin: 0; font-size: 12px; color: var(--text-light);">${percentage}%</p>
                    </div>
                </div>
                <div style="background: #E5E7EB; height: 8px; border-radius: 4px; overflow: hidden;">
                    <div style="background: linear-gradient(90deg, #4F46E5, #7C3AED); height: 100%; width: ${Math.min(percentage, 100)}%; transition: all 0.3s ease;"></div>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 12px; font-size: 12px; color: var(--text-light);">
                    <span>Priority: <strong style="color: var(--text-primary); text-transform: capitalize;">${goal.priority}</strong></span>
                    ${daysLeft !== null ? `<span>${daysLeft > 0 ? daysLeft + ' days left' : 'Target date passed'}</span>` : ''}
                </div>
            </div>
        `;
    }).join('');

    document.getElementById('goals-content').innerHTML = html;
}

function openAddGoalModal() {
    openModal('goal-modal');
}

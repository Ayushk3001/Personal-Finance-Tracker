-- Insert sample transactions for test user (id=1)
-- Note: Adjust dates to be recent

-- Sample Income Transactions
INSERT INTO transactions (user_id, category_id, description, amount, transaction_date, type, payment_method, notes, created_at) 
SELECT 1, c.id, 'Monthly Salary', 50000.00, DATE_SUB(CURDATE(), INTERVAL 5 DAY), 'income', 'bank_transfer', 'May 2026 Salary', NOW()
FROM categories c WHERE c.user_id = 1 AND c.name = 'Salary' AND c.type = 'income' LIMIT 1;

INSERT INTO transactions (user_id, category_id, description, amount, transaction_date, type, payment_method, notes, created_at) 
SELECT 1, c.id, 'Freelance Project', 15000.00, DATE_SUB(CURDATE(), INTERVAL 10 DAY), 'income', 'bank_transfer', 'Web Development Project', NOW()
FROM categories c WHERE c.user_id = 1 AND c.name = 'Freelance' AND c.type = 'income' LIMIT 1;

-- Sample Expense Transactions - Food & Dining
INSERT INTO transactions (user_id, category_id, description, amount, transaction_date, type, payment_method, notes, created_at) 
SELECT 1, c.id, 'Restaurant Lunch', 450.00, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'expense', 'credit_card', 'Lunch at Taj Restaurant', NOW()
FROM categories c WHERE c.user_id = 1 AND c.name = 'Food & Dining' AND c.type = 'expense' LIMIT 1;

INSERT INTO transactions (user_id, category_id, description, amount, transaction_date, type, payment_method, notes, created_at) 
SELECT 1, c.id, 'Grocery Shopping', 1200.00, DATE_SUB(CURDATE(), INTERVAL 1 DAY), 'expense', 'debit_card', 'Weekly groceries', NOW()
FROM categories c WHERE c.user_id = 1 AND c.name = 'Food & Dining' AND c.type = 'expense' LIMIT 1;

INSERT INTO transactions (user_id, category_id, description, amount, transaction_date, type, payment_method, notes, created_at) 
SELECT 1, c.id, 'Coffee', 120.00, CURDATE(), 'expense', 'cash', 'Morning coffee', NOW()
FROM categories c WHERE c.user_id = 1 AND c.name = 'Food & Dining' AND c.type = 'expense' LIMIT 1;

-- Sample Expense Transactions - Transportation
INSERT INTO transactions (user_id, category_id, description, amount, transaction_date, type, payment_method, notes, created_at) 
SELECT 1, c.id, 'Uber/Ola Ride', 350.00, DATE_SUB(CURDATE(), INTERVAL 3 DAY), 'expense', 'app_payment', 'Office commute', NOW()
FROM categories c WHERE c.user_id = 1 AND c.name = 'Transportation' AND c.type = 'expense' LIMIT 1;

INSERT INTO transactions (user_id, category_id, description, amount, transaction_date, type, payment_method, notes, created_at) 
SELECT 1, c.id, 'Petrol', 2000.00, DATE_SUB(CURDATE(), INTERVAL 5 DAY), 'expense', 'credit_card', 'Car fuel', NOW()
FROM categories c WHERE c.user_id = 1 AND c.name = 'Transportation' AND c.type = 'expense' LIMIT 1;

-- Sample Expense Transactions - Shopping
INSERT INTO transactions (user_id, category_id, description, amount, transaction_date, type, payment_method, notes, created_at) 
SELECT 1, c.id, 'Clothes Shopping', 3500.00, DATE_SUB(CURDATE(), INTERVAL 7 DAY), 'expense', 'credit_card', 'Summer collection from mall', NOW()
FROM categories c WHERE c.user_id = 1 AND c.name = 'Shopping' AND c.type = 'expense' LIMIT 1;

INSERT INTO transactions (user_id, category_id, description, amount, transaction_date, type, payment_method, notes, created_at) 
SELECT 1, c.id, 'Electronics', 8500.00, DATE_SUB(CURDATE(), INTERVAL 8 DAY), 'expense', 'debit_card', 'Smartphone accessory', NOW()
FROM categories c WHERE c.user_id = 1 AND c.name = 'Shopping' AND c.type = 'expense' LIMIT 1;

-- Sample Expense Transactions - Entertainment
INSERT INTO transactions (user_id, category_id, description, amount, transaction_date, type, payment_method, notes, created_at) 
SELECT 1, c.id, 'Movie Tickets', 400.00, DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'expense', 'credit_card', 'Cinema - 2 tickets', NOW()
FROM categories c WHERE c.user_id = 1 AND c.name = 'Entertainment' AND c.type = 'expense' LIMIT 1;

INSERT INTO transactions (user_id, category_id, description, amount, transaction_date, type, payment_method, notes, created_at) 
SELECT 1, c.id, 'Gaming', 1000.00, DATE_SUB(CURDATE(), INTERVAL 4 DAY), 'expense', 'credit_card', 'In-game purchase', NOW()
FROM categories c WHERE c.user_id = 1 AND c.name = 'Entertainment' AND c.type = 'expense' LIMIT 1;

-- Sample Expense Transactions - Utilities
INSERT INTO transactions (user_id, category_id, description, amount, transaction_date, type, payment_method, notes, created_at) 
SELECT 1, c.id, 'Electricity Bill', 1800.00, DATE_SUB(CURDATE(), INTERVAL 6 DAY), 'expense', 'bank_transfer', 'April 2026 bill', NOW()
FROM categories c WHERE c.user_id = 1 AND c.name = 'Utilities' AND c.type = 'expense' LIMIT 1;

INSERT INTO transactions (user_id, category_id, description, amount, transaction_date, type, payment_method, notes, created_at) 
SELECT 1, c.id, 'Internet Bill', 599.00, DATE_SUB(CURDATE(), INTERVAL 6 DAY), 'expense', 'auto_debit', 'Monthly internet', NOW()
FROM categories c WHERE c.user_id = 1 AND c.name = 'Utilities' AND c.type = 'expense' LIMIT 1;

-- Sample Expense Transactions - Health
INSERT INTO transactions (user_id, category_id, description, amount, transaction_date, type, payment_method, notes, created_at) 
SELECT 1, c.id, 'Pharmacy', 850.00, DATE_SUB(CURDATE(), INTERVAL 3 DAY), 'expense', 'credit_card', 'Monthly medicines', NOW()
FROM categories c WHERE c.user_id = 1 AND c.name = 'Health & Medical' AND c.type = 'expense' LIMIT 1;

INSERT INTO transactions (user_id, category_id, description, amount, transaction_date, type, payment_method, notes, created_at) 
SELECT 1, c.id, 'Gym Membership', 500.00, DATE_SUB(CURDATE(), INTERVAL 5 DAY), 'expense', 'auto_debit', 'Monthly gym fee', NOW()
FROM categories c WHERE c.user_id = 1 AND c.name = 'Health & Medical' AND c.type = 'expense' LIMIT 1;

-- Sample Expense Transactions - Education
INSERT INTO transactions (user_id, category_id, description, amount, transaction_date, type, payment_method, notes, created_at) 
SELECT 1, c.id, 'Online Course', 2999.00, DATE_SUB(CURDATE(), INTERVAL 10 DAY), 'expense', 'credit_card', 'Python Programming Course', NOW()
FROM categories c WHERE c.user_id = 1 AND c.name = 'Education' AND c.type = 'expense' LIMIT 1;

-- Sample Expense Transactions - Subscriptions
INSERT INTO transactions (user_id, category_id, description, amount, transaction_date, type, payment_method, notes, created_at) 
SELECT 1, c.id, 'Netflix Subscription', 199.00, DATE_SUB(CURDATE(), INTERVAL 9 DAY), 'expense', 'credit_card', 'Monthly plan', NOW()
FROM categories c WHERE c.user_id = 1 AND c.name = 'Subscriptions' AND c.type = 'expense' LIMIT 1;

INSERT INTO transactions (user_id, category_id, description, amount, transaction_date, type, payment_method, notes, created_at) 
SELECT 1, c.id, 'Cloud Storage', 299.00, DATE_SUB(CURDATE(), INTERVAL 8 DAY), 'expense', 'credit_card', '200GB cloud storage', NOW()
FROM categories c WHERE c.user_id = 1 AND c.name = 'Subscriptions' AND c.type = 'expense' LIMIT 1;

-- Create sample budget for May 2026
INSERT INTO budgets (user_id, category_id, name, limit_amount, spent_amount, period, start_date, end_date, alert_threshold, is_active, created_at)
SELECT 1, c.id, 'May Food Budget', 10000.00, 1770.00, 'monthly', DATE_SUB(CURDATE(), INTERVAL 5 DAY), DATE_ADD(CURDATE(), INTERVAL 25 DAY), 80, TRUE, NOW()
FROM categories c WHERE c.user_id = 1 AND c.name = 'Food & Dining' AND c.type = 'expense' LIMIT 1;

INSERT INTO budgets (user_id, category_id, name, limit_amount, spent_amount, period, start_date, end_date, alert_threshold, is_active, created_at)
SELECT 1, c.id, 'May Transportation Budget', 5000.00, 2350.00, 'monthly', DATE_SUB(CURDATE(), INTERVAL 5 DAY), DATE_ADD(CURDATE(), INTERVAL 25 DAY), 75, TRUE, NOW()
FROM categories c WHERE c.user_id = 1 AND c.name = 'Transportation' AND c.type = 'expense' LIMIT 1;

-- Create sample savings goals
INSERT INTO savings_goals (user_id, name, description, target_amount, current_amount, target_date, priority, category, color, icon, is_active, created_at)
VALUES 
(1, 'Laptop Fund', 'Save for a new laptop', 100000.00, 35000.00, DATE_ADD(CURDATE(), INTERVAL 180 DAY), 'high', 'electronics', 'laptop', '#3B82F6', TRUE, NOW()),
(1, 'Vacation to Goa', 'Plan a beach vacation', 75000.00, 15000.00, DATE_ADD(CURDATE(), INTERVAL 365 DAY), 'medium', 'travel', 'plane', '#10B981', TRUE, NOW()),
(1, 'Emergency Fund', 'Build emergency savings', 200000.00, 85000.00, DATE_ADD(CURDATE(), INTERVAL 730 DAY), 'high', 'savings', 'shield', '#F59E0B', TRUE, NOW());

# Personal Finance Tracker - Quick Start Guide

## 🚀 Overview

Welcome to **Personal Finance Tracker** - a comprehensive personal finance management application built with PHP, MySQL, and JavaScript. This app helps you track income, expenses, budgets, and savings goals with an intuitive, responsive interface.

### ✨ Key Features

- **Dashboard**: Real-time overview of your financial status
- **Transactions**: Add, edit, and track income/expense transactions
- **Budgets**: Create monthly/yearly budgets and monitor spending
- **Savings Goals**: Set financial targets with progress tracking
- **Categories**: Organize transactions by customizable categories
- **Charts & Analytics**: Visual representation of spending patterns
- **Multi-currency**: Support for Indian Rupees (₹) and other currencies
- **Secure Authentication**: Bcrypt password hashing and token-based sessions

---

## 🔧 Quick Setup (3 Minutes)

### For macOS/Linux Users

```bash
# 1. Clone or extract the project
cd ~/Desktop/Personal\ Finance\ Tracker

# 2. Create .env file
cp .env.example .env

# 3. Create database
mysql -u root -ppassword < backend/config/Database.sql

# 4. Load sample data (optional)
mysql -u root -ppassword personal_finance_tracker < SAMPLE_DATA.sql

# 5. Start server
php -S localhost:8000 router.php

# 6. Open browser
# Visit: http://localhost:8000/frontend/public/
```

### For Windows 10 Users

⚠️ **See [SETUP_WINDOWS.md](SETUP_WINDOWS.md) for detailed step-by-step instructions**

Quick Summary:
1. Install PHP 8.x and MySQL 8.x
2. Add PHP to Windows PATH
3. Create `.env` file with database credentials
4. Create database: `mysql -u root -ppassword < backend\config\Database.sql`
5. Start server: `php -S localhost:8000 router.php`
6. Access: `http://localhost:8000/frontend/public/`

---

## 📊 Sample Data

The app comes with pre-loaded sample data including:

- ✅ **18 Sample Transactions**: Income and expenses across all categories
- ✅ **2 Sample Budgets**: Food & Transportation budgets for May 2026
- ✅ **3 Savings Goals**: Laptop Fund, Vacation, and Emergency Fund
- ✅ **17 Default Categories**: Pre-configured expense and income categories

**Test Account:**
```
Email: test@example.com
Password: Test@1234
```

---

## 💰 Indian Currency (INR)

The app is configured to use Indian Rupees (₹) by default.

**Features:**
- All amounts displayed with ₹ symbol
- Indian number formatting (e.g., ₹50,000.00)
- Easy currency switching in settings

**Sample Transactions (in INR):**
- Monthly Salary: ₹50,000
- Freelance Income: ₹15,000
- Groceries: ₹1,200
- Petrol: ₹2,000
- Shopping: ₹3,500
- And more...

---

## 📱 Using the Application

### 1. Login/Registration

**Create Account:**
- Click "Register" on login screen
- Fill in username, email, password, first name, and last name
- Account created with default categories

**Login:**
- Enter your email and password
- Access dashboard immediately after successful login

### 2. Dashboard Overview

The dashboard displays:
- **Total Income**: Monthly income summary
- **Total Expenses**: Monthly expense summary  
- **Net Balance**: Income minus expenses
- **Savings Goals**: Overall progress percentage
- **Spending by Category**: Doughnut chart visualization
- **Income vs Expenses**: Line chart comparison
- **Recent Transactions**: Last 5 transactions list

### 3. Adding Transactions

1. Click **"+ Add Transaction"** button
2. Fill in:
   - Description (e.g., "Lunch at Restaurant")
   - Amount in INR
   - Category (e.g., "Food & Dining")
   - Type (Income or Expense)
   - Date
   - Payment method (optional)
   - Notes (optional)
3. Click "Create Transaction"
4. View in Recent Transactions immediately

### 4. Managing Budgets

1. Go to **"Budgets"** from sidebar
2. Create budget:
   - Select category to track
   - Set monthly limit
   - Alert threshold (%)
3. Monitor spending against limit
4. Visual progress bar shows budget status

### 5. Savings Goals

1. Go to **"Savings Goals"** from sidebar
2. Create goal:
   - Goal name (e.g., "Laptop Fund")
   - Target amount
   - Target date
   - Priority level
3. Track progress toward each goal
4. View overall savings progress on dashboard

### 6. Categories

1. Go to **"Categories"** from sidebar
2. View all active categories
3. Create new category:
   - Name
   - Type (Income/Expense)
   - Color
   - Icon
4. Edit or deactivate categories

---

## 🔐 Security Features

- **Password Security**: Bcrypt hashing with cost factor 12
- **Token Authentication**: Secure Bearer tokens for API access
- **Session Management**: Automatic token expiration
- **Input Validation**: Server-side and client-side validation
- **SQL Injection Prevention**: Prepared statements for all queries
- **CSRF Protection**: Cross-site request forgery protection
- **XSS Prevention**: Output escaping and sanitization

---

## 📁 Project Structure

```
Personal Finance Tracker/
├── backend/
│   ├── config/
│   │   ├── Database.php          # PDO connection
│   │   ├── Database.sql          # MySQL schema
│   │   ├── env.php               # Environment loader
│   │   └── Autoloader.php        # PSR-0 autoloader
│   └── src/
│       ├── api/                  # RESTful endpoints
│       ├── classes/              # Business logic
│       └── utils/                # Helpers
├── frontend/
│   ├── public/
│   │   ├── index.html            # SPA shell
│   │   └── src/                  # CSS & JS assets
│   └── src/                      # Source files
├── docs/                         # Documentation
├── .env                          # Configuration (create from .env.example)
├── .env.example                  # Template
├── router.php                    # PHP development router
├── SAMPLE_DATA.sql               # Sample transactions
├── SETUP.md                      # General setup
└── SETUP_WINDOWS.md              # Windows 10 detailed guide
```

---

## 🐛 Troubleshooting

### Problem: Cannot Login
**Solution:**
- Verify email is correct
- Reset password if forgotten
- Check MySQL is running
- Clear browser cache (Ctrl+Shift+Del)

### Problem: "Cannot connect to database"
**Solution:**
- Verify MySQL service is running
- Check credentials in `.env` file
- Verify database `personal_finance_tracker` exists
- Command: `mysql -u root -ppassword -e "SHOW DATABASES;"`

### Problem: "Page shows blank or unstyled"
**Solution:**
- Verify CSS files copied to public: `frontend/public/src/css/`
- Use router: `php -S localhost:8000 router.php` (not just `php -S localhost:8000`)
- Clear browser cache
- Check browser console for 404 errors (F12)

### Problem: "Cannot find PHP executable"
**Solution (Windows):**
- Verify PHP added to PATH
- Restart Command Prompt after adding to PATH
- Test: Open cmd.exe, type `php -v`
- Should show PHP version info

### Problem: Transactions not showing
**Solution:**
- Verify sample data loaded: `mysql -u root -ppassword personal_finance_tracker -e "SELECT COUNT(*) FROM transactions;"`
- Create new transaction manually
- Refresh browser (F5)

---

## 🔄 API Endpoints

All API endpoints require Bearer token authentication.

### Authentication
```
POST   /api/auth/register           # Create account
POST   /api/auth/login              # Get token
POST   /api/auth/logout             # Invalidate token
POST   /api/auth/change-password    # Change password
```

### Transactions
```
GET    /api/transactions                      # List transactions
POST   /api/transactions/create                # Create transaction
GET    /api/transactions/{id}                  # Get one
PUT    /api/transactions/{id}                  # Update
DELETE /api/transactions/{id}                  # Delete
GET    /api/transactions/summary/{year}/{month} # Monthly summary
GET    /api/transactions/spending              # Category breakdown
```

### Other Resources
- **Categories**: `/api/categories` (CRUD)
- **Budgets**: `/api/budgets` (CRUD)
- **Savings Goals**: `/api/goals` (CRUD)

---

## 📊 Database Schema

### Main Tables
- **users**: User accounts and profile
- **categories**: Transaction categories
- **transactions**: Income/expense records
- **budgets**: Spending limits by category
- **savings_goals**: Financial targets
- **sessions**: Active tokens
- **audit_logs**: Activity tracking
- **default_categories**: Template categories

---

## 💡 Tips & Tricks

### 1. Use Categories Effectively
- Create custom categories for your spending patterns
- Assign meaningful icons and colors
- Review category spending in dashboard charts

### 2. Set Realistic Budgets
- Start with current average spending
- Adjust based on financial goals
- Use 80% alert threshold for warnings

### 3. Track Regular Expenses
- Mark recurring transactions (if enabled)
- Set up monthly subscriptions tracking
- Use notes for transaction details

### 4. Monitor Savings Progress
- Set multiple goals (short/long term)
- Update progress manually each month
- Track toward 3-month emergency fund minimum

### 5. Regular Reviews
- Check dashboard weekly for spending patterns
- Review budget status monthly
- Adjust categories/budgets as needed

---

## 🔄 Backup & Recovery

### Backup Database
```bash
# macOS/Linux
mysqldump -u root -ppassword personal_finance_tracker > backup_$(date +%Y%m%d_%H%M%S).sql

# Windows
mysqldump -u root -ppassword personal_finance_tracker > backup.sql
```

### Restore Database
```bash
mysql -u root -ppassword personal_finance_tracker < backup.sql
```

---

## 📈 Performance

### Browser Performance
- Charts cached in-memory
- Lazy loading for large transaction lists
- CSS variables for efficient styling
- Minified API responses

### Database Performance
- Indexes on user_id, transaction_date, category_id
- Query optimization for common reports
- Pagination for transaction lists
- Efficient aggregate queries

---

## 🚀 Deployment

For production deployment:

1. **Use proper web server**: Apache, Nginx, or IIS instead of PHP built-in server
2. **Secure environment**: 
   - Move `.env` outside web root
   - Set strong database passwords
   - Enable HTTPS/SSL
3. **Database**:
   - Regular automated backups
   - MySQL user with limited privileges
   - Database connection pooling
4. **Security**:
   - Rate limiting on API endpoints
   - Web Application Firewall (WAF)
   - Regular security updates

---

## 📞 Support

- **Documentation**: See README.md, DEVELOPMENT.md, API_REFERENCE.md
- **Issues**: Check browser console (F12) for JavaScript errors
- **Database Issues**: Check MySQL logs
- **API Debugging**: Use curl or Postman to test endpoints

---

## 📝 Version History

**Version 1.0.0** (May 2026)
- Initial release
- Full CRUD for all features
- Dashboard with charts
- Sample data included
- Indian Rupee support
- Windows 10 setup guide

---

## 📄 License

This project is provided as-is for personal finance tracking.

---

## 🎯 Next Steps

1. ✅ **Setup Complete**: You're ready to use the app!
2. 📝 **Create Transactions**: Add your income and expenses
3. 💼 **Set Budgets**: Define spending limits
4. 🎯 **Track Goals**: Monitor financial targets
5. 📊 **Analyze**: Review charts and reports weekly

---

## Quick Reference

| Feature | Location | Shortcut |
|---------|----------|----------|
| Add Transaction | Dashboard | "+ Add Transaction" button |
| View Budgets | Sidebar > Budgets | Click Budgets menu |
| Savings Goals | Sidebar > Savings Goals | Click Goals menu |
| Manage Categories | Sidebar > Categories | Click Categories menu |
| Settings | Sidebar > Settings | Click Settings menu |
| Logout | Bottom of Sidebar | Red Logout button |

---

**Happy Tracking!** 💰📈

For detailed Windows 10 setup instructions, see [SETUP_WINDOWS.md](SETUP_WINDOWS.md)

# Personal Finance Tracker - Quick Reference Card

## 🚀 Starting the App

### macOS/Linux
```bash
cd ~/Desktop/Personal\ Finance\ Tracker
php -S localhost:8000 router.php
# Open: http://localhost:8000/frontend/public/
```

### Windows 10
```cmd
cd C:\Users\YourUsername\Desktop\Personal Finance Tracker
php -S localhost:8000 router.php
REM Open: http://localhost:8000/frontend/public/
```

---

## 👤 Login Credentials

**Test Account:**
- Email: `test@example.com`
- Password: `Test@1234`

---

## 💰 Sample Data Included

**Transactions:** 18 (income + expenses)
**Budgets:** 2 (Food ₹10k, Transport ₹5k)
**Goals:** 3 (Laptop, Vacation, Emergency Fund)
**Income:** ₹50k salary + ₹15k freelance
**Expenses:** ₹24k across all categories

---

## 📊 Dashboard Features

| Feature | What It Shows |
|---------|---------------|
| Total Income | Monthly income sum |
| Total Expenses | Monthly expense sum |
| Net Balance | Income - Expenses |
| Savings Goals | Progress toward goals |
| Category Chart | Spending breakdown |
| Recent Transactions | Last 5 transactions |

---

## ➕ Add Transaction

1. Click **"+ Add Transaction"** button
2. Fill form:
   - Description (e.g., "Lunch")
   - Amount (in ₹)
   - Category (e.g., "Food & Dining")
   - Type (Income / Expense)
   - Date
3. Click **Create**

---

## 💼 Create Budget

1. Go to **Budgets** menu
2. Click **"+ Create Budget"**
3. Fill:
   - Category to track
   - Monthly limit (₹)
   - Alert percentage (80% recommended)
4. Click **Create**

---

## 🎯 Set Savings Goal

1. Go to **Savings Goals** menu
2. Click **"+ Create Goal"**
3. Fill:
   - Goal name
   - Target amount (₹)
   - Target date
   - Priority (High/Medium/Low)
4. Click **Create**

---

## 🏷️ Manage Categories

1. Go to **Categories** menu
2. View existing or create new
3. Can set:
   - Category name
   - Icon
   - Color
   - Type (Income/Expense)

---

## ⚙️ Settings

1. Go to **Settings** menu
2. Can update:
   - Currency (INR recommended)
   - Timezone
   - Profile info

---

## 🔓 Logout

- Click **"Logout"** button in bottom-left sidebar
- Confirms before logging out
- Returns to login page

---

## 💾 Backup Database

**macOS/Linux:**
```bash
mysqldump -u root -ppassword personal_finance_tracker > backup.sql
```

**Windows:**
```cmd
mysqldump -u root -ppassword personal_finance_tracker > backup.sql
```

---

## 🔧 Troubleshooting

### App won't load?
- Verify router running: `php -S localhost:8000 router.php`
- Not just: `php -S localhost:8000` (without router.php)

### Can't login?
- Verify MySQL is running
- Check email is correct
- Try test account: test@example.com / Test@1234

### CSS not showing?
- Clear browser cache: Ctrl+Shift+Del
- Verify files at: `frontend/public/src/css/`
- Refresh page: F5

### Database error?
- Verify `.env` credentials
- Check MySQL running
- Verify database exists: `mysql -u root -ppassword -e "SHOW DATABASES;"`

---

## 📊 Sample INR Amounts

- Monthly Salary: ₹50,000
- Freelance Income: ₹15,000
- Groceries: ₹1,200
- Petrol: ₹2,000
- Shopping: ₹3,500
- Movie: ₹400
- Gym: ₹500
- Internet: ₹599

---

## 🔐 Test Account Details

```
Username: testuser
Email: test@example.com
Password: Test@1234
First Name: Test
Last Name: User
```

---

## 📁 Key Files

- **Config**: `.env` (database credentials)
- **Database**: `backend/config/Database.sql`
- **Sample Data**: `SAMPLE_DATA.sql`
- **Frontend**: `frontend/public/index.html`
- **Router**: `router.php`

---

## 🌐 API Endpoints (for developers)

```
POST /api/auth/login             # Login
POST /api/auth/register          # Register
GET  /api/transactions           # List transactions
POST /api/transactions/create    # Add transaction
GET  /api/budgets                # List budgets
POST /api/budgets/create         # Add budget
GET  /api/goals                  # List goals
POST /api/goals/create           # Add goal
```

All endpoints require: `Authorization: Bearer {token}`

---

## 💡 Pro Tips

✅ Add transactions immediately to remember
✅ Set budgets based on current spending
✅ Review dashboard weekly
✅ Backup database monthly
✅ Use meaningful category names
✅ Set 80% budget alert threshold
✅ Track emergency fund separately

---

## 📞 Documentation

- **QUICKSTART.md** - Usage guide (400+ lines)
- **SETUP_WINDOWS.md** - Windows installation (400+ lines)
- **README.md** - Features overview
- **API_REFERENCE.md** - API documentation

---

## 🎯 Quick Checklist

- [ ] Start server: `php -S localhost:8000 router.php`
- [ ] Open: `http://localhost:8000/frontend/public/`
- [ ] Login: test@example.com / Test@1234
- [ ] View sample transactions
- [ ] View sample budget
- [ ] View savings goals
- [ ] Create new transaction
- [ ] Create new budget
- [ ] Set new savings goal

---

**Version 1.0.0 | May 2026**

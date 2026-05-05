# Windows 10 Setup Guide for Personal Finance Tracker

## Prerequisites

Before starting, ensure you have:
- **Windows 10** (Version 1909 or later)
- Administrator access to your computer
- Internet connection

---

## Step 1: Install Required Software

### 1.1 Install PHP 8.x

1. **Download PHP**
   - Visit https://www.php.net/downloads.php
   - Download "Windows PHP 8.x (64-bit) Zip" (latest version)
   - Example: `php-8.1.0-win32-vs16-x64.zip`

2. **Extract PHP**
   - Create a folder: `C:\php`
   - Extract the downloaded ZIP file to `C:\php`

3. **Add PHP to System PATH**
   - Press `Win + X` → Select "System"
   - Click "Advanced system settings"
   - Click "Environment Variables" button
   - Under "System variables", select `Path` → Click "Edit"
   - Click "New" and add: `C:\php`
   - Click "OK" three times to close all dialogs

4. **Verify PHP Installation**
   - Open Command Prompt (Press `Win + R`, type `cmd`, press Enter)
   - Type: `php -v`
   - Should show PHP version info (Example: `PHP 8.1.0 (cli)...`)

### 1.2 Install MySQL Server 8.x

1. **Download MySQL**
   - Visit https://dev.mysql.com/downloads/mysql/
   - Download "MySQL Community Server" (8.0 recommended)
   - Choose "Windows (x86, 64-bit), MSI Installer"

2. **Install MySQL**
   - Run the `.msi` installer
   - Choose "Setup Type" → Select "Developer Default"
   - Click "Next" through installation
   - When asked for MySQL Server configuration:
     - Select "Development Computer"
     - Port: `3306` (default)
     - Click "Next"
   - When asked for MySQL Root password:
     - **Enter password: `password`** (or use your preferred password)
     - Confirm the password
     - **Remember this password!**
   - Complete installation by clicking "Finish"

3. **Verify MySQL Installation**
   - Open Command Prompt
   - Type: `mysql -u root -p`
   - Enter password: `password`
   - Type: `exit` to quit

### 1.3 Install Git (Optional but Recommended)

1. **Download Git**
   - Visit https://git-scm.com/download/win
   - Download the 64-bit installer

2. **Install Git**
   - Run installer with default settings
   - Click "Next" through all steps
   - Click "Finish"

---

## Step 2: Download the Application

### Option A: Using Git (Recommended)

1. Open Command Prompt
2. Navigate to your desired folder:
   ```
   cd C:\Users\YourUsername\Desktop
   ```
3. Clone the repository:
   ```
   git clone <repository-url>
   cd Personal\ Finance\ Tracker
   ```

### Option B: Manual Download

1. Download the project ZIP file
2. Extract it to: `C:\Users\YourUsername\Desktop\Personal Finance Tracker`
3. Open Command Prompt and navigate to the folder:
   ```
   cd C:\Users\YourUsername\Desktop\Personal\ Finance\ Tracker
   ```

---

## Step 3: Configure the Application

### 3.1 Create Environment File

1. In the project folder, look for `.env.example`
2. Make a copy and rename it to `.env`
   - Right-click `.env.example` → "Copy"
   - Right-click empty space → "Paste"
   - Rename new file to `.env`

3. Open `.env` with Notepad
   - Right-click `.env` → "Edit"
   - Update database credentials:
     ```
     DB_HOST=localhost
     DB_PORT=3306
     DB_NAME=personal_finance_tracker
     DB_USER=root
     DB_PASS=password
     DEFAULT_CURRENCY=INR
     APP_ENV=development
     ```
   - Save the file (Ctrl + S)

### 3.2 Create Database

1. Open Command Prompt
2. Navigate to project folder:
   ```
   cd C:\Users\YourUsername\Desktop\Personal\ Finance\ Tracker
   ```

3. Create database from SQL file:
   ```
   mysql -u root -ppassword < backend\config\Database.sql
   ```
   - If prompted for password, enter: `password`

4. Load sample data (optional, provides demo transactions):
   ```
   mysql -u root -ppassword personal_finance_tracker < SAMPLE_DATA.sql
   ```

### 3.3 Verify Database Setup

1. In Command Prompt, type:
   ```
   mysql -u root -ppassword personal_finance_tracker
   ```

2. At the mysql prompt, type:
   ```
   SHOW TABLES;
   ```
   - Should show tables: `users`, `categories`, `transactions`, `budgets`, `savings_goals`, `sessions`, `audit_logs`, `default_categories`

3. Type `exit` to quit MySQL

---

## Step 4: Run the Application

### 4.1 Start PHP Built-in Server

1. Open Command Prompt
2. Navigate to project folder:
   ```
   cd C:\Users\YourUsername\Desktop\Personal\ Finance\ Tracker
   ```

3. Start the server:
   ```
   php -S localhost:8000 router.php
   ```

4. You should see:
   ```
   Development Server (http://127.0.0.1:8000)
   Listening on http://127.0.0.1:8000
   Press Ctrl-C to quit
   ```

### 4.2 Access the Application

1. Open your web browser (Chrome, Firefox, Edge, etc.)
2. Go to: `http://localhost:8000/frontend/public/`
3. You should see the Finance Tracker login page

---

## Step 5: Login and Use the Application

### 5.1 Test Login

**Option A: Create New Account**
1. Click "Register" link
2. Fill in details:
   - Username: `testuser`
   - Email: `test@example.com`
   - Password: `Test@1234`
   - Confirm: `Test@1234`
   - First Name: `Test`
   - Last Name: `User`
3. Click "Create Account"
4. You'll be logged in automatically

**Option B: Use Pre-loaded Sample Account** (if you loaded sample data)
1. Email: `test@example.com`
2. Password: `Test@1234`
3. Click "Login"

### 5.2 Explore the Features

- **Dashboard**: View summary of income, expenses, and savings goals
- **Transactions**: Add, edit, view income and expense transactions
- **Budgets**: Create and track monthly/yearly budgets
- **Savings Goals**: Set financial targets with progress tracking
- **Categories**: Manage transaction categories
- **Settings**: Update profile and preferences

---

## Troubleshooting

### Problem: "PHP is not recognized"
**Solution:**
- Verify PHP is added to PATH (Step 1.1, step 3)
- Restart Command Prompt after adding to PATH
- Restart computer if needed

### Problem: "MySQL Access denied for user 'root'"
**Solution:**
- Verify MySQL service is running:
  - Press `Win + R`, type `services.msc`, press Enter
  - Look for "MySQL80" (or similar version)
  - Right-click → "Start" if not running
- Verify password is correct (should be `password` by default)
- Reset MySQL password if forgotten:
  - See MySQL documentation for Windows password reset

### Problem: "Cannot connect to localhost:8000"
**Solution:**
- Verify PHP server is running (check Command Prompt output)
- Port 8000 might be in use. Use different port:
  ```
  php -S localhost:8001 router.php
  ```
  Then access: `http://localhost:8001/frontend/public/`
- Check Windows Firewall:
  - Press `Win + R`, type `wf.msc`, press Enter
  - Allow PHP through firewall if prompted

### Problem: "The database connection failed"
**Solution:**
- Verify MySQL is running (check Windows Services)
- Verify `.env` file has correct database credentials
- Verify database is created:
  ```
  mysql -u root -ppassword -e "SHOW DATABASES;"
  ```
  Should list `personal_finance_tracker`

### Problem: "404 Not Found" on pages
**Solution:**
- Make sure you're using the router: `php -S localhost:8000 router.php`
- Not just: `php -S localhost:8000`
- Restart the server

---

## Useful Commands

### Stop PHP Server
- Press `Ctrl + C` in Command Prompt

### Restart PHP Server
```bash
php -S localhost:8000 router.php
```

### Check PHP Version
```bash
php -v
```

### Check MySQL Status
```bash
mysql -u root -ppassword -e "SELECT 1"
```

### Backup Database
```bash
mysqldump -u root -ppassword personal_finance_tracker > backup.sql
```

### Restore Database
```bash
mysql -u root -ppassword personal_finance_tracker < backup.sql
```

---

## Performance Tips for Windows

1. **Disable Windows Defender scan** on project folder (improves performance):
   - Windows Defender → Virus & threat protection → Manage settings
   - Add exclusion for: `C:\Users\YourUsername\Desktop\Personal Finance Tracker`

2. **Use Command Prompt/PowerShell** instead of Windows Terminal for better PHP performance

3. **Keep MySQL service running** in background for faster access

4. **Clear browser cache** if experiencing stale data:
   - Chrome: `Ctrl + Shift + Delete`
   - Firefox: `Ctrl + Shift + Delete`
   - Edge: `Ctrl + Shift + Delete`

---

## Next Steps

### After Getting Started:

1. **Create Your Budget**: Set monthly/yearly spending limits
2. **Add Your Transactions**: Import or manually add income/expenses
3. **Track Categories**: Customize spending categories to match your needs
4. **Set Savings Goals**: Create financial targets with deadlines
5. **Monitor Progress**: Use charts to visualize spending patterns

### For Advanced Users:

- Modify `.env` file for custom settings
- Edit CSS files in `frontend\src\css\` for customization
- Add new API endpoints in `backend\src\api\`
- Extend database schema in `backend\config\Database.sql`

---

## Support & Documentation

- **README.md**: Feature overview and API documentation
- **DEVELOPMENT.md**: Architecture and development guide
- **API_REFERENCE.md**: Complete API endpoint documentation
- **SETUP.md**: General setup instructions

---

## Important Notes

⚠️ **Security Reminders:**
- Change `DB_PASS` in `.env` to a strong password
- Change `APP_SECRET` in `.env` to a unique secret
- Never share `.env` file on public repositories
- Keep MySQL and PHP updated

⚠️ **Backup Reminders:**
- Regularly backup your database:
  ```
  mysqldump -u root -ppassword personal_finance_tracker > backup_$(date +\%Y\%m\%d).sql
  ```
- Save backups in multiple locations

---

## Currency & Localization

The application uses **Indian Rupees (INR)** by default with sample transactions in INR.

To change currency:
1. Edit `.env` file: `DEFAULT_CURRENCY=USD` (or your currency code)
2. Update `backend/config/Database.sql` default currency
3. Restart PHP server

---

**Last Updated:** May 5, 2026  
**Version:** 1.0.0  
**Tested On:** Windows 10 (Version 22H2) with PHP 8.1+, MySQL 8.0+

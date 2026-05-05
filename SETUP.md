# Personal Finance Tracker - Setup Guide

## Quick Start

### System Requirements
- PHP 7.4+
- MySQL 8.0+
- Apache/Nginx with PHP support (or use PHP built-in server)
- Modern web browser

### Installation Steps

#### 1. Environment Configuration
```bash
# Copy environment template
cp .env.example .env

# Edit .env with your database credentials
# Edit values as needed:
DB_HOST=localhost
DB_PORT=3306
DB_NAME=personal_finance_tracker
DB_USER=root
DB_PASS=your_password
APP_ENV=development
```

#### 2. Database Setup
```bash
# Option A: Command line
mysql -u root -p < backend/config/Database.sql

# Option B: Using MySQL Workbench
# Open backend/config/Database.sql and execute
```

#### 3. Run PHP Development Server
```bash
# From project root directory
php -S localhost:8000

# The application will be available at:
# http://localhost:8000/frontend/public/
```

## Detailed Setup Instructions

### Prerequisites Installation

#### macOS
```bash
# Install PHP (if not already installed)
brew install php
brew install mysql

# Start MySQL
brew services start mysql

# Create database and user
mysql -u root
```

#### Windows
1. Install XAMPP, WAMP, or MAMP
2. Enable PHP and MySQL modules
3. Start Apache and MySQL services

#### Linux
```bash
# Ubuntu/Debian
sudo apt-get install php php-mysql php-curl php-json php-mbstring
sudo apt-get install mysql-server

# Start services
sudo service mysql start
```

### Database Creation

1. Open terminal/command prompt
2. Connect to MySQL:
   ```bash
   mysql -u root -p
   # Enter your MySQL password
   ```

3. Execute database creation:
   ```sql
   CREATE DATABASE personal_finance_tracker;
   USE personal_finance_tracker;
   # Run all SQL from backend/config/Database.sql
   ```

Or use the provided SQL file:
```bash
mysql -u root -p personal_finance_tracker < backend/config/Database.sql
```

### Project Structure

```
Personal Finance Tracker/
├── backend/                    # PHP Backend
│   ├── config/
│   │   ├── Database.php       # DB Connection
│   │   ├── Database.sql       # Database Schema
│   │   ├── Autoloader.php     # PSR-0 Autoloader
│   │   └── env.php            # Environment loader
│   └── src/
│       ├── api/               # RESTful API endpoints
│       ├── classes/           # Business logic classes
│       └── utils/             # Utility functions
├── frontend/                   # JavaScript Frontend
│   ├── public/
│   │   └── index.html         # Main HTML file
│   └── src/
│       ├── js/                # JavaScript modules
│       └── css/               # Stylesheets
├── database/                   # Migration folder
├── docs/                       # Additional documentation
├── .env                        # Environment variables (created from .env.example)
├── .env.example               # Example environment file
├── .gitignore                 # Git ignore rules
├── .editorconfig              # Editor configuration
├── .vscode/                   # VS Code configuration
└── README.md                  # Main documentation
```

### Running the Application

#### Method 1: PHP Built-in Server (Recommended for Development)
```bash
# Navigate to project directory
cd "Personal Finance Tracker"

# Start server
php -S localhost:8000

# Open browser
# http://localhost:8000/frontend/public/
```

#### Method 2: Apache (Windows XAMPP)
1. Move project to `C:\xampp\htdocs\pft\`
2. Access at `http://localhost/pft/frontend/public/`

#### Method 3: Apache (Linux)
```bash
# Copy project to Apache directory
sudo cp -r "Personal Finance Tracker" /var/www/html/pft

# Set permissions
sudo chown -R www-data:www-data /var/www/html/pft

# Create Apache config (if needed)
# Access at http://localhost/pft/frontend/public/
```

### Initial Login

1. Open application in browser
2. Click "Register" to create account
3. Fill in registration details:
   - Username: (unique)
   - Email: (valid email)
   - Password: (min 8 chars, uppercase, number)
   - First Name: Your name
4. Click Register
5. Login with credentials

### Default Categories

The system automatically creates the following categories for each user:

**Income Categories:**
- Salary
- Freelance
- Bonus
- Investment Income
- Other Income

**Expense Categories:**
- Food & Dining
- Transportation
- Shopping
- Entertainment
- Utilities
- Health & Medical
- Education
- Travel
- Insurance
- Personal Care
- Subscriptions
- Other Expenses

## Configuration Guide

### Environment Variables (.env)

```env
# Database Configuration
DB_HOST=localhost              # MySQL host
DB_PORT=3306                   # MySQL port
DB_NAME=personal_finance_tracker  # Database name
DB_USER=root                   # MySQL username
DB_PASS=                       # MySQL password

# Application Settings
APP_ENV=development            # development or production
APP_DEBUG=true                 # true or false
APP_SECRET=your-secret-key-change-this  # Secret key

# Session Configuration
SESSION_NAME=pft_session       # Session cookie name
SESSION_LIFETIME=3600          # Session timeout (seconds)

# Security
ALLOWED_ORIGINS=http://localhost:8000  # CORS allowed origins

# Email (optional for future features)
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USER=
MAIL_PASS=
```

## Troubleshooting

### Database Connection Errors

**Error: "Connection Error"**
- Check MySQL is running
- Verify credentials in .env
- Verify database exists: `mysql -u root -p -e "SHOW DATABASES;"`

**Error: "Access denied for user 'root'@'localhost'"**
- Reset MySQL password
- Update DB_PASS in .env
- Try empty password: `DB_PASS=`

### API Errors

**Error: 404 Not Found**
- Check URL format
- Ensure API_BASE_URL matches server
- Verify PHP server is running

**Error: 401 Unauthorized**
- Token expired, login again
- Check Authorization header format
- Clear localStorage and login fresh

### Frontend Issues

**Page won't load:**
- Check browser console (F12)
- Verify API_BASE_URL in api.js
- Check PHP server is running on port 8000

**Data not showing:**
- Check network tab in DevTools
- Verify API responses
- Check database has data

**Charts not displaying:**
- Verify Chart.js is loaded
- Check browser console for errors
- Refresh page

## Security Recommendations

### For Production

1. **Change APP_SECRET**
   ```env
   APP_SECRET=generate-a-long-random-string
   ```

2. **Set APP_ENV to production**
   ```env
   APP_ENV=production
   APP_DEBUG=false
   ```

3. **Enable HTTPS**
   - Use SSL certificate
   - Update ALLOWED_ORIGINS to https://

4. **Database Security**
   - Create dedicated MySQL user (not root)
   - Set strong password
   - Restrict host access

5. **File Permissions**
   ```bash
   chmod 750 backend/
   chmod 750 frontend/
   chmod 644 *.md
   ```

6. **Hide .env**
   - Don't commit .env to git
   - Set file permissions: `chmod 600 .env`

## Performance Optimization

### Database
- Indexes are created on frequently queried fields
- Consider archiving old transactions

### Frontend
- Browser caching enabled
- CSS and JavaScript minified for production
- Use CDN for Chart.js library

### Server
- Enable gzip compression
- Use PHP OPcache
- Consider using PHP 8.0+ for better performance

## Backup & Recovery

### Database Backup
```bash
# Backup database
mysqldump -u root -p personal_finance_tracker > backup.sql

# Restore database
mysql -u root -p personal_finance_tracker < backup.sql
```

### File Backup
```bash
# Backup entire project
tar -czf pft-backup.tar.gz "Personal Finance Tracker"

# Restore
tar -xzf pft-backup.tar.gz
```

## Development Tips

### Code Style
- PHP follows PSR-12 standards
- JavaScript uses ES6+ conventions
- CSS uses custom properties for theming

### Testing
```bash
# Create test transaction
# Use browser DevTools to test API

# Check database
mysql -u root -p personal_finance_tracker
SELECT * FROM users;
SELECT * FROM transactions;
```

### Debugging
1. Enable APP_DEBUG=true in .env
2. Check PHP error logs
3. Use browser DevTools (F12)
4. Check MySQL logs

## Next Steps

1. Create account and explore features
2. Add sample transactions
3. Create budgets and goals
4. Customize categories
5. Configure settings (currency, timezone)
6. Experiment with charts and reports

## Support & Resources

- Check README.md for feature documentation
- Review database schema in Database.sql
- Check API documentation in README.md
- Look at JavaScript modules in frontend/src/js/

## Common Commands

```bash
# Start PHP server
php -S localhost:8000

# Connect to MySQL
mysql -u root -p personal_finance_tracker

# View PHP version
php -v

# View MySQL version
mysql -V

# Test database connection (from MySQL prompt)
SELECT VERSION();
SELECT DATABASE();
```

---

**For more information, see README.md**

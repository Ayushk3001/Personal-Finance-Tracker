<!-- Use this file to provide workspace-specific custom instructions to Copilot. -->

# Personal Finance Tracker - Workspace Instructions

## Project Context

This is a full-stack Personal Finance Tracker application with:
- **Backend:** PHP 7.4+ with RESTful API
- **Frontend:** HTML5, CSS3, JavaScript ES6+
- **Database:** MySQL 8.0+ with comprehensive schema
- **Security:** Bcrypt passwords, JWT-like tokens, CSRF protection

## Project Structure

```
backend/config/        - Database config, autoloader, environment
backend/src/
  ├── api/            - RESTful API endpoints
  ├── classes/        - Business logic (Auth, Transaction, etc.)
  └── utils/          - Security, Validation, Response helpers
frontend/src/
  ├── js/             - API client, Auth, Dashboard, UI modules
  └── css/            - Responsive design with CSS variables
frontend/public/index.html - Single-page application shell
```

## Key Files

- `backend/config/Database.php` - PDO connection
- `backend/src/classes/Auth.php` - Authentication logic
- `frontend/src/js/api.js` - API client
- `frontend/src/js/dashboard.js` - Main dashboard logic
- `README.md` - Full documentation
- `SETUP.md` - Installation guide
- `DEVELOPMENT.md` - Developer guide

## Running the Project

```bash
# 1. Setup environment
cp .env.example .env
# Edit .env with database credentials

# 2. Create database
mysql -u root -p < backend/config/Database.sql

# 3. Start PHP server
php -S localhost:8000

# 4. Open browser
# http://localhost:8000/frontend/public/
```

## Common Copilot Tasks

1. **Adding Features:**
   - Create PHP class in `backend/src/classes/`
   - Add API endpoint in `backend/src/api/`
   - Create frontend JS module in `frontend/src/js/`
   - Update HTML structure in `frontend/public/index.html`

2. **API Debugging:**
   - Check format in `backend/src/utils/Response.php`
   - Verify authentication in endpoint file
   - Test with curl or Postman

3. **Frontend Issues:**
   - Check `frontend/src/js/api.js` for API calls
   - Verify DOM elements in `frontend/public/index.html`
   - Check CSS in `frontend/src/css/`

4. **Database Issues:**
   - Schema in `backend/config/Database.sql`
   - Check PDO in `backend/config/Database.php`
   - Verify .env credentials

## Code Conventions

- **PHP:** PSR-12 style, namespaced classes, prepared statements
- **JavaScript:** ES6+, async/await, module-based
- **CSS:** Custom properties, mobile-first, semantic naming
- **Naming:** camelCase for JS/CSS, snake_case for SQL

## Security Standards

- Bcrypt password hashing (cost 12)
- Token-based sessions with expiration
- Prepared SQL statements (PDO)
- Input validation & sanitization
- CSRF token protection
- Output escaping (htmlspecialchars)

## API Pattern

```
GET    /api/endpoint           - List
GET    /api/endpoint/{id}      - Get one
POST   /api/endpoint/create    - Create
PUT    /api/endpoint/{id}      - Update
DELETE /api/endpoint/{id}      - Delete
```

## Authentication

1. Register: POST `/api/auth/register`
2. Login: POST `/api/auth/login` → Returns token
3. Use token: `Authorization: Bearer {token}` in header
4. Server verifies with `Auth::verifyToken($token)`

## Database Tables

**Main tables:**
- `users` - Accounts
- `transactions` - Income/expenses
- `categories` - Transaction categories
- `budgets` - Spending budgets
- `savings_goals` - Financial goals
- `sessions` - Active tokens

## When You're Asked To...

- **"Add a new feature":** Create class → Add API → Add JS → Update HTML
- **"Fix a bug":** Check logs → Identify source → Fix → Test
- **"Optimize":** Add indexes → Cache data → Reduce queries
- **"Add validation":** Use `Validator` class → Check on backend and frontend
- **"Create a report":** Use `Transaction::getMonthlySummary()` → Display with Chart.js

## Testing

- Register account and login
- Add transactions in multiple categories
- Create budgets and goals
- Check dashboard charts render
- Verify responsive on mobile (F12 DevTools)

## Performance Notes

- Database indexes on: user_id, transaction_date, category_id
- Client caches categories in localStorage
- Charts use client-side rendering
- API paginate transactions (limit 50)

## Common Errors & Fixes

| Error | Fix |
|-------|-----|
| 401 Unauthorized | Check token in localStorage, re-login |
| 404 Not Found | Verify API endpoint spelling |
| 500 Server Error | Check PHP error logs, .env credentials |
| CORS Error | Update ALLOWED_ORIGINS in .env |
| No Charts | Verify Chart.js loaded, check data format |

## Documentation

- **README.md** - Feature overview & API docs
- **SETUP.md** - Installation & configuration
- **DEVELOPMENT.md** - Architecture & developer guide
- **Database.sql** - Schema with comments

## Quick Commands

```bash
php -S localhost:8000              # Start server
mysql -u root -p                   # Connect to MySQL
php -v                             # Check PHP version
curl http://localhost:8000/api     # Test API
```

---

**Last Updated:** May 5, 2026

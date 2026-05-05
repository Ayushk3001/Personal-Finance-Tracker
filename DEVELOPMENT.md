# Personal Finance Tracker - Development Guide

## Project Overview

A full-stack personal finance management system built with PHP, JavaScript, MySQL, and Chart.js.

**Tech Stack:**
- Backend: PHP 7.4+ with PDO
- Frontend: HTML5, CSS3, JavaScript ES6+
- Database: MySQL 8.0+
- Visualization: Chart.js 3.9+

## File Organization

### Backend Structure

```
backend/
├── config/
│   ├── Database.php      # PDO connection class
│   ├── Database.sql      # Database schema
│   ├── Autoloader.php    # PSR-0 namespace autoloader
│   └── env.php           # Environment variable loader
└── src/
    ├── api/              # RESTful API endpoints
    │   ├── index.php     # Main router
    │   ├── auth.php      # Authentication routes
    │   ├── transactions.php
    │   ├── categories.php
    │   ├── budgets.php
    │   ├── goals.php
    │   └── dashboard.php
    ├── classes/          # Business logic
    │   ├── Auth.php      # User auth & session
    │   ├── Transaction.php
    │   ├── Category.php
    │   ├── Budget.php
    │   └── SavingsGoal.php
    └── utils/            # Utilities
        ├── Security.php  # Password hashing, CSRF, validation
        ├── Response.php  # JSON response helpers
        └── Validator.php # Input validation
```

### Frontend Structure

```
frontend/
├── public/
│   └── index.html        # Single-page application shell
└── src/
    ├── js/               # JavaScript modules
    │   ├── api.js        # API client class
    │   ├── auth.js       # Authentication UI & logic
    │   ├── dashboard.js  # Dashboard functionality
    │   ├── transactions.js
    │   ├── budgets.js
    │   ├── goals.js
    │   ├── categories.js
    │   └── ui.js         # General UI utilities
    └── css/              # Stylesheets
        ├── styles.css    # Core styles, forms, buttons
        └── dashboard.css # Dashboard layout & components
```

## API Architecture

### Request/Response Pattern

**Request:**
```javascript
// All API calls include Authorization header
Authorization: Bearer {token}
Content-Type: application/json
```

**Success Response:**
```json
{
    "success": true,
    "message": "Success message",
    "data": { /* response data */ }
}
```

**Error Response:**
```json
{
    "success": false,
    "message": "Error message"
}
```

### Authentication Flow

1. User registers → Password hashed with bcrypt → User created
2. User logs in → Credentials verified → Session token generated
3. Token stored in localStorage (client)
4. Token sent in Authorization header for all subsequent requests
5. Token verified on server before allowing access
6. Token expires after SESSION_LIFETIME seconds

### Database Schema

**Core Tables:**
- `users` - User accounts with hashed passwords
- `categories` - Income/expense categories (default + custom)
- `transactions` - Income and expense records
- `budgets` - Budget tracking with spending limits
- `savings_goals` - Financial goals with targets
- `sessions` - Active user sessions with tokens
- `audit_logs` - Activity logging

**Foreign Keys:**
- transactions → users, categories
- budgets → users, categories
- savings_goals → users
- sessions → users
- audit_logs → users

## Key Features Implementation

### Authentication
- **File:** `backend/src/classes/Auth.php`
- **Security:** Bcrypt password hashing (cost 12)
- **Sessions:** Token-based with expiration
- **CSRF:** Token generation and validation

### Transaction Management
- **File:** `backend/src/classes/Transaction.php`
- **Features:** CRUD, filtering, monthly summaries, category breakdown
- **Queries:** Optimized with indexes on date and category

### Budget Tracking
- **File:** `backend/src/classes/Budget.php`
- **Features:** Period-based budgets, spending tracking, alerts
- **Logic:** Calculates spent amount for budget period

### Savings Goals
- **File:** `backend/src/classes/SavingsGoal.php`
- **Features:** Goal creation, progress tracking, priority levels
- **Progress:** Real-time percentage calculation

### Dashboard
- **File:** `frontend/src/js/dashboard.js`
- **Charts:** Chart.js doughnut and line charts
- **Summary Cards:** Income, expenses, balance, savings progress
- **Data Loading:** Async API calls with refresh

## Development Workflow

### Running Locally

```bash
# 1. Copy environment template
cp .env.example .env

# 2. Edit .env with database credentials
# DB_HOST=localhost, DB_USER=root, etc.

# 3. Create database
mysql -u root -p < backend/config/Database.sql

# 4. Start PHP server
php -S localhost:8000

# 5. Open browser
# http://localhost:8000/frontend/public/
```

### Adding a New Feature

1. **Backend:**
   - Create class in `backend/src/classes/`
   - Add API endpoint in `backend/src/api/`
   - Add validation in endpoint
   - Test with API client (Postman)

2. **Frontend:**
   - Add API methods to `frontend/src/js/api.js`
   - Create UI module if needed
   - Add HTML structure to `index.html`
   - Add CSS styling
   - Test in browser

3. **Database:**
   - Add migrations in `database/migrations/`
   - Update schema documentation

### Code Standards

**PHP:**
- PSR-12 coding style
- Namespaces for organization
- Type hints where possible
- Comments for complex logic

**JavaScript:**
- ES6+ syntax
- Async/await for API calls
- Clear function naming
- Module-based organization

**CSS:**
- CSS custom properties for theming
- Mobile-first responsive design
- BEM-style class naming
- Semantic color variables

## Common Tasks

### Adding a New API Endpoint

```php
// 1. Create route handler in api/endpoint.php
public function getAll() {
    $userId = $this->authenticateUser();
    if (!$userId) Response::error('Unauthorized', 401);
    
    $data = new DataClass();
    $results = $data->getByUser($userId);
    Response::success('Data retrieved', $results);
}

// 2. Add route in api/index.php
if (strpos($path, '/endpoint/') === 0) {
    require_once __DIR__ . '/endpoint.php';
}

// 3. Add API client method in frontend/src/js/api.js
static async getAll() {
    return this.get('/endpoint');
}
```

### Adding a New Database Table

1. Create SQL migration in `database/migrations/`
2. Execute migration against database
3. Update `Database.sql` with new schema
4. Create corresponding PHP class
5. Create API endpoints

### Implementing Charts

```javascript
// Use Chart.js with canvas element
new Chart(ctx, {
    type: 'doughnut',
    data: {
        labels: ['Label1', 'Label2'],
        datasets: [{
            data: [value1, value2],
            backgroundColor: ['#color1', '#color2']
        }]
    },
    options: { /* Chart options */ }
});
```

## Debugging Tips

### Backend Debugging

```bash
# Check PHP errors
tail -f /var/log/php-errors.log

# Test database connection
php -r "require 'backend/config/Database.php'; echo 'Connected';"

# Check specific API endpoint
curl -X GET http://localhost:8000/api/endpoint
```

### Frontend Debugging

```javascript
// Browser DevTools
console.log('Debug message');
debugger; // Set breakpoint

// Check API responses
console.log(response);

// Check localStorage
localStorage.getItem('authToken');
```

### Database Debugging

```sql
-- Check user creation
SELECT * FROM users;

-- Check transactions
SELECT * FROM transactions WHERE user_id = 1;

-- Check data consistency
SELECT COUNT(*) FROM transactions GROUP BY user_id;
```

## Performance Optimization

### Database
- Indexes on: user_id, transaction_date, category_id
- Avoid N+1 queries with proper joins
- Use LIMIT for pagination

### Frontend
- Cache categories in localStorage
- Lazy load transaction lists
- Debounce input events
- Minimize DOM manipulation

### Server
- Enable PHP OPcache
- Use gzip compression
- Consider PHP 8.0+ for better performance

## Security Best Practices

1. **Input Validation:** All inputs validated server-side
2. **SQL Injection:** PDO prepared statements
3. **XSS Prevention:** htmlspecialchars() on output
4. **CSRF Protection:** Token-based validation
5. **Password Security:** Bcrypt with high cost factor
6. **CORS:** Restricted to configured origins
7. **Session Security:** Secure tokens, expiration

## Testing Checklist

- [ ] User registration with validation
- [ ] Login/logout functionality
- [ ] Create/read/update/delete transactions
- [ ] Create/read/update/delete budgets
- [ ] Create/read/update/delete goals
- [ ] Dashboard data loads correctly
- [ ] Charts render properly
- [ ] Responsive design on mobile
- [ ] API error handling
- [ ] Session expiration

## Known Limitations

1. Single database connection per request
2. No transaction queuing for concurrent requests
3. Basic error logging (extend as needed)
4. No file upload support for receipts
5. No real-time notifications
6. No offline support

## Future Enhancements

- [ ] Add recurring transactions
- [ ] Multi-currency support
- [ ] Data export (CSV, PDF)
- [ ] Advanced reporting
- [ ] Mobile app
- [ ] Real-time sync
- [ ] Collaborative features
- [ ] AI spending insights

## Documentation Standards

- Keep README.md updated
- Document new endpoints in API section
- Add inline code comments for complex logic
- Update SETUP.md for new requirements
- Maintain schema documentation in Database.sql

## Support

For detailed setup instructions, see SETUP.md
For API documentation, see README.md API Endpoints section
For database schema, see backend/config/Database.sql

---

**Last Updated:** May 5, 2026

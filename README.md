# Personal Finance Tracker

A comprehensive full-stack application for managing personal finances with income/expense tracking, budgeting, and savings goals.

## Features

### 📊 Dashboard
- Real-time financial overview with summary cards
- Income vs expense charts (Chart.js)
- Spending breakdown by category
- Recent transactions list
- Savings goals progress tracking

### 💳 Transaction Management
- Log income and expenses with categories
- Multiple payment methods support
- Date-based organization
- Transaction filtering and search
- Edit and delete transactions

### 📈 Budgets
- Create budgets by category or overall
- Multiple budget periods (daily, weekly, monthly, yearly)
- Visual budget progress indicators
- Budget alerts and thresholds
- Budget vs actual spending

### 🎯 Savings Goals
- Set financial goals with target amounts
- Track progress with visual indicators
- Priority levels and target dates
- Goal categorization
- Overall savings progress

### 🏷️ Categories
- Pre-configured income and expense categories
- Custom category creation
- Color-coded categories
- Category-based spending analysis

### 🔐 Security
- Secure user authentication with bcrypt password hashing
- Token-based session management
- CSRF protection
- Input validation and sanitization
- SQL injection prevention

### 🎨 UI/UX
- Clean, card-based responsive design
- Dark/Light mode ready
- Mobile-first responsive layout
- Smooth animations and transitions
- Intuitive navigation

## Tech Stack

### Backend
- **Node.js** - Vercel serverless API
- **PostgreSQL** - Database
- **Prisma ORM** - Database schema and queries
- **RESTful API** - Clean API design

### Frontend
- **HTML5** - Semantic markup
- **CSS3** - Modern styling with variables
- **JavaScript ES6+** - Client-side logic
- **Chart.js** - Data visualization

## Project Structure

```
Personal Finance Tracker/
├── backend/
│   ├── config/
│   │   ├── Database.php
│   │   ├── Database.sql
│   │   ├── Autoloader.php
│   │   └── env.php
│   └── src/
│       ├── api/
│       │   ├── index.php
│       │   ├── auth.php
│       │   ├── transactions.php
│       │   ├── categories.php
│       │   ├── budgets.php
│       │   ├── goals.php
│       │   └── dashboard.php
│       ├── classes/
│       │   ├── Auth.php
│       │   ├── Transaction.php
│       │   ├── Category.php
│       │   ├── Budget.php
│       │   └── SavingsGoal.php
│       └── utils/
│           ├── Security.php
│           ├── Response.php
│           └── Validator.php
├── frontend/
│   ├── public/
│   │   └── index.html
│   └── src/
│       ├── css/
│       │   ├── styles.css
│       │   └── dashboard.css
│       └── js/
│           ├── api.js
│           ├── auth.js
│           ├── dashboard.js
│           ├── transactions.js
│           ├── budgets.js
│           ├── goals.js
│           ├── categories.js
│           └── ui.js
├── database/
├── docs/
├── .env.example
├── .gitignore
├── .editorconfig
└── README.md
```

## Installation

### Prerequisites
- Node.js 18 or higher
- PostgreSQL database URL

### Setup

1. **Clone or download the project**
   ```bash
   cd "Personal Finance Tracker"
   ```

2. **Install dependencies**
   ```bash
   npm install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   ```
   Edit `.env` with your PostgreSQL connection string:
   ```env
   DATABASE_URL="postgresql://USER:PASSWORD@HOST:PORT/defaultdb?sslmode=require"
   ```

4. **Create the database tables**
   ```bash
   npm run db:push
   ```

5. **Start the Vercel dev server**
   ```bash
   npm run dev
   ```

6. **Access the application**
   Open the local URL printed by Vercel.

## API Endpoints

### Authentication
- `POST /api/auth/register` - Register new user
- `POST /api/auth/login` - User login
- `POST /api/auth/logout` - User logout
- `POST /api/auth/change-password` - Change password

### Transactions
- `GET /api/transactions` - Get all transactions
- `POST /api/transactions/create` - Create transaction
- `GET /api/transactions/{id}` - Get transaction details
- `PUT /api/transactions/{id}` - Update transaction
- `DELETE /api/transactions/{id}` - Delete transaction
- `GET /api/transactions/summary/{year}/{month}` - Monthly summary
- `GET /api/transactions/spending` - Spending by category

### Categories
- `GET /api/categories` - Get all categories
- `POST /api/categories/create` - Create category
- `PUT /api/categories/{id}` - Update category
- `DELETE /api/categories/{id}` - Delete category

### Budgets
- `GET /api/budgets` - Get all budgets
- `POST /api/budgets/create` - Create budget
- `PUT /api/budgets/{id}` - Update budget
- `DELETE /api/budgets/{id}` - Delete budget

### Savings Goals
- `GET /api/goals` - Get all goals
- `POST /api/goals/create` - Create goal
- `PUT /api/goals/{id}` - Update goal
- `DELETE /api/goals/{id}` - Delete goal

### Dashboard
- `GET /api/dashboard` - Get dashboard data

## Database Schema

### Tables
- **users** - User accounts and profiles
- **categories** - Income/expense categories
- **transactions** - Income and expense records
- **budgets** - Budget tracking
- **savings_goals** - Savings goals
- **sessions** - Active user sessions
- **audit_logs** - Activity logging
- **default_categories** - Default category templates

## Security Features

- **Password Hashing**: BCrypt with cost factor 12
- **CSRF Protection**: Token-based protection
- **Input Validation**: Server-side validation
- **SQL Injection Prevention**: Prepared statements
- **XSS Prevention**: Output escaping
- **Session Security**: Secure token-based sessions
- **CORS**: Configurable origin control

## Usage Guide

### Creating an Account
1. Click "Register" on the login page
2. Fill in username, email, password, and name
3. Password must be 8+ characters with uppercase and numbers
4. Submit and login with credentials

### Adding Transactions
1. Click "Add Transaction" button
2. Select transaction type (Income/Expense)
3. Choose category
4. Enter amount, date, and description
5. Optionally add payment method and notes
6. Save transaction

### Setting Budgets
1. Navigate to Budgets
2. Click "Add Budget"
3. Set budget name, limit, and period
4. Assign to category or make it overall
5. Set alert threshold
6. Save budget

### Creating Savings Goals
1. Navigate to Goals
2. Click "Add Goal"
3. Enter goal name and target amount
4. Set target date and priority
5. Add description
6. Save goal

## Development

### Code Style
- PSR-12 compliant PHP code
- ES6+ JavaScript conventions
- CSS custom properties for theming
- Consistent indentation (4 spaces for PHP, 2 for JS/CSS)

### Database Migrations
Future database changes should be managed through SQL migration files in `database/migrations/`

### Testing
Basic validation and error handling included. Extend with unit tests as needed.

## Performance Optimization

- Database indexes on frequently queried fields
- Efficient SQL queries with proper joins
- Client-side caching of categories and budgets
- Lazy loading of transaction data
- Optimized CSS with custom properties

## Future Enhancements

- [ ] Multi-currency support
- [ ] Data export (CSV, PDF)
- [ ] Recurring transactions
- [ ] Investment tracking
- [ ] Bill reminders
- [ ] Dark mode
- [ ] Mobile app
- [ ] Advanced reporting
- [ ] Collaborative budgets
- [ ] Transaction receipts/attachments

## Troubleshooting

### Database Connection Error
- Verify `DATABASE_URL` in `.env`
- Confirm the PostgreSQL database exists and accepts SSL if your provider requires it
- Run `npm run db:push` after changing the Prisma schema

### API Errors
- Check PHP error logs
- Verify token in Authorization header
- Confirm request format matches API docs

### Frontend Issues
- Clear browser cache
- Check console for JavaScript errors
- Verify API_BASE_URL in api.js matches server

## License

MIT License - Feel free to use this project

## Support

For issues or questions, refer to the documentation or create an issue in the project repository.

---

**Created**: 2026
**Last Updated**: May 5, 2026

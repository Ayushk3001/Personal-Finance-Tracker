# Personal Finance Tracker - API Quick Reference

## Base URL
```
http://localhost:8000/api
```

## Authentication
All endpoints (except auth/register and auth/login) require:
```
Authorization: Bearer {token}
Content-Type: application/json
```

## Auth Endpoints

### Register
```
POST /auth/register
Body: {
  "username": "string",
  "email": "string",
  "password": "string (min 8, uppercase, number)",
  "firstName": "string",
  "lastName": "string (optional)"
}
Response: { "success": true, "data": { "userId": 1 } }
```

### Login
```
POST /auth/login
Body: { "email": "string", "password": "string" }
Response: { 
  "success": true, 
  "data": { 
    "token": "string",
    "userId": 1,
    "username": "string"
  }
}
```

### Logout
```
POST /auth/logout
Response: { "success": true }
```

### Change Password
```
POST /auth/change-password
Body: { "oldPassword": "string", "newPassword": "string" }
Response: { "success": true }
```

## Transaction Endpoints

### List Transactions
```
GET /transactions?limit=50&offset=0&type=expense&category_id=1&start_date=2026-05-01&end_date=2026-05-31
Response: { "success": true, "data": [...] }
```

### Create Transaction
```
POST /transactions/create
Body: {
  "category_id": 1,
  "description": "string",
  "amount": 99.99,
  "transaction_date": "2026-05-05",
  "type": "income|expense",
  "payment_method": "cash|credit_card|...",
  "notes": "string (optional)"
}
```

### Get Transaction
```
GET /transactions/{id}
```

### Update Transaction
```
PUT /transactions/{id}
Body: { "description": "string", "amount": 99.99, ... }
```

### Delete Transaction
```
DELETE /transactions/{id}
```

### Monthly Summary
```
GET /transactions/summary/{year}/{month}
Response: [
  { "type": "income", "category_id": 1, "category_name": "Salary", "total": 5000, "count": 1 }
]
```

### Spending by Category
```
GET /transactions/spending?start=2026-05-01&end=2026-05-31
Response: [
  { "id": 1, "name": "Food", "color": "#FF6F00", "total": 250.50, "count": 5, "average": 50 }
]
```

## Category Endpoints

### List Categories
```
GET /categories?type=income
Response: { "success": true, "data": [...] }
```

### Create Category
```
POST /categories/create
Body: {
  "name": "string",
  "type": "income|expense",
  "color": "#FF0000",
  "icon": "string",
  "description": "string (optional)"
}
```

### Update Category
```
PUT /categories/{id}
Body: { "name": "string", "color": "#FF0000", ... }
```

### Delete Category
```
DELETE /categories/{id}
```

## Budget Endpoints

### List Budgets
```
GET /budgets
```

### Create Budget
```
POST /budgets/create
Body: {
  "name": "string",
  "limit_amount": 500,
  "period": "daily|weekly|monthly|yearly",
  "category_id": 1 (optional),
  "start_date": "2026-05-01"
}
```

### Update Budget
```
PUT /budgets/{id}
Body: { "name": "string", "limit_amount": 500, ... }
```

### Delete Budget
```
DELETE /budgets/{id}
```

## Savings Goals Endpoints

### List Goals
```
GET /goals
```

### Create Goal
```
POST /goals/create
Body: {
  "name": "string",
  "target_amount": 10000,
  "target_date": "2027-05-01",
  "description": "string (optional)",
  "priority": "low|medium|high"
}
```

### Update Goal
```
PUT /goals/{id}
Body: { "name": "string", "current_amount": 5000, ... }
```

### Delete Goal
```
DELETE /goals/{id}
```

## Dashboard Endpoints

### Get Dashboard Data
```
GET /dashboard
Response: {
  "success": true,
  "data": {
    "summary": [...],
    "budgets": [...],
    "goals": [...],
    "recentTransactions": [...]
  }
}
```

## Response Codes

- **200** - Success
- **201** - Created
- **400** - Bad Request / Validation Error
- **401** - Unauthorized
- **404** - Not Found
- **405** - Method Not Allowed
- **422** - Validation Failed
- **500** - Server Error

## Error Response Format

```json
{
  "success": false,
  "message": "Error description"
}
```

## Validation Error Response

```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "field_name": "Error message"
  }
}
```

## Testing with curl

### Register
```bash
curl -X POST http://localhost:8000/api/auth/register \
  -H "Content-Type: application/json" \
  -d '{
    "username": "testuser",
    "email": "test@example.com",
    "password": "TestPass123",
    "firstName": "Test"
  }'
```

### Login
```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email": "test@example.com", "password": "TestPass123"}'
```

### Get Transactions (with token)
```bash
curl -X GET http://localhost:8000/api/transactions \
  -H "Authorization: Bearer YOUR_TOKEN"
```

### Create Transaction
```bash
curl -X POST http://localhost:8000/api/transactions/create \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "category_id": 1,
    "description": "Lunch",
    "amount": 15.50,
    "transaction_date": "2026-05-05",
    "type": "expense"
  }'
```

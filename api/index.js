const crypto = require("node:crypto");
const bcrypt = require("bcryptjs");
const { PrismaClient, Prisma } = require("@prisma/client");

const globalForPrisma = globalThis;
const prisma = globalForPrisma.prisma || new PrismaClient();

if (process.env.NODE_ENV !== "production") {
  globalForPrisma.prisma = prisma;
}

const DEFAULT_CATEGORIES = [
  ["Salary", "income", "#4CAF50", "briefcase"],
  ["Freelance", "income", "#8BC34A", "code"],
  ["Bonus", "income", "#FFC107", "gift"],
  ["Investment Income", "income", "#2196F3", "trending_up"],
  ["Other Income", "income", "#9C27B0", "help"],
  ["Food & Dining", "expense", "#FF6F00", "restaurant"],
  ["Transportation", "expense", "#00BCD4", "directions_car"],
  ["Shopping", "expense", "#E91E63", "shopping_bag"],
  ["Entertainment", "expense", "#FF5722", "movie"],
  ["Utilities", "expense", "#607D8B", "flash_on"],
  ["Health & Medical", "expense", "#F44336", "local_hospital"],
  ["Education", "expense", "#3F51B5", "school"],
  ["Travel", "expense", "#009688", "flight_takeoff"],
  ["Insurance", "expense", "#795548", "security"],
  ["Personal Care", "expense", "#FFEB3B", "spa"],
  ["Subscriptions", "expense", "#673AB7", "subscriptions"],
  ["Other Expenses", "expense", "#A9A9A9", "help"],
];

module.exports = async function handler(req, res) {
  setHeaders(req, res);

  if (req.method === "OPTIONS") {
    return res.status(204).end();
  }

  try {
    const url = new URL(req.url, `https://${req.headers.host || "localhost"}`);
    const pathname = url.pathname.replace(/^\/api\/?/, "/");
    const segments = pathname.split("/").filter(Boolean);

    if (segments[0] === "auth") {
      return handleAuth(req, res, segments);
    }

    const userId = await authenticate(req);
    if (!userId) {
      return error(res, "Unauthorized", 401);
    }

    if (segments[0] === "transactions") {
      return handleTransactions(req, res, segments, url.searchParams, userId);
    }

    if (segments[0] === "categories") {
      return handleCategories(req, res, segments, url.searchParams, userId);
    }

    if (segments[0] === "budgets") {
      return handleBudgets(req, res, segments, userId);
    }

    if (segments[0] === "goals") {
      return handleGoals(req, res, segments, userId);
    }

    if (segments[0] === "dashboard") {
      return handleDashboard(req, res, userId);
    }

    return error(res, "Endpoint not found", 404);
  } catch (err) {
    console.error(err);
    return error(res, "Server error", 500);
  }
};

function setHeaders(req, res) {
  const origin = process.env.ALLOWED_ORIGINS || req.headers.origin || "*";
  res.setHeader("Access-Control-Allow-Origin", origin);
  res.setHeader("Access-Control-Allow-Methods", "GET, POST, PUT, DELETE, OPTIONS");
  res.setHeader("Access-Control-Allow-Headers", "Content-Type, Authorization");
  res.setHeader("Content-Type", "application/json; charset=utf-8");
}

function success(res, message = "Success", data = null, statusCode = 200) {
  return res.status(statusCode).json(toPlain({ success: true, message, data }));
}

function error(res, message = "An error occurred", statusCode = 400, errors = undefined) {
  return res.status(statusCode).json({ success: false, message, ...(errors ? { errors } : {}) });
}

function toPlain(value) {
  return JSON.parse(
    JSON.stringify(value, (_key, current) => {
      if (current instanceof Prisma.Decimal) {
        return current.toString();
      }

      if (current instanceof Date) {
        return current.toISOString().slice(0, 10);
      }

      return current;
    })
  );
}

function getBody(req) {
  if (!req.body) return {};
  if (typeof req.body === "string") {
    try {
      return JSON.parse(req.body);
    } catch {
      return {};
    }
  }
  return req.body;
}

function requiredFields(body, fields) {
  return fields.reduce((errors, field) => {
    if (body[field] === undefined || body[field] === null || body[field] === "") {
      errors[field] = `${field} is required`;
    }
    return errors;
  }, {});
}

function validatePassword(password) {
  return typeof password === "string" && password.length >= 8 && /[A-Z]/.test(password) && /\d/.test(password);
}

function getToken(req) {
  const header = req.headers.authorization || "";
  const [scheme, token] = header.split(" ");
  return scheme === "Bearer" && token ? token : null;
}

async function authenticate(req) {
  const token = getToken(req);
  if (!token) return null;

  const session = await prisma.session.findFirst({
    where: {
      token,
      expires_at: { gt: new Date() },
    },
    select: { user_id: true },
  });

  return session?.user_id || null;
}

function getClientIp(req) {
  const forwardedFor = req.headers["x-forwarded-for"];
  if (typeof forwardedFor === "string") {
    return forwardedFor.split(",")[0].trim();
  }
  return req.socket?.remoteAddress || null;
}

function asNumber(value) {
  const parsed = Number(value);
  return Number.isFinite(parsed) ? parsed : null;
}

function asDate(value) {
  if (!value) return null;
  const date = new Date(`${value}T00:00:00.000Z`);
  return Number.isNaN(date.getTime()) ? null : date;
}

function addDays(date, days) {
  const next = new Date(date);
  next.setUTCDate(next.getUTCDate() + days);
  return next;
}

async function handleAuth(req, res, segments) {
  const action = segments[1];

  if (action === "register") {
    if (req.method !== "POST") return error(res, "Method not allowed", 405);
    return register(req, res);
  }

  if (action === "login") {
    if (req.method !== "POST") return error(res, "Method not allowed", 405);
    return login(req, res);
  }

  if (action === "logout") {
    if (req.method !== "POST") return error(res, "Method not allowed", 405);
    const token = getToken(req);
    if (!token) return error(res, "Unauthorized", 401);
    await prisma.session.deleteMany({ where: { token } });
    return success(res, "Logged out successfully");
  }

  if (action === "change-password") {
    if (req.method !== "POST") return error(res, "Method not allowed", 405);
    const userId = await authenticate(req);
    if (!userId) return error(res, "Unauthorized", 401);
    return changePassword(req, res, userId);
  }

  return error(res, "Auth endpoint not found", 404);
}

async function register(req, res) {
  const body = getBody(req);
  const validationErrors = requiredFields(body, ["username", "email", "password", "firstName"]);

  if (body.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(body.email)) {
    validationErrors.email = "Invalid email format";
  }

  if (body.password && !validatePassword(body.password)) {
    validationErrors.password = "Password must be at least 8 characters with uppercase and numbers";
  }

  if (Object.keys(validationErrors).length > 0) {
    return error(res, "Validation failed", 422, validationErrors);
  }

  const existingUser = await prisma.user.findFirst({
    where: {
      OR: [{ email: body.email }, { username: body.username }],
    },
    select: { id: true },
  });

  if (existingUser) {
    return error(res, "User already exists", 400);
  }

  const passwordHash = await bcrypt.hash(body.password, 12);

  const user = await prisma.$transaction(async (tx) => {
    const created = await tx.user.create({
      data: {
        username: body.username,
        email: body.email,
        password_hash: passwordHash,
        first_name: body.firstName,
        last_name: body.lastName || "",
      },
      select: { id: true },
    });

    await tx.category.createMany({
      data: DEFAULT_CATEGORIES.map(([name, type, color, icon]) => ({
        user_id: created.id,
        name,
        type,
        color,
        icon,
      })),
    });

    return created;
  });

  return success(res, "User registered successfully", { userId: user.id }, 201);
}

async function login(req, res) {
  const body = getBody(req);
  const validationErrors = requiredFields(body, ["email", "password"]);
  if (Object.keys(validationErrors).length > 0) {
    return error(res, "Validation failed", 422, validationErrors);
  }

  const user = await prisma.user.findFirst({
    where: {
      email: body.email,
      is_active: true,
    },
    select: {
      id: true,
      username: true,
      password_hash: true,
    },
  });

  if (!user || !(await bcrypt.compare(body.password, user.password_hash))) {
    return error(res, "Invalid credentials", 401);
  }

  const token = crypto.randomBytes(32).toString("hex");
  const expiresAt = addDays(new Date(), 7);

  await prisma.$transaction([
    prisma.session.create({
      data: {
        token,
        user_id: user.id,
        user_agent: req.headers["user-agent"] || "",
        ip_address: getClientIp(req),
        expires_at: expiresAt,
      },
    }),
    prisma.user.update({
      where: { id: user.id },
      data: { last_login: new Date() },
    }),
  ]);

  return success(res, "Login successful", {
    token,
    userId: user.id,
    username: user.username,
  });
}

async function changePassword(req, res, userId) {
  const body = getBody(req);
  const validationErrors = requiredFields(body, ["oldPassword", "newPassword"]);
  if (body.newPassword && !validatePassword(body.newPassword)) {
    validationErrors.newPassword = "Password must be at least 8 characters with uppercase and numbers";
  }

  if (Object.keys(validationErrors).length > 0) {
    return error(res, "Validation failed", 422, validationErrors);
  }

  const user = await prisma.user.findUnique({
    where: { id: userId },
    select: { password_hash: true },
  });

  if (!user || !(await bcrypt.compare(body.oldPassword, user.password_hash))) {
    return error(res, "Current password is incorrect", 400);
  }

  await prisma.user.update({
    where: { id: userId },
    data: { password_hash: await bcrypt.hash(body.newPassword, 12) },
  });

  return success(res, "Password changed successfully");
}

async function handleTransactions(req, res, segments, searchParams, userId) {
  if (segments[1] === "summary") {
    if (req.method !== "GET") return error(res, "Method not allowed", 405);
    return getMonthlySummary(res, userId, segments[2], segments[3]);
  }

  if (segments[1] === "spending") {
    if (req.method !== "GET") return error(res, "Method not allowed", 405);
    return getSpendingByCategory(res, userId, searchParams);
  }

  if (!segments[1] && req.method === "GET") {
    return getTransactions(res, userId, searchParams);
  }

  if (segments[1] === "create") {
    if (req.method !== "POST") return error(res, "Method not allowed", 405);
    return createTransaction(req, res, userId);
  }

  const id = asNumber(segments[1]);
  if (!id) return error(res, "Transaction endpoint not found", 404);

  if (req.method === "GET") {
    const transaction = await prisma.transaction.findFirst({
      where: { id, user_id: userId },
      include: { category: { select: { name: true, color: true } } },
    });

    if (!transaction) return error(res, "Transaction not found", 404);
    return success(res, "Transaction retrieved", formatTransaction(transaction));
  }

  if (req.method === "PUT") {
    return updateTransaction(req, res, userId, id);
  }

  if (req.method === "DELETE") {
    await prisma.transaction.deleteMany({ where: { id, user_id: userId } });
    return success(res, "Transaction deleted");
  }

  return error(res, "Method not allowed", 405);
}

async function getTransactions(res, userId, searchParams) {
  const limit = asNumber(searchParams.get("limit")) || 50;
  const offset = asNumber(searchParams.get("offset")) || 0;
  const where = { user_id: userId };
  const type = searchParams.get("type");
  const categoryId = asNumber(searchParams.get("category_id"));
  const startDate = asDate(searchParams.get("start_date"));
  const endDate = asDate(searchParams.get("end_date"));

  if (type) where.type = type;
  if (categoryId) where.category_id = categoryId;
  if (startDate || endDate) {
    where.transaction_date = {};
    if (startDate) where.transaction_date.gte = startDate;
    if (endDate) where.transaction_date.lte = endDate;
  }

  const transactions = await prisma.transaction.findMany({
    where,
    take: Math.min(limit, 100),
    skip: offset,
    orderBy: [{ transaction_date: "desc" }, { created_at: "desc" }],
    include: { category: { select: { name: true, color: true } } },
  });

  return success(res, "Transactions retrieved", transactions.map(formatTransaction));
}

async function createTransaction(req, res, userId) {
  const body = getBody(req);
  const validationErrors = requiredFields(body, ["category_id", "description", "amount", "transaction_date", "type"]);
  const amount = asNumber(body.amount);
  const categoryId = asNumber(body.category_id);
  const transactionDate = asDate(body.transaction_date);

  if (!amount || amount <= 0) validationErrors.amount = "Amount must be positive";
  if (!categoryId) validationErrors.category_id = "category_id must be numeric";
  if (!transactionDate) validationErrors.transaction_date = "transaction_date must be a valid date";

  if (Object.keys(validationErrors).length > 0) {
    return error(res, "Validation failed", 422, validationErrors);
  }

  const transaction = await prisma.transaction.create({
    data: {
      user_id: userId,
      category_id: categoryId,
      description: body.description,
      amount,
      transaction_date: transactionDate,
      type: body.type,
      payment_method: body.payment_method || null,
      notes: body.notes || null,
    },
    select: { id: true },
  });

  return success(res, "Transaction created", { id: transaction.id }, 201);
}

async function updateTransaction(req, res, userId, id) {
  const body = getBody(req);
  const data = {};

  if (body.category_id !== undefined) data.category_id = asNumber(body.category_id);
  if (body.description !== undefined) data.description = body.description;
  if (body.amount !== undefined) data.amount = asNumber(body.amount);
  if (body.transaction_date !== undefined) data.transaction_date = asDate(body.transaction_date);
  if (body.type !== undefined) data.type = body.type;
  if (body.payment_method !== undefined) data.payment_method = body.payment_method || null;
  if (body.notes !== undefined) data.notes = body.notes || null;

  if (Object.keys(data).length === 0) {
    return error(res, "No valid fields to update", 400);
  }

  await prisma.transaction.updateMany({
    where: { id, user_id: userId },
    data,
  });

  return success(res, "Transaction updated");
}

function formatTransaction(transaction) {
  const { category, ...rest } = transaction;
  return {
    ...rest,
    category_name: category?.name || null,
    color: category?.color || null,
  };
}

async function getMonthlySummary(res, userId, yearValue, monthValue) {
  const year = asNumber(yearValue);
  const month = asNumber(monthValue);

  if (!year || !month || month < 1 || month > 12) {
    return error(res, "Invalid parameters", 400);
  }

  const start = new Date(Date.UTC(year, month - 1, 1));
  const end = new Date(Date.UTC(year, month, 1));

  const groups = await prisma.transaction.groupBy({
    by: ["type", "category_id"],
    where: {
      user_id: userId,
      transaction_date: {
        gte: start,
        lt: end,
      },
    },
    _sum: { amount: true },
    _count: { id: true },
  });

  const categoryIds = groups.map((group) => group.category_id);
  const categories = await prisma.category.findMany({
    where: { id: { in: categoryIds } },
    select: { id: true, name: true, color: true },
  });
  const categoryMap = new Map(categories.map((category) => [category.id, category]));

  const summary = groups
    .map((group) => {
      const category = categoryMap.get(group.category_id);
      return {
        type: group.type,
        category_id: group.category_id,
        category_name: category?.name || null,
        color: category?.color || null,
        total: group._sum.amount || 0,
        count: group._count.id,
      };
    })
    .sort((a, b) => {
      if (a.type !== b.type) return a.type < b.type ? 1 : -1;
      return Number(b.total) - Number(a.total);
    });

  return success(res, "Summary retrieved", summary);
}

async function getSpendingByCategory(res, userId, searchParams) {
  const now = new Date();
  const startDate = asDate(searchParams.get("start")) || new Date(Date.UTC(now.getUTCFullYear(), now.getUTCMonth(), 1));
  const endDate = asDate(searchParams.get("end")) || now;

  const categories = await prisma.category.findMany({
    where: {
      user_id: userId,
      type: "expense",
      is_active: true,
    },
    orderBy: { name: "asc" },
  });

  const groups = await prisma.transaction.groupBy({
    by: ["category_id"],
    where: {
      user_id: userId,
      type: "expense",
      transaction_date: {
        gte: startDate,
        lte: endDate,
      },
    },
    _sum: { amount: true },
    _count: { id: true },
    _avg: { amount: true },
  });

  const groupMap = new Map(groups.map((group) => [group.category_id, group]));

  const spending = categories
    .map((category) => {
      const group = groupMap.get(category.id);
      return {
        id: category.id,
        name: category.name,
        color: category.color,
        icon: category.icon,
        total: group?._sum.amount || 0,
        count: group?._count.id || 0,
        average: group?._avg.amount || 0,
      };
    })
    .sort((a, b) => Number(b.total) - Number(a.total));

  return success(res, "Spending retrieved", spending);
}

async function handleCategories(req, res, segments, searchParams, userId) {
  if (!segments[1] && req.method === "GET") {
    const type = searchParams.get("type");
    const categories = await prisma.category.findMany({
      where: {
        user_id: userId,
        is_active: true,
        ...(type ? { type } : {}),
      },
      orderBy: [{ type: "asc" }, { name: "asc" }],
    });
    return success(res, "Categories retrieved", categories);
  }

  if (segments[1] === "create") {
    if (req.method !== "POST") return error(res, "Method not allowed", 405);
    const body = getBody(req);
    const validationErrors = requiredFields(body, ["name", "type"]);
    if (Object.keys(validationErrors).length > 0) {
      return error(res, "Validation failed", 422, validationErrors);
    }

    const category = await prisma.category.create({
      data: {
        user_id: userId,
        name: body.name,
        type: body.type,
        color: body.color || "#000000",
        icon: body.icon || null,
        description: body.description || null,
      },
      select: { id: true },
    });

    return success(res, "Category created", { id: category.id }, 201);
  }

  const id = asNumber(segments[1]);
  if (!id) return error(res, "Category endpoint not found", 404);

  if (req.method === "PUT") {
    const body = getBody(req);
    const data = pick(body, ["name", "color", "icon", "description", "is_active"]);
    if (Object.keys(data).length === 0) return error(res, "No valid fields to update", 400);

    await prisma.category.updateMany({ where: { id, user_id: userId }, data });
    return success(res, "Category updated");
  }

  if (req.method === "DELETE") {
    await prisma.category.updateMany({
      where: { id, user_id: userId },
      data: { is_active: false },
    });
    return success(res, "Category updated");
  }

  return error(res, "Method not allowed", 405);
}

async function handleBudgets(req, res, segments, userId) {
  if (!segments[1] && req.method === "GET") {
    const budgets = await prisma.budget.findMany({
      where: { user_id: userId, is_active: true },
      orderBy: [{ period: "asc" }, { created_at: "desc" }],
      include: { category: { select: { name: true } } },
    });

    const formatted = await Promise.all(budgets.map((budget) => formatBudgetWithSpent(budget)));
    return success(res, "Budgets retrieved", formatted);
  }

  if (segments[1] === "create") {
    if (req.method !== "POST") return error(res, "Method not allowed", 405);
    const body = getBody(req);
    const validationErrors = requiredFields(body, ["name", "limit_amount"]);
    const limitAmount = asNumber(body.limit_amount);

    if (!limitAmount || limitAmount <= 0) {
      validationErrors.limit_amount = "limit_amount must be positive";
    }

    if (Object.keys(validationErrors).length > 0) {
      return error(res, "Validation failed", 422, validationErrors);
    }

    const budget = await prisma.budget.create({
      data: {
        user_id: userId,
        category_id: body.category_id ? asNumber(body.category_id) : null,
        name: body.name,
        limit_amount: limitAmount,
        period: body.period || "monthly",
        start_date: asDate(body.start_date) || new Date(),
      },
      select: { id: true },
    });

    return success(res, "Budget created", { id: budget.id }, 201);
  }

  const id = asNumber(segments[1]);
  if (!id) return error(res, "Budget endpoint not found", 404);

  if (req.method === "PUT") {
    const body = getBody(req);
    const data = pick(body, ["name", "period", "alert_threshold", "is_active"]);
    if (body.limit_amount !== undefined) data.limit_amount = asNumber(body.limit_amount);
    if (Object.keys(data).length === 0) return error(res, "No valid fields to update", 400);

    await prisma.budget.updateMany({ where: { id, user_id: userId }, data });
    return success(res, "Budget updated");
  }

  if (req.method === "DELETE") {
    await prisma.budget.deleteMany({ where: { id, user_id: userId } });
    return success(res, "Budget deleted");
  }

  return error(res, "Method not allowed", 405);
}

async function formatBudgetWithSpent(budget) {
  const { start, end } = budgetDateRange(budget.start_date, budget.period);
  const aggregate = await prisma.transaction.aggregate({
    where: {
      user_id: budget.user_id,
      type: "expense",
      transaction_date: {
        gte: start,
        lte: end,
      },
      ...(budget.category_id ? { category_id: budget.category_id } : {}),
    },
    _sum: { amount: true },
  });

  const { category, ...rest } = budget;
  return {
    ...rest,
    spent_amount: aggregate._sum.amount || 0,
    category_name: category?.name || null,
  };
}

function budgetDateRange(startDate, period) {
  const start = new Date(startDate);
  const end = new Date(startDate);

  if (period === "daily") end.setUTCDate(end.getUTCDate() + 1);
  if (period === "weekly") end.setUTCDate(end.getUTCDate() + 7);
  if (period === "monthly") end.setUTCMonth(end.getUTCMonth() + 1);
  if (period === "yearly") end.setUTCFullYear(end.getUTCFullYear() + 1);

  end.setUTCDate(end.getUTCDate() - 1);
  return { start, end };
}

async function handleGoals(req, res, segments, userId) {
  if (!segments[1] && req.method === "GET") {
    const goals = await prisma.savingsGoal.findMany({
      where: { user_id: userId, is_active: true },
      orderBy: [{ priority: "desc" }, { created_at: "desc" }],
    });
    return success(res, "Goals retrieved", goals);
  }

  if (segments[1] === "create") {
    if (req.method !== "POST") return error(res, "Method not allowed", 405);
    const body = getBody(req);
    const validationErrors = requiredFields(body, ["name", "target_amount"]);
    const targetAmount = asNumber(body.target_amount);

    if (!targetAmount || targetAmount <= 0) {
      validationErrors.target_amount = "target_amount must be positive";
    }

    if (Object.keys(validationErrors).length > 0) {
      return error(res, "Validation failed", 422, validationErrors);
    }

    const goal = await prisma.savingsGoal.create({
      data: {
        user_id: userId,
        name: body.name,
        target_amount: targetAmount,
        target_date: body.target_date ? asDate(body.target_date) : null,
        description: body.description || null,
        priority: body.priority || "medium",
      },
      select: { id: true },
    });

    return success(res, "Goal created", { id: goal.id }, 201);
  }

  const id = asNumber(segments[1]);
  if (!id) return error(res, "Goal endpoint not found", 404);

  if (req.method === "PUT") {
    const body = getBody(req);
    const data = pick(body, ["name", "description", "priority", "is_active"]);
    if (body.target_amount !== undefined) data.target_amount = asNumber(body.target_amount);
    if (body.current_amount !== undefined) data.current_amount = asNumber(body.current_amount);
    if (body.target_date !== undefined) data.target_date = body.target_date ? asDate(body.target_date) : null;
    if (Object.keys(data).length === 0) return error(res, "No valid fields to update", 400);

    await prisma.savingsGoal.updateMany({ where: { id, user_id: userId }, data });
    return success(res, "Goal updated");
  }

  if (req.method === "DELETE") {
    await prisma.savingsGoal.updateMany({
      where: { id, user_id: userId },
      data: { is_active: false },
    });
    return success(res, "Goal updated");
  }

  return error(res, "Method not allowed", 405);
}

async function handleDashboard(req, res, userId) {
  if (req.method !== "GET") return error(res, "Method not allowed", 405);

  const currentDate = new Date();
  const year = currentDate.getUTCFullYear();
  const month = currentDate.getUTCMonth() + 1;
  const start = new Date(Date.UTC(year, month - 1, 1));
  const end = new Date(Date.UTC(year, month, 1));

  const [summaryGroups, budgets, goals, recentTransactions] = await Promise.all([
    prisma.transaction.groupBy({
      by: ["type", "category_id"],
      where: {
        user_id: userId,
        transaction_date: { gte: start, lt: end },
      },
      _sum: { amount: true },
      _count: { id: true },
    }),
    prisma.budget.findMany({
      where: { user_id: userId, is_active: true },
      include: { category: { select: { name: true } } },
    }),
    prisma.savingsGoal.findMany({
      where: { user_id: userId, is_active: true },
    }),
    prisma.transaction.findMany({
      where: { user_id: userId },
      take: 5,
      orderBy: [{ transaction_date: "desc" }, { created_at: "desc" }],
      include: { category: { select: { name: true, color: true } } },
    }),
  ]);

  const categoryIds = summaryGroups.map((group) => group.category_id);
  const categories = await prisma.category.findMany({
    where: { id: { in: categoryIds } },
    select: { id: true, name: true, color: true },
  });
  const categoryMap = new Map(categories.map((category) => [category.id, category]));

  const summary = summaryGroups.map((group) => {
    const category = categoryMap.get(group.category_id);
    return {
      type: group.type,
      category_id: group.category_id,
      category_name: category?.name || null,
      color: category?.color || null,
      total: group._sum.amount || 0,
      count: group._count.id,
    };
  });

  return success(res, "Dashboard data retrieved", {
    summary,
    budgets: await Promise.all(budgets.map((budget) => formatBudgetWithSpent(budget))),
    goals,
    recentTransactions: recentTransactions.map(formatTransaction),
  });
}

function pick(source, keys) {
  return keys.reduce((target, key) => {
    if (source[key] !== undefined) target[key] = source[key];
    return target;
  }, {});
}

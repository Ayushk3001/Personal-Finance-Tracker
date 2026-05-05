<?php

namespace Classes;

use Config\Database;

class Budget
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Create budget
     */
    public function create($userId, $name, $limitAmount, $period = 'monthly', $categoryId = null, $startDate = null)
    {
        $conn = $this->db->getConnection();
        $startDate = $startDate ?? date('Y-m-d');

        try {
            $stmt = $conn->prepare('
                INSERT INTO budgets (user_id, category_id, name, limit_amount, period, start_date)
                VALUES (?, ?, ?, ?, ?, ?)
            ');

            $stmt->execute([$userId, $categoryId, $name, $limitAmount, $period, $startDate]);
            return ['success' => true, 'message' => 'Budget created', 'id' => $conn->lastInsertId()];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to create budget: ' . $e->getMessage()];
        }
    }

    /**
     * Get all budgets for user
     */
    public function getByUser($userId, $active = true)
    {
        $conn = $this->db->getConnection();

        if ($active) {
            $stmt = $conn->prepare('
                SELECT b.*, c.name as category_name 
                FROM budgets b
                LEFT JOIN categories c ON b.category_id = c.id
                WHERE b.user_id = ? AND b.is_active = 1
                ORDER BY b.period, b.created_at DESC
            ');
            $stmt->execute([$userId]);
        } else {
            $stmt = $conn->prepare('
                SELECT b.*, c.name as category_name 
                FROM budgets b
                LEFT JOIN categories c ON b.category_id = c.id
                WHERE b.user_id = ?
                ORDER BY b.is_active DESC, b.created_at DESC
            ');
            $stmt->execute([$userId]);
        }

        return $stmt->fetchAll();
    }

    /**
     * Get budget by ID
     */
    public function getById($id, $userId)
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('SELECT * FROM budgets WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $userId]);
        return $stmt->fetch();
    }

    /**
     * Update spent amount
     */
    public function updateSpent($id, $userId)
    {
        $conn = $this->db->getConnection();

        // Get budget details
        $budget = $this->getById($id, $userId);
        if (!$budget) {
            return ['success' => false, 'message' => 'Budget not found'];
        }

        // Calculate spent amount for the period
        $spent = $this->calculateSpentAmount($budget);

        try {
            $stmt = $conn->prepare('UPDATE budgets SET spent_amount = ? WHERE id = ?');
            $stmt->execute([$spent, $id]);
            return ['success' => true, 'message' => 'Budget updated'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to update budget: ' . $e->getMessage()];
        }
    }

    /**
     * Calculate spent amount for budget period
     */
    private function calculateSpentAmount($budget)
    {
        $conn = $this->db->getConnection();

        $dateRange = $this->getDateRange($budget['start_date'], $budget['period']);

        $query = 'SELECT SUM(amount) as total FROM transactions 
                  WHERE user_id = ? AND type = "expense" 
                  AND transaction_date >= ? AND transaction_date <= ?';
        $params = [$budget['user_id'], $dateRange['start'], $dateRange['end']];

        if ($budget['category_id']) {
            $query .= ' AND category_id = ?';
            $params[] = $budget['category_id'];
        }

        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetch();

        return $result['total'] ?? 0;
    }

    /**
     * Get date range for budget period
     */
    private function getDateRange($startDate, $period)
    {
        $start = new \DateTime($startDate);
        $end = clone $start;

        switch ($period) {
            case 'daily':
                $end->modify('+1 day');
                break;
            case 'weekly':
                $end->modify('+7 days');
                break;
            case 'monthly':
                $end->modify('+1 month');
                break;
            case 'yearly':
                $end->modify('+1 year');
                break;
        }

        $end->modify('-1 day');

        return [
            'start' => $start->format('Y-m-d'),
            'end' => $end->format('Y-m-d')
        ];
    }

    /**
     * Update budget
     */
    public function update($id, $userId, $data)
    {
        $conn = $this->db->getConnection();

        $allowedFields = ['name', 'limit_amount', 'period', 'alert_threshold', 'is_active'];
        $updates = [];
        $params = [];

        foreach ($data as $field => $value) {
            if (in_array($field, $allowedFields)) {
                $updates[] = "$field = ?";
                $params[] = $value;
            }
        }

        if (empty($updates)) {
            return ['success' => false, 'message' => 'No valid fields to update'];
        }

        $params[] = $id;
        $params[] = $userId;

        $query = 'UPDATE budgets SET ' . implode(', ', $updates) . ' WHERE id = ? AND user_id = ?';
        $stmt = $conn->prepare($query);

        try {
            $stmt->execute($params);
            return ['success' => true, 'message' => 'Budget updated'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to update budget: ' . $e->getMessage()];
        }
    }

    /**
     * Delete budget
     */
    public function delete($id, $userId)
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('DELETE FROM budgets WHERE id = ? AND user_id = ?');

        try {
            $stmt->execute([$id, $userId]);
            return ['success' => true, 'message' => 'Budget deleted'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to delete budget: ' . $e->getMessage()];
        }
    }
}

<?php

namespace Classes;

use Config\Database;

class Transaction
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Create transaction
     */
    public function create($userId, $categoryId, $description, $amount, $date, $type, $paymentMethod = null, $notes = null)
    {
        $conn = $this->db->getConnection();

        try {
            $stmt = $conn->prepare('
                INSERT INTO transactions (user_id, category_id, description, amount, transaction_date, type, payment_method, notes)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ');

            $stmt->execute([
                $userId,
                $categoryId,
                $description,
                $amount,
                $date,
                $type,
                $paymentMethod,
                $notes
            ]);

            return ['success' => true, 'message' => 'Transaction created', 'id' => $conn->lastInsertId()];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to create transaction: ' . $e->getMessage()];
        }
    }

    /**
     * Get user transactions
     */
    public function getByUser($userId, $limit = 50, $offset = 0, $filters = [])
    {
        $conn = $this->db->getConnection();

        $query = 'SELECT t.*, c.name as category_name, c.color FROM transactions t 
                  LEFT JOIN categories c ON t.category_id = c.id 
                  WHERE t.user_id = ?';
        $params = [$userId];

        // Apply filters
        if (!empty($filters['type'])) {
            $query .= ' AND t.type = ?';
            $params[] = $filters['type'];
        }

        if (!empty($filters['category_id'])) {
            $query .= ' AND t.category_id = ?';
            $params[] = $filters['category_id'];
        }

        if (!empty($filters['start_date'])) {
            $query .= ' AND t.transaction_date >= ?';
            $params[] = $filters['start_date'];
        }

        if (!empty($filters['end_date'])) {
            $query .= ' AND t.transaction_date <= ?';
            $params[] = $filters['end_date'];
        }

        $query .= ' ORDER BY t.transaction_date DESC LIMIT ' . (int)$limit . ' OFFSET ' . (int)$offset;

        $stmt = $conn->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Get transaction by ID
     */
    public function getById($id, $userId)
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('SELECT * FROM transactions WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $userId]);
        return $stmt->fetch();
    }

    /**
     * Update transaction
     */
    public function update($id, $userId, $data)
    {
        $conn = $this->db->getConnection();

        $allowedFields = ['category_id', 'description', 'amount', 'transaction_date', 'type', 'payment_method', 'notes'];
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

        $query = 'UPDATE transactions SET ' . implode(', ', $updates) . ' WHERE id = ? AND user_id = ?';
        $stmt = $conn->prepare($query);

        try {
            $stmt->execute($params);
            return ['success' => true, 'message' => 'Transaction updated'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to update transaction: ' . $e->getMessage()];
        }
    }

    /**
     * Delete transaction
     */
    public function delete($id, $userId)
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('DELETE FROM transactions WHERE id = ? AND user_id = ?');

        try {
            $stmt->execute([$id, $userId]);
            return ['success' => true, 'message' => 'Transaction deleted'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to delete transaction: ' . $e->getMessage()];
        }
    }

    /**
     * Get monthly summary
     */
    public function getMonthlySummary($userId, $year, $month)
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('
            SELECT 
                t.type,
                t.category_id,
                c.name as category_name,
                c.color,
                SUM(t.amount) as total,
                COUNT(*) as count
            FROM transactions t
            LEFT JOIN categories c ON t.category_id = c.id
            WHERE t.user_id = ? 
            AND YEAR(t.transaction_date) = ? 
            AND MONTH(t.transaction_date) = ?
            GROUP BY t.type, t.category_id, c.id, c.name, c.color
            ORDER BY t.type DESC, total DESC
        ');
        $stmt->execute([$userId, $year, $month]);
        return $stmt->fetchAll();
    }

    /**
     * Get spending by category
     */
    public function getSpendingByCategory($userId, $startDate, $endDate)
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('
            SELECT 
                c.id,
                c.name,
                c.color,
                c.icon,
                SUM(t.amount) as total,
                COUNT(t.id) as count,
                AVG(t.amount) as average
            FROM categories c
            LEFT JOIN transactions t ON c.id = t.category_id 
            AND t.user_id = ? 
            AND t.transaction_date >= ? 
            AND t.transaction_date <= ?
            AND t.type = "expense"
            WHERE c.user_id = ? AND c.type = "expense"
            GROUP BY c.id, c.name, c.color, c.icon
            ORDER BY total DESC
        ');
        $stmt->execute([$userId, $startDate, $endDate, $userId]);
        return $stmt->fetchAll();
    }
}

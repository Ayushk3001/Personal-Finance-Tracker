<?php

namespace Classes;

use Config\Database;

class SavingsGoal
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Create savings goal
     */
    public function create($userId, $name, $targetAmount, $targetDate = null, $description = null, $priority = 'medium')
    {
        $conn = $this->db->getConnection();

        try {
            $stmt = $conn->prepare('
                INSERT INTO savings_goals (user_id, name, description, target_amount, target_date, priority)
                VALUES (?, ?, ?, ?, ?, ?)
            ');

            $stmt->execute([$userId, $name, $description, $targetAmount, $targetDate, $priority]);
            return ['success' => true, 'message' => 'Goal created', 'id' => $conn->lastInsertId()];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to create goal: ' . $e->getMessage()];
        }
    }

    /**
     * Get all savings goals for user
     */
    public function getByUser($userId, $active = true)
    {
        $conn = $this->db->getConnection();

        if ($active) {
            $stmt = $conn->prepare('SELECT * FROM savings_goals WHERE user_id = ? AND is_active = 1 ORDER BY priority DESC, created_at DESC');
            $stmt->execute([$userId]);
        } else {
            $stmt = $conn->prepare('SELECT * FROM savings_goals WHERE user_id = ? ORDER BY is_active DESC, created_at DESC');
            $stmt->execute([$userId]);
        }

        return $stmt->fetchAll();
    }

    /**
     * Get goal by ID
     */
    public function getById($id, $userId)
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('SELECT * FROM savings_goals WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $userId]);
        return $stmt->fetch();
    }

    /**
     * Update goal progress
     */
    public function updateProgress($id, $userId, $amount)
    {
        $conn = $this->db->getConnection();

        try {
            $stmt = $conn->prepare('
                UPDATE savings_goals 
                SET current_amount = current_amount + ?,
                    updated_at = NOW()
                WHERE id = ? AND user_id = ?
            ');
            $stmt->execute([$amount, $id, $userId]);
            return ['success' => true, 'message' => 'Goal progress updated'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to update goal: ' . $e->getMessage()];
        }
    }

    /**
     * Update goal details
     */
    public function update($id, $userId, $data)
    {
        $conn = $this->db->getConnection();

        $allowedFields = ['name', 'description', 'target_amount', 'target_date', 'priority', 'is_active'];
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

        $query = 'UPDATE savings_goals SET ' . implode(', ', $updates) . ' WHERE id = ? AND user_id = ?';
        $stmt = $conn->prepare($query);

        try {
            $stmt->execute($params);
            return ['success' => true, 'message' => 'Goal updated'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to update goal: ' . $e->getMessage()];
        }
    }

    /**
     * Get progress percentage
     */
    public function getProgress($id, $userId)
    {
        $goal = $this->getById($id, $userId);
        if (!$goal) {
            return null;
        }

        $percentage = ($goal['current_amount'] / $goal['target_amount']) * 100;
        return min(100, round($percentage, 2));
    }
}

<?php

namespace Classes;

use Config\Database;

class Category
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Create category
     */
    public function create($userId, $name, $type, $color = '#000000', $icon = null, $description = null)
    {
        $conn = $this->db->getConnection();

        try {
            $stmt = $conn->prepare('
                INSERT INTO categories (user_id, name, type, color, icon, description)
                VALUES (?, ?, ?, ?, ?, ?)
            ');

            $stmt->execute([$userId, $name, $type, $color, $icon, $description]);
            return ['success' => true, 'message' => 'Category created', 'id' => $conn->lastInsertId()];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to create category: ' . $e->getMessage()];
        }
    }

    /**
     * Get all categories for user
     */
    public function getByUser($userId, $type = null)
    {
        $conn = $this->db->getConnection();

        if ($type) {
            $stmt = $conn->prepare('SELECT * FROM categories WHERE user_id = ? AND type = ? AND is_active = 1 ORDER BY name');
            $stmt->execute([$userId, $type]);
        } else {
            $stmt = $conn->prepare('SELECT * FROM categories WHERE user_id = ? AND is_active = 1 ORDER BY type, name');
            $stmt->execute([$userId]);
        }

        return $stmt->fetchAll();
    }

    /**
     * Get category by ID
     */
    public function getById($id, $userId)
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('SELECT * FROM categories WHERE id = ? AND user_id = ?');
        $stmt->execute([$id, $userId]);
        return $stmt->fetch();
    }

    /**
     * Update category
     */
    public function update($id, $userId, $data)
    {
        $conn = $this->db->getConnection();

        $allowedFields = ['name', 'color', 'icon', 'description', 'is_active'];
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

        $query = 'UPDATE categories SET ' . implode(', ', $updates) . ' WHERE id = ? AND user_id = ?';
        $stmt = $conn->prepare($query);

        try {
            $stmt->execute($params);
            return ['success' => true, 'message' => 'Category updated'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Failed to update category: ' . $e->getMessage()];
        }
    }

    /**
     * Delete category (soft delete)
     */
    public function delete($id, $userId)
    {
        return $this->update($id, $userId, ['is_active' => 0]);
    }
}

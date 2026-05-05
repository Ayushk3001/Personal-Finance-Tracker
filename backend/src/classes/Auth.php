<?php

namespace Classes;

use Config\Database;
use Utils\Security;

class Auth
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Register a new user
     */
    public function register($username, $email, $password, $firstName, $lastName)
    {
        // Check if user already exists
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('SELECT id FROM users WHERE email = ? OR username = ?');
        $stmt->execute([$email, $username]);

        if ($stmt->rowCount() > 0) {
            return ['success' => false, 'message' => 'User already exists'];
        }

        // Hash password
        $passwordHash = Security::hashPassword($password);

        // Insert new user
        try {
            $stmt = $conn->prepare('
                INSERT INTO users (username, email, password_hash, first_name, last_name, created_at)
                VALUES (?, ?, ?, ?, ?, NOW())
            ');
            $stmt->execute([$username, $email, $passwordHash, $firstName, $lastName]);
            $userId = $conn->lastInsertId();

            // Create default categories for user
            $this->createDefaultCategories($userId);

            return ['success' => true, 'message' => 'User registered successfully', 'userId' => $userId];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'Registration failed: ' . $e->getMessage()];
        }
    }

    /**
     * Login user
     */
    public function login($email, $password)
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('SELECT id, password_hash, username FROM users WHERE email = ? AND is_active = 1');
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user || !Security::verifyPassword($password, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Invalid credentials'];
        }

        // Create session
        $token = Security::generateToken();
        $sessionStmt = $conn->prepare('
            INSERT INTO sessions (id, user_id, token, user_agent, ip_address, expires_at)
            VALUES (?, ?, ?, ?, ?, DATE_ADD(NOW(), INTERVAL 7 DAY))
        ');

        $sessionStmt->execute([
            uniqid(),
            $user['id'],
            $token,
            $_SERVER['HTTP_USER_AGENT'] ?? '',
            Security::getClientIP()
        ]);

        // Update last login
        $updateStmt = $conn->prepare('UPDATE users SET last_login = NOW() WHERE id = ?');
        $updateStmt->execute([$user['id']]);

        return [
            'success' => true,
            'message' => 'Login successful',
            'token' => $token,
            'userId' => $user['id'],
            'username' => $user['username']
        ];
    }

    /**
     * Logout user
     */
    public function logout($token)
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('DELETE FROM sessions WHERE token = ?');
        return $stmt->execute([$token]);
    }

    /**
     * Verify token
     */
    public function verifyToken($token)
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('
            SELECT user_id FROM sessions 
            WHERE token = ? AND expires_at > NOW()
        ');
        $stmt->execute([$token]);
        $session = $stmt->fetch();

        return $session ? $session['user_id'] : null;
    }

    /**
     * Create default categories for new user
     */
    private function createDefaultCategories($userId)
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('
            INSERT INTO categories (user_id, name, type, color, icon)
            SELECT ?, name, type, color, icon FROM default_categories
        ');
        $stmt->execute([$userId]);
    }

    /**
     * Change password
     */
    public function changePassword($userId, $oldPassword, $newPassword)
    {
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare('SELECT password_hash FROM users WHERE id = ?');
        $stmt->execute([$userId]);
        $user = $stmt->fetch();

        if (!$user || !Security::verifyPassword($oldPassword, $user['password_hash'])) {
            return ['success' => false, 'message' => 'Current password is incorrect'];
        }

        $newHash = Security::hashPassword($newPassword);
        $updateStmt = $conn->prepare('UPDATE users SET password_hash = ? WHERE id = ?');
        $updateStmt->execute([$newHash, $userId]);

        return ['success' => true, 'message' => 'Password changed successfully'];
    }
}

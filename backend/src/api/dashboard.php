<?php

namespace Api;

use Classes\Transaction;
use Classes\SavingsGoal;
use Classes\Budget;
use Utils\Response;

class DashboardAPI
{
    public function getData()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            Response::error('Method not allowed', 405);
        }

        $userId = $this->authenticateUser();
        if (!$userId) {
            Response::error('Unauthorized', 401);
        }

        $currentDate = new \DateTime();
        $year = $currentDate->format('Y');
        $month = $currentDate->format('m');

        // Get monthly summary
        $transaction = new Transaction();
        $summary = $transaction->getMonthlySummary($userId, $year, $month);

        // Get budgets
        $budget = new Budget();
        $budgets = $budget->getByUser($userId);

        // Get goals
        $goal = new SavingsGoal();
        $goals = $goal->getByUser($userId);

        // Get recent transactions
        $recentTransactions = $transaction->getByUser($userId, 5);

        $data = [
            'summary' => $summary,
            'budgets' => $budgets,
            'goals' => $goals,
            'recentTransactions' => $recentTransactions
        ];

        Response::success('Dashboard data retrieved', $data);
    }

    private function getToken()
    {
        $headers = getallheaders();
        if (isset($headers['Authorization'])) {
            $parts = explode(' ', $headers['Authorization']);
            if (count($parts) === 2 && $parts[0] === 'Bearer') {
                return $parts[1];
            }
        }
        return null;
    }

    private function authenticateUser()
    {
        $token = $this->getToken();
        if (!$token) {
            return null;
        }

        $auth = new \Classes\Auth();
        return $auth->verifyToken($token);
    }
}

// Route handling
$dashboardAPI = new DashboardAPI();
$dashboardAPI->getData();

<?php

namespace Api;

use Classes\Budget;
use Utils\Response;
use Utils\Validator;

class BudgetAPI
{
    public function getByUser()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            Response::error('Method not allowed', 405);
        }

        $userId = $this->authenticateUser();
        if (!$userId) {
            Response::error('Unauthorized', 401);
        }

        $budget = new Budget();
        $budgets = $budget->getByUser($userId);

        Response::success('Budgets retrieved', $budgets);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Response::error('Method not allowed', 405);
        }

        $userId = $this->authenticateUser();
        if (!$userId) {
            Response::error('Unauthorized', 401);
        }

        $input = json_decode(file_get_contents('php://input'), true);

        $validator = new Validator($input);
        $validator->required('name')->required('limit_amount')->numeric('limit_amount')->positive('limit_amount');

        if (!$validator->passes()) {
            Response::validationError($validator->getErrors());
        }

        $budget = new Budget();
        $result = $budget->create(
            $userId,
            $input['name'],
            $input['limit_amount'],
            $input['period'] ?? 'monthly',
            $input['category_id'] ?? null,
            $input['start_date'] ?? date('Y-m-d')
        );

        if ($result['success']) {
            Response::success($result['message'], ['id' => $result['id']], 201);
        } else {
            Response::error($result['message'], 400);
        }
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'PUT') {
            Response::error('Method not allowed', 405);
        }

        $userId = $this->authenticateUser();
        if (!$userId) {
            Response::error('Unauthorized', 401);
        }

        $id = $this->getIdFromPath();
        $input = json_decode(file_get_contents('php://input'), true);

        $budget = new Budget();
        $result = $budget->update($id, $userId, $input);

        if ($result['success']) {
            Response::success($result['message']);
        } else {
            Response::error($result['message'], 400);
        }
    }

    public function delete()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'DELETE') {
            Response::error('Method not allowed', 405);
        }

        $userId = $this->authenticateUser();
        if (!$userId) {
            Response::error('Unauthorized', 401);
        }

        $id = $this->getIdFromPath();

        $budget = new Budget();
        $result = $budget->delete($id, $userId);

        if ($result['success']) {
            Response::success($result['message']);
        } else {
            Response::error($result['message'], 400);
        }
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

    private function getIdFromPath()
    {
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $parts = explode('/', $path);
        return end($parts);
    }
}

// Route handling
$budgetAPI = new BudgetAPI();
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (preg_match('/\/budgets\/?$/', $path) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $budgetAPI->getByUser();
} elseif (strpos($path, '/budgets/create') !== false) {
    $budgetAPI->create();
} elseif (preg_match('/\/budgets\/\d+$/', $path) && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $budgetAPI->update();
} elseif (preg_match('/\/budgets\/\d+$/', $path) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $budgetAPI->delete();
} else {
    Response::error('Budget endpoint not found', 404);
}

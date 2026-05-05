<?php

namespace Api;

use Classes\SavingsGoal;
use Utils\Response;
use Utils\Validator;

class GoalAPI
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

        $goal = new SavingsGoal();
        $goals = $goal->getByUser($userId);

        Response::success('Goals retrieved', $goals);
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
        $validator->required('name')->required('target_amount')->numeric('target_amount')->positive('target_amount');

        if (!$validator->passes()) {
            Response::validationError($validator->getErrors());
        }

        $goal = new SavingsGoal();
        $result = $goal->create(
            $userId,
            $input['name'],
            $input['target_amount'],
            $input['target_date'] ?? null,
            $input['description'] ?? null,
            $input['priority'] ?? 'medium'
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

        $goal = new SavingsGoal();
        $result = $goal->update($id, $userId, $input);

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

        $goal = new SavingsGoal();
        $result = $goal->update($id, $userId, ['is_active' => 0]);

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
$goalAPI = new GoalAPI();
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (preg_match('/\/goals\/?$/', $path) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $goalAPI->getByUser();
} elseif (strpos($path, '/goals/create') !== false) {
    $goalAPI->create();
} elseif (preg_match('/\/goals\/\d+$/', $path) && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $goalAPI->update();
} elseif (preg_match('/\/goals\/\d+$/', $path) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $goalAPI->delete();
} else {
    Response::error('Goal endpoint not found', 404);
}

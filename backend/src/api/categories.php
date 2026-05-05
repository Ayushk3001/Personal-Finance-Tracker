<?php

namespace Api;

use Classes\Category;
use Utils\Response;
use Utils\Validator;

class CategoryAPI
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

        $type = $_GET['type'] ?? null;
        $category = new Category();
        $categories = $category->getByUser($userId, $type);

        Response::success('Categories retrieved', $categories);
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
        $validator->required('name')->required('type');

        if (!$validator->passes()) {
            Response::validationError($validator->getErrors());
        }

        $category = new Category();
        $result = $category->create(
            $userId,
            $input['name'],
            $input['type'],
            $input['color'] ?? '#000000',
            $input['icon'] ?? null,
            $input['description'] ?? null
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

        $category = new Category();
        $result = $category->update($id, $userId, $input);

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

        $category = new Category();
        $result = $category->delete($id, $userId);

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
$categoryAPI = new CategoryAPI();
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (preg_match('/\/categories\/?$/', $path) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $categoryAPI->getByUser();
} elseif (strpos($path, '/categories/create') !== false) {
    $categoryAPI->create();
} elseif (preg_match('/\/categories\/\d+$/', $path) && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $categoryAPI->update();
} elseif (preg_match('/\/categories\/\d+$/', $path) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $categoryAPI->delete();
} else {
    Response::error('Category endpoint not found', 404);
}

<?php

namespace Api;

use Classes\Auth;
use Utils\Response;
use Utils\Security;
use Utils\Validator;

class AuthAPI
{
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Response::error('Method not allowed', 405);
        }

        $input = json_decode(file_get_contents('php://input'), true);

        $validator = new Validator($input);
        $validator->required('username')->required('email')->required('password')->required('firstName');

        if (!$validator->passes()) {
            Response::validationError($validator->getErrors());
        }

        // Validate email
        if (!Security::validateEmail($input['email'])) {
            Response::validationError(['email' => 'Invalid email format']);
        }

        // Validate password strength
        $passwordStrength = Security::validatePasswordStrength($input['password']);
        if (!$passwordStrength['length'] || !$passwordStrength['uppercase'] || !$passwordStrength['number']) {
            Response::validationError(['password' => 'Password must be at least 8 characters with uppercase and numbers']);
        }

        $auth = new Auth();
        $result = $auth->register(
            $input['username'],
            $input['email'],
            $input['password'],
            $input['firstName'],
            $input['lastName'] ?? ''
        );

        if ($result['success']) {
            Response::success($result['message'], ['userId' => $result['userId']], 201);
        } else {
            Response::error($result['message'], 400);
        }
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Response::error('Method not allowed', 405);
        }

        $input = json_decode(file_get_contents('php://input'), true);

        $validator = new Validator($input);
        $validator->required('email')->required('password');

        if (!$validator->passes()) {
            Response::validationError($validator->getErrors());
        }

        $auth = new Auth();
        $result = $auth->login($input['email'], $input['password']);

        if ($result['success']) {
            Response::success($result['message'], [
                'token' => $result['token'],
                'userId' => $result['userId'],
                'username' => $result['username']
            ]);
        } else {
            Response::error($result['message'], 401);
        }
    }

    public function logout()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            Response::error('Method not allowed', 405);
        }

        $token = $this->getToken();
        if (!$token) {
            Response::error('Unauthorized', 401);
        }

        $auth = new Auth();
        $auth->logout($token);
        Response::success('Logged out successfully');
    }

    public function changePassword()
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
        $validator->required('oldPassword')->required('newPassword');

        if (!$validator->passes()) {
            Response::validationError($validator->getErrors());
        }

        $auth = new Auth();
        $result = $auth->changePassword($userId, $input['oldPassword'], $input['newPassword']);

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

        $auth = new Auth();
        return $auth->verifyToken($token);
    }
}

// Route handling
$authAPI = new AuthAPI();
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (strpos($path, '/register') !== false) {
    $authAPI->register();
} elseif (strpos($path, '/login') !== false) {
    $authAPI->login();
} elseif (strpos($path, '/logout') !== false) {
    $authAPI->logout();
} elseif (strpos($path, '/change-password') !== false) {
    $authAPI->changePassword();
} else {
    Response::error('Auth endpoint not found', 404);
}

<?php

namespace Api;

use Classes\Transaction;
use Utils\Response;
use Utils\Validator;
use Utils\Security;

class TransactionAPI
{
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
        $validator->required('category_id')->required('description')->required('amount')
            ->required('transaction_date')->required('type')->numeric('amount')->positive('amount');

        if (!$validator->passes()) {
            Response::validationError($validator->getErrors());
        }

        $transaction = new Transaction();
        $result = $transaction->create(
            $userId,
            $input['category_id'],
            $input['description'],
            $input['amount'],
            $input['transaction_date'],
            $input['type'],
            $input['payment_method'] ?? null,
            $input['notes'] ?? null
        );

        if ($result['success']) {
            Response::success($result['message'], ['id' => $result['id']], 201);
        } else {
            Response::error($result['message'], 400);
        }
    }

    public function getByUser()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            Response::error('Method not allowed', 405);
        }

        $userId = $this->authenticateUser();
        if (!$userId) {
            Response::error('Unauthorized', 401);
        }

        $limit = (int)($_GET['limit'] ?? 50);
        $offset = (int)($_GET['offset'] ?? 0);

        $filters = [];
        if (!empty($_GET['type'])) $filters['type'] = $_GET['type'];
        if (!empty($_GET['category_id'])) $filters['category_id'] = $_GET['category_id'];
        if (!empty($_GET['start_date'])) $filters['start_date'] = $_GET['start_date'];
        if (!empty($_GET['end_date'])) $filters['end_date'] = $_GET['end_date'];

        $transaction = new Transaction();
        $transactions = $transaction->getByUser($userId, $limit, $offset, $filters);

        Response::success('Transactions retrieved', $transactions);
    }

    public function getById()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            Response::error('Method not allowed', 405);
        }

        $userId = $this->authenticateUser();
        if (!$userId) {
            Response::error('Unauthorized', 401);
        }

        $id = $this->getIdFromPath();
        $transaction = new Transaction();
        $result = $transaction->getById($id, $userId);

        if (!$result) {
            Response::error('Transaction not found', 404);
        }

        Response::success('Transaction retrieved', $result);
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

        $transaction = new Transaction();
        $result = $transaction->update($id, $userId, $input);

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

        $transaction = new Transaction();
        $result = $transaction->delete($id, $userId);

        if ($result['success']) {
            Response::success($result['message']);
        } else {
            Response::error($result['message'], 400);
        }
    }

    public function getMonthlySummary()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            Response::error('Method not allowed', 405);
        }

        $userId = $this->authenticateUser();
        if (!$userId) {
            Response::error('Unauthorized', 401);
        }

        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        preg_match('/summary\/(\d+)\/(\d+)/', $path, $matches);

        if (!$matches) {
            Response::error('Invalid parameters', 400);
        }

        $year = $matches[1];
        $month = $matches[2];

        $transaction = new Transaction();
        $summary = $transaction->getMonthlySummary($userId, $year, $month);

        Response::success('Summary retrieved', $summary);
    }

    public function getSpendingByCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            Response::error('Method not allowed', 405);
        }

        $userId = $this->authenticateUser();
        if (!$userId) {
            Response::error('Unauthorized', 401);
        }

        $startDate = $_GET['start'] ?? date('Y-m-01');
        $endDate = $_GET['end'] ?? date('Y-m-d');

        $validator = new Validator(['start_date' => $startDate, 'end_date' => $endDate]);
        $validator->date('start_date')->date('end_date');

        if (!$validator->passes()) {
            Response::validationError($validator->getErrors());
        }

        $transaction = new Transaction();
        $spending = $transaction->getSpendingByCategory($userId, $startDate, $endDate);

        Response::success('Spending retrieved', $spending);
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
$transactionAPI = new TransactionAPI();
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (preg_match('/\/transactions\/?$/', $path) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $transactionAPI->getByUser();
} elseif (strpos($path, '/transactions/create') !== false) {
    $transactionAPI->create();
} elseif (preg_match('/\/transactions\/summary/', $path)) {
    $transactionAPI->getMonthlySummary();
} elseif (strpos($path, '/transactions/spending') !== false) {
    $transactionAPI->getSpendingByCategory();
} elseif (preg_match('/\/transactions\/\d+$/', $path) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $transactionAPI->getById();
} elseif (preg_match('/\/transactions\/\d+$/', $path) && $_SERVER['REQUEST_METHOD'] === 'PUT') {
    $transactionAPI->update();
} elseif (preg_match('/\/transactions\/\d+$/', $path) && $_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $transactionAPI->delete();
} else {
    Response::error('Transaction endpoint not found', 404);
}

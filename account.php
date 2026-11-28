<?php

use App\Controllers\UserController;
use App\Models\UserModel;

session_start();

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/db.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Please log in first.']);
    exit;
}

$userModel = new UserModel($conn);
$userController = new UserController($userModel);

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $response = $userController->getAccount((int) $_SESSION['user_id']);
} elseif ($method === 'PUT') {
    $data = json_decode(file_get_contents('php://input'), true) ?? [];
    $response = $userController->updateAccount(
        (int) $_SESSION['user_id'],
        $data
    );
} else {
    http_response_code(405);
    $response = ['error' => 'Method not allowed.'];
}

echo json_encode($response);

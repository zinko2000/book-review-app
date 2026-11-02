<?php
session_start();

header('Content-Type: application/json; charset=utf-8');

require_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if ($email === '' || $password === '') {
    http_response_code(400);
    echo json_encode(['error' => 'Email and password are required.']);
    exit;
}

$stmt = $conn->prepare(
    'SELECT user_id, name, email, password_hash FROM users WHERE email = ?'
);

$stmt->bind_param('s', $email);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows !== 1) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid email or password.']);
    exit;
}

$user = $result->fetch_assoc();

if (!password_verify($password, $user['password_hash'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid email or password.']);
    exit;
}

session_regenerate_id(true);

$_SESSION['user_id'] = $user['user_id'];
$_SESSION['user_name'] = $user['name'];
$_SESSION['user_email'] = $user['email'];

echo json_encode([
    'message' => 'Login successful!',
    'user' => [
        'user_id' => $user['user_id'],
        'name' => $user['name'],
        'email' => $user['email']
    ]
]);
?>
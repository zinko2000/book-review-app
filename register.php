<?php
header('Content-Type: application/json; charset=utf-8');

require_once 'db.php';

$data = json_decode(file_get_contents('php://input'), true);

$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';

if ($name === '' || $email === '' || $password === '') {
    http_response_code(400);
    echo json_encode(['error' => 'All fields are required.']);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid email address.']);
    exit;
}

if (strlen($password) < 8) {
    http_response_code(400);
    echo json_encode(['error' => 'Password must be at least 8 characters.']);
    exit;
}

$check = $conn->prepare('SELECT user_id FROM users WHERE email = ?');
$check->bind_param('s', $email);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    http_response_code(409);
    echo json_encode(['error' => 'Email already registered.']);
    exit;
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare(
    'INSERT INTO users (name, email, password_hash) VALUES (?, ?, ?)'
);

$stmt->bind_param('sss', $name, $email, $passwordHash);

if ($stmt->execute()) {
    http_response_code(201);
    echo json_encode(['message' => 'Account created successfully.']);
} else {
    http_response_code(500);
    echo json_encode(['error' => 'Registration failed.']);
}
?>
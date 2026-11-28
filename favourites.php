<?php
session_start();
header('Content-Type: application/json; charset=utf-8');

$userId = $_SESSION['user_id'] ?? null;
if (!$userId) {
    http_response_code(401);
    echo json_encode(['error' => 'Log in to use favourites.']);
    exit;
}

require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $conn->prepare(
        'SELECT books.book_id, books.title FROM favourites
         JOIN books ON books.book_id = favourites.book_id
         WHERE favourites.user_id = ? ORDER BY books.title'
    );
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    echo json_encode($stmt->get_result()->fetch_all(MYSQLI_ASSOC));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
    exit;
}

if (($_SERVER['CONTENT_TYPE'] ?? '') !== 'application/json') {
    http_response_code(415);
    echo json_encode(['error' => 'Send JSON data.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$bookId = filter_var($data['book_id'] ?? null, FILTER_VALIDATE_INT);
$action = $data['action'] ?? '';

if ($bookId <= 0 || !in_array($action, ['add', 'remove'], true)) {
    http_response_code(400);
    echo json_encode(['error' => 'Choose a valid book and action.']);
    exit;
}

if ($action === 'add') {
    $stmt = $conn->prepare(
        'INSERT INTO favourites (user_id, book_id)
         SELECT ?, book_id FROM books WHERE book_id = ?
         ON DUPLICATE KEY UPDATE favourites.book_id = favourites.book_id'
    );
} else {
    $stmt = $conn->prepare('DELETE FROM favourites WHERE user_id = ? AND book_id = ?');
}

$stmt->bind_param('ii', $userId, $bookId);
$stmt->execute();
echo json_encode(['success' => true]);

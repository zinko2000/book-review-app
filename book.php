<?php
header("Content-Type: application/json; charset=utf-8");

require "db.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);

if (!$id || $id < 1) {
    http_response_code(400);
    echo json_encode(["error" => "A valid book ID is required."]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM books WHERE book_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$book = $stmt->get_result()->fetch_assoc();

if (!$book) {
    http_response_code(404);
    echo json_encode(["error" => "Book not found."]);
    exit;
}

echo json_encode($book);
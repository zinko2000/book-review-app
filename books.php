
<?php
header("Content-Type: application/json; charset=utf-8");

require "db.php";

$result = $conn->query("SELECT * FROM books ORDER BY RAND() LIMIT 6");

$books = [];

while ($book = $result->fetch_assoc()) {
    $books[] = $book;
}

echo json_encode($books);

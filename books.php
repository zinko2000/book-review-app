
<?php
header("Content-Type: application/json; charset=utf-8");

require "db.php";

$result = $conn->query("SELECT * FROM books ORDER BY RAND()");

$books = [];

while ($book = $result->fetch_assoc()) {
    $books[] = $book;
}

echo json_encode($books);

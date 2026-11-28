<?php
use App\Controllers\ReviewController;
use App\Models\ReviewModel;

session_start();
header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

$method = $_SERVER['REQUEST_METHOD'];
if (!in_array($method, ['GET', 'POST', 'PUT', 'DELETE'], true)) {
    header('Allow: GET, POST, PUT, DELETE');
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed.']);
    exit;
}
$userId = isset($_SESSION['user_id']) ? (int) $_SESSION['user_id'] : null;
if (($method !== 'GET' || isset($_GET['mine'])) && !$userId) {
    http_response_code(401);
    echo json_encode(['error' => 'Please log in to manage your reviews.']);
    exit;
}
if ($userId && !isset($_SESSION['review_csrf'])) {
    $_SESSION['review_csrf'] = bin2hex(random_bytes(32));
}
if ($method !== 'GET') {
    $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';
    if (!hash_equals($_SESSION['review_csrf'], $token)) {
        http_response_code(403);
        echo json_encode(['error' => 'Your session changed. Refresh the page and try again.']);
        exit;
    }
}

try {
    require_once __DIR__ . '/vendor/autoload.php';
    require_once __DIR__ . '/db.php';
    $controller = new ReviewController(new ReviewModel($conn));
    if ($method === 'GET') {
        if (isset($_GET['mine'])) {
            $response = $controller->listForUser($userId);
        } else {
            $bookId = ReviewController::positiveId($_GET['book_id'] ?? null);
            if (!$bookId) {
                http_response_code(400);
                $response = ['error' => 'A valid book ID is required.'];
            } else {
                $response = $controller->listForBook($bookId);
            }
        }
        $response['current_user_id'] = $userId;
        $response['csrf_token'] = $userId ? $_SESSION['review_csrf'] : null;
    } else {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!is_array($data)) {
            http_response_code(400);
            $response = ['error' => 'Send a valid JSON object.'];
        } elseif ($method === 'DELETE') {
            $response = $controller->delete($userId, $data);
        } else {
            $response = $controller->save($method, $userId, $data);
        }
    }
    echo json_encode($response);
} catch (\mysqli_sql_exception $error) {
    if ($error->getCode() === 1062) {
        http_response_code(409);
        echo json_encode(['error' => 'You already reviewed this book. Edit your existing review instead.']);
    } else {
        error_log((string) $error);
        http_response_code(500);
        echo json_encode(['error' => 'Could not access reviews. Check the database setup and try again.']);
    }
} catch (\Throwable $error) {
    error_log((string) $error);
    http_response_code(500);
    echo json_encode(['error' => 'Could not load reviews. Check that Composer dependencies are installed.']);
}

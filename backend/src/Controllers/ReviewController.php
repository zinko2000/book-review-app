<?php
namespace App\Controllers;

use App\Models\ReviewModel;

class ReviewController
{
    private ReviewModel $reviews;

    public function __construct(ReviewModel $reviews)
    {
        $this->reviews = $reviews;
    }

    public static function positiveId($value): ?int
    {
        if (!is_int($value) && !is_string($value)) {
            return null;
        }
        $id = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        return $id === false ? null : $id;
    }

    private function fail(int $status, string $message): array
    {
        http_response_code($status);
        return ['error' => $message];
    }

    public function listForBook(int $bookId): array
    {
        if (!$this->reviews->bookExists($bookId)) {
            return $this->fail(404, 'Book not found.');
        }
        $reviews = $this->reviews->forBook($bookId);
        $count = count($reviews);
        $total = array_sum(array_column($reviews, 'rating'));
        return [
            'reviews' => $reviews,
            'review_count' => $count,
            'average_rating' => $count ? round($total / $count, 1) : null
        ];
    }

    public function listForUser(int $userId): array
    {
        return ['reviews' => $this->reviews->forUser($userId)];
    }

    public function save(string $method, int $userId, array $data): array
    {
        $rating = $data['rating'] ?? null;
        $text = $data['review_text'] ?? null;
        if (!is_int($rating) || $rating < 1 || $rating > 5) {
            return $this->fail(422, 'Choose a whole-number rating from 1 to 5.');
        }
        if (!is_string($text) || trim($text) === '' || preg_match_all('/./us', trim($text)) > 1000) {
            return $this->fail(422, 'Write a review of 1 to 1000 characters.');
        }
        $text = trim($text);
        if ($method === 'POST') {
            $bookId = self::positiveId($data['book_id'] ?? null);
            if (!$bookId) {
                return $this->fail(400, 'A valid book ID is required.');
            }
            if (!$this->reviews->bookExists($bookId)) {
                return $this->fail(404, 'Book not found.');
            }
            $this->reviews->create($bookId, $userId, $rating, $text);
            http_response_code(201);
            return ['message' => 'Your review has been posted.'];
        }

        $reviewId = self::positiveId($data['review_id'] ?? null);
        if (!$reviewId) {
            return $this->fail(400, 'A valid review ID is required.');
        }
        if (!$this->reviews->ownedBy($reviewId, $userId)) {
            return $this->fail(404, 'Review not found in your account.');
        }
        $this->reviews->update($reviewId, $userId, $rating, $text);
        return ['message' => 'Your review has been updated.'];
    }

    public function delete(int $userId, array $data): array
    {
        $reviewId = self::positiveId($data['review_id'] ?? null);
        if (!$reviewId) {
            return $this->fail(400, 'A valid review ID is required.');
        }
        if (!$this->reviews->ownedBy($reviewId, $userId)) {
            return $this->fail(404, 'Review not found in your account.');
        }
        $this->reviews->delete($reviewId, $userId);
        return ['message' => 'Your review has been deleted.'];
    }
}

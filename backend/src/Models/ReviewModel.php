<?php
namespace App\Models;

class ReviewModel
{
    private \mysqli $db;

    public function __construct(\mysqli $db)
    {
        $this->db = $db;
    }

    public function forBook(int $bookId): array
    {
        $stmt = $this->db->prepare(
            'SELECT r.review_id, r.book_id, r.user_id, r.rating, r.review_text,
                    r.created_at, r.updated_at, u.name
             FROM reviews r JOIN users u ON u.user_id = r.user_id
             WHERE r.book_id = ? ORDER BY r.created_at DESC, r.review_id DESC'
        );
        $stmt->bind_param('i', $bookId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function forUser(int $userId): array
    {
        $stmt = $this->db->prepare(
            'SELECT r.review_id, r.book_id, r.user_id, r.rating, r.review_text,
                    r.created_at, r.updated_at, b.title
             FROM reviews r JOIN books b ON b.book_id = r.book_id
             WHERE r.user_id = ? ORDER BY r.updated_at DESC, r.review_id DESC'
        );
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function bookExists(int $bookId): bool
    {
        $stmt = $this->db->prepare('SELECT book_id FROM books WHERE book_id = ?');
        $stmt->bind_param('i', $bookId);
        $stmt->execute();
        return $stmt->get_result()->num_rows === 1;
    }

    public function create(int $bookId, int $userId, int $rating, string $text): void
    {
        $stmt = $this->db->prepare(
            'INSERT INTO reviews (book_id, user_id, rating, review_text) VALUES (?, ?, ?, ?)'
        );
        $stmt->bind_param('iiis', $bookId, $userId, $rating, $text);
        $stmt->execute();
    }

    public function ownedBy(int $reviewId, int $userId): bool
    {
        $stmt = $this->db->prepare('SELECT review_id FROM reviews WHERE review_id = ? AND user_id = ?');
        $stmt->bind_param('ii', $reviewId, $userId);
        $stmt->execute();
        return $stmt->get_result()->num_rows === 1;
    }

    public function update(int $reviewId, int $userId, int $rating, string $text): void
    {
        // Ownership stays in the UPDATE too; never trust a user ID from the browser.
        $stmt = $this->db->prepare(
            'UPDATE reviews SET rating = ?, review_text = ? WHERE review_id = ? AND user_id = ?'
        );
        $stmt->bind_param('isii', $rating, $text, $reviewId, $userId);
        $stmt->execute();
    }

    public function delete(int $reviewId, int $userId): void
    {
        $stmt = $this->db->prepare('DELETE FROM reviews WHERE review_id = ? AND user_id = ?');
        $stmt->bind_param('ii', $reviewId, $userId);
        $stmt->execute();
    }
}

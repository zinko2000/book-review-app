<?php

namespace App\Models;

use mysqli;

class UserModel
{
    private mysqli $connection;

    public function __construct(mysqli $connection)
    {
        $this->connection = $connection;
    }

    public function findById(int $userId): ?array
    {
        $sql = "SELECT user_id, name, email, password_hash
                FROM users
                WHERE user_id = ?";

        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        $result = $stmt->get_result();

        return $result->fetch_assoc() ?: null;
    }

    public function emailExists(string $email, int $userId): bool
    {
        $sql = "SELECT user_id
                FROM users
                WHERE email = ? AND user_id != ?";

        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("si", $email, $userId);
        $stmt->execute();

        return $stmt->get_result()->num_rows > 0;
    }

    public function updateDetails(
        int $userId,
        string $name,
        string $email
    ): bool {
        $sql = "UPDATE users
                SET name = ?, email = ?
                WHERE user_id = ?";

        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("ssi", $name, $email, $userId);

        return $stmt->execute();
    }

    public function updatePassword(
        int $userId,
        string $password
    ): bool {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE users
                SET password_hash = ?
                WHERE user_id = ?";

        $stmt = $this->connection->prepare($sql);
        $stmt->bind_param("si", $passwordHash, $userId);

        return $stmt->execute();
    }
}


<?php

namespace App\Services;

class Validator
{
    public static function validateAccount(array $data): array
    {
        $errors = [];

        $name = trim($data['name'] ?? '');
        $email = trim($data['email'] ?? '');

        if (empty($name)) {
            $errors[] = "Username is required.";
        }

        if (strlen($name) > 100) {
            $errors[] = "Username cannot exceed 100 characters.";
        }

        if (empty($email)) {
            $errors[] = "Email is required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Please enter a valid email address.";
        }

        return $errors;
    }

    public static function validatePassword(string $password): array
    {
        $errors = [];

        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters.";
        }

        return $errors;
    }
}

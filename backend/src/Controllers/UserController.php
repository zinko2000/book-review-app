
<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Services\Validator;

class UserController
{
    private UserModel $userModel;

    public function __construct(UserModel $userModel)
    {
        $this->userModel = $userModel;
    }

    // Retrieve the logged-in user's account details
    public function getAccount(int $userId): array
    {
        $user = $this->userModel->findById($userId);

        if (!$user) {
            http_response_code(404);
            return ['error' => 'User not found.'];
        }

        unset($user['password_hash']);

        return ['user' => $user];
    }

    // Update the user's account details
    public function updateAccount(int $userId, array $data): array
    {
        $errors = Validator::validateAccount($data);

        if (!empty($errors)) {
            http_response_code(400);
            return ['errors' => $errors];
        }

        $name = trim($data['name']);
        $email = trim($data['email']);

        if ($this->userModel->emailExists($email, $userId)) {
            http_response_code(409);
            return ['error' => 'This email is already registered.'];
        }

        // Verify the current password before changing it
        $newPassword = $data['new_password'] ?? '';

        if ($newPassword !== '') {
            $currentPassword = $data['current_password'] ?? '';
            $user = $this->userModel->findById($userId);

            if (
                !$user ||
                !password_verify($currentPassword, $user['password_hash'])
            ) {
                http_response_code(401);
                return ['error' => 'Current password is incorrect.'];
            }

            $passwordErrors = Validator::validatePassword($newPassword);

            if (!empty($passwordErrors)) {
                http_response_code(400);
                return ['errors' => $passwordErrors];
            }
        }

        if (!$this->userModel->updateDetails($userId, $name, $email)) {
            http_response_code(500);
            return ['error' => 'Could not update account details.'];
        }

        if ($newPassword !== '') {
            if (!$this->userModel->updatePassword($userId, $newPassword)) {
                http_response_code(500);
                return ['error' => 'Could not update password.'];
            }
        }

        // Refresh the session information
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;

        return [
            'message' => 'Account updated successfully.',
            'user' => [
                'user_id' => $userId,
                'name' => $name,
                'email' => $email
            ]
        ];
    }
}

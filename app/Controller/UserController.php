<?php

namespace StepTracker\Controller;

use StepTracker\Service\UserService;

class UserController
{
    private UserService $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    private function render(string $template, array $data = []): void
    {
        extract($data);
        include __DIR__ . '/../View/' . $template;
    }

    public function editPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $currentPasswordSubmission = $_POST['current_password'] ?? '';
            $newPasswordSubmission = $_POST['new_password'] ?? '';
            $confirmNewPassword = $_POST['confirm_password'] ?? '';

            $serviceValidator = $this->userService->isPasswordUpdateValid(
                $currentPasswordSubmission, 
                $newPasswordSubmission, 
                $confirmNewPassword
            );

            if ($serviceValidator[0]) {
                $this->userService->updatePassword($newPasswordSubmission);
                
                $_SESSION = [];
                session_destroy();
                header('Location: index.php?page=login');
                exit;
            }

            $_SESSION['error'] = $serviceValidator[1];
            header('Location: index.php?page=edit-password');
            exit;
        }

        if ($this->userService->isInPasswordCooldown()) {
            $_SESSION['isInPasswordCooldown'] = 1;
        }

        $this->render('edit-password.php', []);
    }

    public function editNickname()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $newNickname = $_POST['new_nickname'] ?? '';

            $serviceValidator = $this->userService->isNicknameUpdateValid(
                $newNickname
            );

            if ($serviceValidator[0]) {
                $this->userService->updateNickname($newNickname);
                
                header('Location: index.php?page=account');
                exit;
            }

            $_SESSION['error'] = $serviceValidator[1];
            header('Location: index.php?page=edit-nickname');
            exit;
        }

        $this->render('edit-nickname.php', []);
    }
}

?>
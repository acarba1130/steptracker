<?php

namespace StepTracker\Controller;

use StepTracker\Service\LoginService;

class LoginController
{
    private string $username;
    private string $password;
    private string $option;
    private string $successfulLoginID;
    private LoginService $loginService;

    public function __construct()
    {
        $this->loginService = new LoginService();
    }

    public function login()
    {
        if (isset($_SESSION['user']['user_id'])) {
            header('Location: index.php?page=home');
            exit;
        }

        include __DIR__ . '/../View/login.php';
    }

    public function handleLogin()
    {
        if (isset($_SESSION['user']['user_id'])) {
            header('Location: index.php?page=home');
            exit;
        }

        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        $this->loginService->setUsername($username);
        $this->loginService->setPassword($password);

        $this->loginService->authenticateLogin();

        $validAuth = $this->loginService->getAuthenticationResult();

        if ($validAuth) {
            session_regenerate_id(true);
            $_SESSION['user']['user_id'] = $this->loginService->getUserID();
            $_SESSION['user']['username'] = $username;
            $_SESSION['user']['is_admin'] = $this->loginService->getAdminStatus();
            $_SESSION['user']['last_activity'] = time();

            //set session token to prevent user being logged in multiple times
            $sessionToken = bin2hex(random_bytes(32));
            $_SESSION['user']['session_token'] = $sessionToken;
            $this->loginService->updateSessionToken($sessionToken);

            header('Location: index.php?page=home');
            exit;
        } else {
            $_SESSION['error'] = 'Invalid credentials';
            header('Location: index.php?page=login');
            exit;
        }
    }

    public function logout()
    {
        $_SESSION = [];
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }
}

?>
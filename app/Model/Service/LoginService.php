<?php

namespace StepTracker\Service;

use StepTracker\Repository\LoginRepository;

class LoginService
{
    private $username;
    private $password;
    private $isLoginValid = false;
    private string $successfulLoginID;
    private bool $isAdmin = false;
    private LoginRepository $loginRepository;

    public function __construct($username = '', $password = '')
    {
        $this->loginRepository = new LoginRepository();        
    }

    public function authenticateLogin()
    {
        if ($username = '' || $password = '') {
            return;
        }

        $result = $this->loginRepository->searchUsernamePassword($this->username, $this->password);

        if ($result === null) {
            return;
        }

        if (password_verify($this->password, $result['hashed_password'])) {
            $this->successfulLoginID = $result['user_id'];
            $this->isLoginValid = true;
        } else {
            return;
        }
    }

    public function updateSessionToken($sessionToken)
    {
        $this->loginRepository->updateSessionToken($_SESSION['user']['user_id'], $sessionToken);
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getAuthenticationResult()
    {
        return $this->isLoginValid;
    }

    public function getAdminStatus()
    {
        return $this->isAdmin;
    }

    public function getUserID()
    {
        return $this->successfulLoginID;
    }
}

?>
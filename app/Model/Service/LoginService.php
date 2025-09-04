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

        if (trim($this->username) === '' || trim($this->password) === '') {
            $this->logLoginAttempt(false, 'Empty username or password');
            return;
        }

        $result = $this->loginRepository->searchUsernamePassword($this->username);

        if ($result === null) {
            $this->logLoginAttempt(false, 'User not found');
            return;
        }

        if (password_verify($this->password, $result['hashed_password'])) {
            $this->successfulLoginID = $result['user_id'];
            $this->isLoginValid = true;
            $this->logLoginAttempt(true);
        } else {
            $this->logLoginAttempt(false, 'Invalid credentials');
            return;
        }
    }

    public function checkFailedLogInAttempts()
    {
        if ($this->failedLoginAttemptsMoreThanTenByIp()) {
            $this->addBlockedIp();
        }
    }

    public function failedLoginAttemptsMoreThanTenByIp()
    {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

        $loginAttemptsLastHour = (int)$this->loginRepository->getFailedLoginAttemptsLastHourByIp($ipAddress)['COUNT(*)'];

        return ($loginAttemptsLastHour > 10);
    }

    public function addBlockedIp()
    {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $this->loginRepository->addBlockedIp($ipAddress);
    }

    public function logLoginAttempt($success, $failureReason = null)
    {
        //attempted_username,ip_address,user_agent,success,failure_reason
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'unknown';

        $this->loginRepository->logLoginAttempt($this->username, $ipAddress, $userAgent, ($success ? 1 : 0), $failureReason);
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

    public function setSessionVariables()
    {
        $_SESSION['user']['user_id'] = $this->getUserID();
        $_SESSION['user']['username'] = $this->username;
        $_SESSION['user']['is_admin'] = $this->getAdminStatus();
        $_SESSION['user']['last_activity'] = time();

        //set session token to prevent user being logged in multiple times
        $sessionToken = bin2hex(random_bytes(32));
        $_SESSION['user']['session_token'] = $sessionToken;
        $this->updateSessionToken($sessionToken);
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
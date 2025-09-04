<?php

namespace StepTracker\Repository;

use StepTracker\Pdo\DatabasePdo;

class LoginRepository
{
    private DatabasePdo $databasePdo;

    public function __construct()
    {
        $this->databasePdo = new DatabasePdo();
    }

    public function searchUsernamePassword($username)
    {
        $result = $this->databasePdo->selectOne(
            "SELECT user_id, username, hashed_password FROM users WHERE username = ?;", 
            [$username]
        );

        return $result;
    }

    public function searchUsername($username)
    {
        $result = $this->databasePdo->selectOne(
            "SELECT * FROM users WHERE username = ?;", 
            [$username]
        );

        return $result;
    }

    public function updateSessionToken($userId, $sessionToken)
    {
        $this->databasePdo->execute(
            "UPDATE users SET session_token = ? WHERE user_id = ?;",
            [$sessionToken, $userId]
        );
    }

    public function logLoginAttempt($attemptedUsername, $ipAddress, $userAgent, $success, $failure_reason)
    {
        $this->databasePdo->execute(
            "INSERT INTO login_attempts (attempted_username,ip_address,user_agent,success,failure_reason) 
            VALUES (?,?,?,?,?);",
            [$attemptedUsername, $ipAddress, $userAgent, $success, $failure_reason]
        );
    }

    public function getFailedLoginAttemptsLastHourByIp($ipAddress)
    {
        $result = $this->databasePdo->selectOne(
            "SELECT COUNT(*) FROM login_attempts 
            WHERE ip_address = ? 
            AND success = 0
            AND timestamp > NOW() - INTERVAL 1 HOUR;", 
            [$ipAddress]
        );

        return $result;
    }

    public function addBlockedIp($ipAddress)
    {
        $this->databasePdo->execute(
            "INSERT INTO blocked_ips (ip_address) 
            VALUES (?);",
            [$ipAddress]
        );
    }
}

?>
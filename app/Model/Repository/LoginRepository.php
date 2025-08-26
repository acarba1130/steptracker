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

    public function searchUsernamePassword($username, $hashed_password)
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
}

?>
<?php

namespace StepTracker\Repository;

use StepTracker\Pdo\DatabasePdo;

class UserRepository
{
    private DatabasePdo $databasePdo;

    public function __construct()
    {
        $this->databasePdo = new DatabasePdo();
    }

    public function getAllUsers()
    {
        $result = $this->databasePdo->select(
            "SELECT * FROM users"
        );

        return $result;
    }

    public function getUserByID($id)
    {
        $result = $this->databasePdo->selectOne(
            "SELECT * FROM users WHERE user_id = ?;", 
            [$id]
        );

        return $result;
    }

    public function getUserTeamName($id)
    {
        $result = $this->databasePdo->selectOne(
            "SELECT t.name as 'teamname'
            FROM users u 
                JOIN teams t on u.team_id = t.team_id
            WHERE user_id = ?;", 
            [$id]
        );

        return $result;
    }

    public function updatePassword($userId, $oldPW, $newPW)
    {
        $this->databasePdo->execute(
            "UPDATE users SET hashed_password = ? WHERE user_id = ? AND hashed_password = ?;",
            [$newPW, $userId, $oldPW]
        );
    }

    public function logPasswordChange($userId)
    {
        $this->databasePdo->execute(
            "INSERT INTO password_changes (user_id) VALUES (?);",
            [$userId]
        );
    }

    public function passwordChangesLastHour($userId, $changedAt)
    {
        $result = $this->databasePdo->selectOne(
            "SELECT COUNT(*) FROM password_changes WHERE user_id = ? AND changed_at >= ?;",
            [$userId, $changedAt]
        );

        return $result;
    }

    public function checkIfNicknameExists($nickname)
    {
        $result = $this->databasePdo->selectOne(
            "SELECT 1 FROM users WHERE nickname = ? LIMIT 1;", 
            [$nickname]
        );

        return $result;
    }

    public function updateNickname($newNickname, $userId)
    {
        $this->databasePdo->execute(
            "UPDATE users SET nickname = ? WHERE user_id = ?",
            [$newNickname, $userId]
        );
    }
}

?>
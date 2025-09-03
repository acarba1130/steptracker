<?php

namespace StepTracker\Repository;

use StepTracker\Pdo\DatabasePdo;

class LogStepsRepository
{
    private DatabasePdo $databasePdo;

    public function __construct()
    {
        $this->databasePdo = new DatabasePdo();
    }

    public function isDateLogged($userId, $date)
    {
        $result = $this->databasePdo->selectOne(
            "SELECT COUNT(*) FROM steps WHERE user_id = ? AND entry_date = ?;", 
            [$userId, $date]
        );

        return $result;
    }

    public function insertStepLog($userID, $date, $stepCount)
    {
        $this->databasePdo->execute(
            "INSERT INTO steps (entry_date,steps_count,user_id) 
            VALUES (?,?,?);",
            [$date, $stepCount, $userID]
        );
    }

    public function updateStepLog($userID, $date, $stepCount)
    {
        $this->databasePdo->execute(
            "UPDATE steps SET steps_count = ? WHERE user_id = ? AND entry_date = ?;",
            [$stepCount, $userID, $date]
        );
    }
}

?>
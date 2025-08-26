<?php

namespace StepTracker\Repository;

use StepTracker\Pdo\DatabasePdo;

class HomeRepository
{
    private DatabasePdo $databasePdo;

    public function __construct()
    {
        $this->databasePdo = new DatabasePdo();
    }

    public function getTeamsAndStepCount()
    {
        $result = $this->databasePdo->select(
            "SELECT t.name AS 'teamname', IFNULL(SUM(steps_count), 0) AS 'stepcount'
            FROM teams t
            	LEFT JOIN users u on t.team_id = u.team_id
                LEFT JOIN steps s on u.user_id = s.user_id
            GROUP BY t.team_id
            ORDER BY SUM(steps_count) DESC;"
        );

        return $result;
    }

    public function getUserAndStepCount()
    {
        $result = $this->databasePdo->select(
            "SELECT IFNULL(u.nickname,u.username) AS 'name', IFNULL(SUM(steps_count), 0) AS 'stepcount'
            FROM users u 
                LEFT JOIN steps s ON u.user_id = s.user_id
            GROUP BY u.user_id
            ORDER BY SUM(steps_count) DESC;"
        );

        return $result;
    }
}

?>
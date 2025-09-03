<?php

namespace StepTracker\Service;

use StepTracker\Repository\LogStepsRepository;
use DateTime;

class LogStepsService
{
    private LogStepsRepository $logStepsRepository;

    public function __construct()
    {
        $this->logStepsRepository = new LogStepsRepository();        
    }

    public function isDateValid($dateToLog)
    {
        // 1. Check format using regex: YYYY-MM-DD
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dateToLog)) {
            return false;
        }

        // 2. Convert to DateTime and check if valid
        $dt = DateTime::createFromFormat('Y-m-d', $dateToLog);
        if (!$dt || $dt->format('Y-m-d') !== $dateToLog) {
            return false;
        }

        // 3. Check year and month
        if ($dt->format('Y') !== '2025' || $dt->format('m') !== '09') {
            return false;
        }

        if ($dateToLog > date('Y-m-d')) {
            return false;
        }

        return true;
    }

    public function isStepCountValid($stepCount)
    {
        $error = '';

        if (filter_var($stepCount, FILTER_VALIDATE_INT, ["options" => ["min_range" => 0]]) === false) {
            $error = 'Invalid step count input.';
        }

        return [($error === ''), $error];
    }

    public function logStep($stepCount)
    {
        $userID = $_SESSION['user']['user_id'];
        $date = $_SESSION['date'];
        $stepsRecordAssoc = $this->logStepsRepository->isDateLogged($userID, $date);

        if ($stepsRecordAssoc['COUNT(*)'] === 0) {
            $this->logStepsRepository->insertStepLog($userID, $date, $stepCount);
        } else {
            $this->logStepsRepository->updateStepLog($userID, $date, $stepCount);
        }
    }
}

?>
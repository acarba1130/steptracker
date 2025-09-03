<?php

namespace StepTracker\Controller;

use StepTracker\Service\LogStepsService;

class LogStepsController
{
    private LogStepsService $logStepsService;

    public function __construct()
    {
        $this->logStepsService = new LogStepsService();
    }

    private function render(string $template, array $data = []): void
    {
        extract($data);
        include __DIR__ . '/../View/' . $template;
    }

    public function logStep()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!$this->logStepsService->isDateValid($_POST['date'])) {
                $_SESSION['error'] = 'Attempting to log invalid date.';
                header('Location: index.php?page=log-steps');
                exit();
            }

            $_SESSION['date'] = $_POST['date'];
            header('Location: index.php?page=log-step');
            exit;
        }

        if (!isset($_SESSION['date'])) {
            $this->render('error.php');
            exit;
        }

        $date = $_SESSION['date'];

        $this->render('log-step.php', ['date' => $date]);
    }

    public function logStepSubmit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->render('error.php');
            exit;
        }

        if (!isset($_SESSION['user']['user_id']) || !isset($_SESSION['date'])) {
            $this->render('error.php');
            exit; 
        }

        $stepCount = $_POST['stepCount'] ?? 0;

        $isStepCountValid = $this->logStepsService->isStepCountValid($stepCount);

        if (!$isStepCountValid[0]) {
            $_SESSION['error'] = $isStepCountValid[1];
            header('Location: index.php?page=log-step');
            exit;
        }

        $this->logStepsService->logStep($stepCount);
        unset($_SESSION['date']);
        header('Location: index.php?page=log-steps');
    }
}

?>
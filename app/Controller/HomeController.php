<?php

namespace StepTracker\Controller;

use StepTracker\Service\UserService;
use StepTracker\Service\HomeService;

class HomeController
{
    private UserService $userService;
    private HomeService $homeService;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->homeService = new HomeService();
    }

    private function render(string $template, array $data = []): void
    {
        extract($data);
        include __DIR__ . '/../View/' . $template;
    }

    public function home(): void
    {
        $teamStepCount = $this->homeService->getTeamsAndStepCount() ?? [
            [
                'teamname' => 'Team Alpha',
                'stepcount' => '45000'
            ],
            [
                'teamname' => 'Team Beta',
                'stepcount' => '42300'
            ],
            [
                'teamname' => 'Team Gamma',
                'stepcount' => '39000'
            ],
        ];

        $userStepCount = $this->homeService->getUserAndStepCount() ?? [
            [
                'name' => 'Alice',
                'stepcount' => '15000'
            ],
            [
                'name' => 'Bob',
                'stepcount' => '14200'
            ],
            [
                'name' => 'Charlie',
                'stepcount' => '13600'
            ],
        ];
        
        $this->render('home.php', [
            'teamStepCount' => $teamStepCount,
            'userStepCount' => $userStepCount
        ]);
    }

    public function logSteps()
    {
        $loggedSteps = $this->userService->getUserLoggedSteps();

        // Dates to show for logging
        $datesToShow = [];

        $year = 2025;
        $month = 9;

        // Get number of days in September 2025
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);

        for ($day = 1; $day <= $daysInMonth; $day++) {
            // Format with leading zeros: YYYY-MM-DD
            $datesToShow[] = sprintf('%04d-%02d-%02d', $year, $month, $day);
        }

        $this->render('log-steps.php', [
            'loggedSteps' => $loggedSteps,
            'datesToShow' => $datesToShow
        ]);
    }

    public function account(): void
    {
        $user = $this->userService->getCurrectUser($_SESSION['user']['user_id']);
        $userTeamName = $this->userService->getUserTeamName($_SESSION['user']['user_id']);

        $this->render('account.php', [
            'teamName' => $userTeamName['teamname'] ?? "Green Striders",
            'username' => $user['username'] ?? "johndoe",
            'nickname' => $user['nickname'] ?? "Not set"
        ]);
    }

    public function error(): void
    {
        $this->render('error.php', [
            'errorMessage' => 'Error'
        ]);
    }
}

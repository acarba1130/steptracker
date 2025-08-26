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
        // Dummy logged steps by date (YYYY-MM-DD)
        $loggedSteps = [
            '2025-08-20' => 10500,
            '2025-08-22' => 8700,
            '2025-08-24' => 12000,
        ];

        // Dates to show for logging

        $datesToShow = [];

        if ($_SESSION['user']['user_id'] === '1') {
            $datesToShow = array_merge($datesToShow, [
                '2025-08-17',
                '2025-08-18',
                '2025-08-19',
                '2025-08-20',
                '2025-08-21',
                '2025-08-22',
                '2025-08-23',
                '2025-08-24',
                '2025-08-25',
                '2025-08-26',
                '2025-08-27',
                '2025-08-28',
                '2025-08-29',
                '2025-08-30',
                '2025-08-31',
            ]);
        }

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
            'nickname' => $user['nickname'] ?? "Johnny"
        ]);
    }

    public function error(): void
    {
        $this->render('error.php', [
            'errorMessage' => 'Error'
        ]);
    }
}

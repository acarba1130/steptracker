<?php

namespace StepTracker\Service;

use StepTracker\Repository\HomeRepository;

class HomeService
{
    private HomeRepository $homeRepository;

    public function __construct()
    {
        $this->homeRepository = new HomeRepository();        
    }

    public function getTeamsAndStepCount()
    {
        return $this->homeRepository->getTeamsAndStepCount();
    }

    public function getUserAndStepCount()
    {
        return $this->homeRepository->getUserAndStepCount();
    }
}

?>
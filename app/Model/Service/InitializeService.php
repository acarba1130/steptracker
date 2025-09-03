<?php

namespace StepTracker\Service;

use StepTracker\Pdo\DatabasePdo;

class InitializeService
{
    private static bool $filesIncluded = false;

    public function loadFiles()
    {
        if (!self::$filesIncluded) {
            include_once __DIR__ . '/../Pdo/DatabasePdo.php';
            include_once __DIR__ . '/../../Controller/LoginController.php';
            include_once __DIR__ . '/../../Controller/HomeController.php';
            include_once __DIR__ . '/../../Controller/UserController.php';
            include_once __DIR__ . '/../../Controller/LogStepsController.php';
            include_once __DIR__ . '/../Service/LoginService.php';
            include_once __DIR__ . '/../Service/HomeService.php';
            include_once __DIR__ . '/../Service/UserService.php';
            include_once __DIR__ . '/../Service/LogStepsService.php';
            include_once __DIR__ . '/../Repository/LoginRepository.php';
            include_once __DIR__ . '/../Repository/HomeRepository.php';
            include_once __DIR__ . '/../Repository/UserRepository.php';
            include_once __DIR__ . '/../Repository/LogStepsRepository.php';
        }
    }

    public function checkIfUserInactive()
    {
        //Log out due to inactivity
        $timeout_duration = 900;

        if (isset($_SESSION['user']['user_id'])) {
            if (isset($_SESSION['user']['last_activity']) && (time() - $_SESSION['user']['last_activity']) > $timeout_duration) {
                $_SESSION = [];
                session_destroy();
            
                setcookie("logout_reason", "You’ve been logged out due to inactivity. Please log in again to continue.", time() + 5, "/");
                header('Location: index.php?page=login');
                exit();
            }
        
            $_SESSION['user']['last_activity'] = time();
        } else {
            $privilegedPages = ['home', 'account', 'edit-password', 'edit-nickname', 'log-steps', 'log-step', 'log-step-submit'];
            if (in_array(($_GET['page'] ?? 'home'), $privilegedPages)) {
                header('Location: index.php?page=login');
                exit;
            }
        }
    }

    public function checkIfMostRecentSession()
    {
        if (isset($_SESSION['user'])) {
            $db = new DatabasePdo();

            $result = $db->selectOne(
                "SELECT session_token FROM users WHERE user_id = ?;", 
                [$_SESSION['user']['user_id']]
            );

            if ($_SESSION['user']['session_token'] !== $result['session_token']) {
                $_SESSION = [];
                session_destroy();
            
                setcookie("logout_reason", "You’ve been logged out because your account was accessed from another device. If this wasn’t you, please contact support.", time() + 5, "/");
                header('Location: index.php?page=login');
                exit();
            }
        }
    }
}
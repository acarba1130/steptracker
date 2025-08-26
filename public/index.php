<?php

// Required to access session variables
session_start();

include_once __DIR__ . '/../app/Model/Service/InitializeService.php';

use StepTracker\Service\InitializeService;
use StepTracker\Controller\LoginController;
use StepTracker\Controller\HomeController;
use StepTracker\Controller\UserController;


$initialize = new InitializeService();
$initialize->loadFiles();
$initialize->checkIfUserInactive();
$initialize->checkIfMostRecentSession();


$page = $_GET['page'] ?? 'home';
$loginController = new LoginController();
$homeController = new HomeController();
$userController = new UserController();


switch ($page) {
    case 'home':
        $homeController->home();
        break;

    case 'log-steps':
        $homeController->logSteps();
        break;

    case 'account':
        $homeController->account();
        break;

    case 'edit-password':
        $userController->editPassword();
        break;

    case 'edit-nickname':
        $userController->editNickname();
        break;

    case 'login':
        $loginController->login();
        break;

    case 'login-submit':
        $loginController->handleLogin();
        break;

    case 'logout':
        $loginController->logout();
        break;

    default:
        http_response_code(404);
        $homeController->error();
        break;
}

?>
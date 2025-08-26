<?php

namespace StepTracker\Service;

use StepTracker\Repository\UserRepository;

class UserService
{
    private $userID;
    private $username;
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new userRepository();        
    }

    public function getCurrectUser($userID)
    {
        $result = $this->userRepository->getUserByID($userID);

        return $result;
    }

    public function getUserTeamName($userID)
    {
        $result = $this->userRepository->getUserTeamName($userID);

        return $result;
    }

    public function isPasswordUpdateValid($currentPwSubmit, $newPw, $confirmNewPw)
    {
        $currentUser = $this->getCurrectUser($_SESSION['user']['user_id']);
        $currentPassword = $currentUser['hashed_password'];

        $errors = [];

        // Check for empty fields
        if (empty($currentPwSubmit)) {
            $errors[] = "Current password is required.";
        }
        if (empty($newPw)) {
            $errors[] = "New password is required.";
        }
        if (empty($confirmNewPw)) {
            $errors[] = "Password confirmation is required.";
        }

        // Only proceed with further validation if fields are filled
        if (empty($errors)) {

            // Check if current password is correct
            if (!password_verify($currentPwSubmit, $currentPassword)) {
                $errors[] = "Current password is incorrect.";
            }

            // Check if new passwords match
            if ($newPw !== $confirmNewPw) {
                $errors[] = "New passwords do not match.";
            }

            //Check if new password is old password
            else if (password_hash($newPw, PASSWORD_DEFAULT) === $currentPassword) {
                $errors[] = "New password can't be the same as current.";
            } 
            
            //Check if passwords starts or ends with spaces
            else if ($newPw !== trim($newPw)) {
                $errors[] = "New password cannot start or end with spaces.";
            }

            // Validate new password strength
            if (strlen($newPw) < 8) {
                $errors[] = "Password must be at least 8 characters long.";
            }
            if (!preg_match('/[A-Z]/', $newPw)) {
                $errors[] = "Password must include at least one uppercase letter.";
            }
            if (!preg_match('/[a-z]/', $newPw)) {
                $errors[] = "Password must include at least one lowercase letter.";
            }
            if (!preg_match('/[0-9]/', $newPw)) {
                $errors[] = "Password must include at least one number.";
            }
            if (!preg_match('/[\W]/', $newPw)) {
                $errors[] = "Password must include at least one special character.";
            }

            //get password changes in the last hour
            date_default_timezone_set('UTC');
            $oneHourAgo = date('Y-m-d H:i:s', strtotime('-1 hour'));
            $passwordChangesLastHourAssoc = $this->userRepository->passwordChangesLastHour($_SESSION['user']['user_id'], $oneHourAgo);
            $passwordChangesLastHour = (int) $passwordChangesLastHourAssoc['COUNT(*)'];

            if ($passwordChangesLastHour >= 5) {
                $errors[] = "You can only change your password 5 times per hour. Please try again later.";
            }
        }

        // Return true if no errors, false otherwise, plus the errors
        return [empty($errors), $errors];
    }


    public function updatePassword($newPassword)
    {
        $currentUser = $this->getCurrectUser($_SESSION['user']['user_id']);
        $currentPassword = $currentUser['hashed_password'];

        $newPasswordHashed = password_hash($newPassword, PASSWORD_DEFAULT);

        $this->userRepository->updatePassword($_SESSION['user']['user_id'], $currentPassword, $newPasswordHashed);

        //track password changes
        $this->userRepository->logPasswordChange($_SESSION['user']['user_id']);
    }

    public function isNicknameUpdateValid($newNickname)
    {
        $errors = [];

        // Check for empty fields
        if (empty($newNickname)) {
            $errors[] = "New nickname is required.";
        }

        if ($newNickname !== trim($newNickname)) {
            $errors[] = "Nickname cannot start or end with spaces.";
        }

        // Only proceed with further validation if fields are filled
        if (empty($errors)) {
            $nicknameExist = $this->userRepository->checkIfNicknameExists($newNickname) ?? [];

            if (count($nicknameExist) !== 0) {
                $errors[] = "Nickname already in use.";
            }

            if (strlen($newNickname) > 20) {
                $errors[] = "Nickname must be at most 20 characters long.";
            }
        }

        // Return true if no errors, false otherwise, plus the errors
        return [empty($errors), $errors];
    }

    public function updateNickname($newNickname)
    {
        $this->userRepository->updateNickname($newNickname, $_SESSION['user']['user_id']);
    }
}

?>
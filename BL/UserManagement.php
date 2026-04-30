<?php

require_once "../model/connection_db.php";
require_once "../model/userModel.php";

class UserManagement
{
    private $regsModel;

    public function __construct()
    {
        $database = new Database();
        $db = $database->connect();

        $this->regsModel = new UserModel($db);
    }

    public function registerUser($firstName, $middleName, $lastName, $birthday, $email, $password)
    {
        $firstName = trim($firstName);
        $lastName = trim($lastName);
        $email = trim($email);
        $password = trim($password);

        if (empty($firstName) || empty($lastName) || empty($email) || empty($password)) {
            return "empty";
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "invalid_email";
        }

        if ($this->regsModel->checkEmail($email)) {
            return "exists";
        }

        $password_hash = password_hash($password, PASSWORD_ARGON2ID);
        $created_at = date('Y-m-d H:i:s');

        $success = $this->regsModel->createUser(
            $firstName,
            $middleName,
            $lastName,
            $birthday,
            $email,
            $password_hash,
            $created_at
        );

        return $success ? "registered" : "error";
    }

    public function loginUser($email, $password)
    {
        $email = trim(strtolower($email));
        $password = trim($password);

        $user = $this->regsModel->getUserByEmail($email);

        if ($user && password_verify($password, $user["password"])) {

            $_SESSION["loggedUser"] = [
                "user_id" => $user["user_id"],
                "first_name" => $user["first_name"],
                "last_name" => $user["last_name"],
                "email" => $user["email"],
                "role_id" => $user["role_id"]
            ];

            echo $user["role_id"] == 1 ? "admin" : "user";
            return;
        }

        echo "false";
    }

    public function logoutUser()
    {
        $_SESSION = [];
        session_unset();
        session_destroy();
    }

    public function getCardTotalRuns($userID)
    {
        return $this->regsModel->cardTotalRuns($userID)->fetch(PDO::FETCH_ASSOC);
    }

    public function getCardAveragePace($userID)
    {
        return $this->regsModel->cardAveragePace($userID)->fetch(PDO::FETCH_ASSOC);
    }

    public function getCardCurrentWeight($userID)
    {
        return $this->regsModel->cardCurrentWeight($userID)->fetch(PDO::FETCH_ASSOC);
    }

    public function getCardActiveGoals($userID)
    {
        return $this->regsModel->cardActiveGoals($userID)->fetch(PDO::FETCH_ASSOC);
    }

    public function getRecentActivities($userID)
    {
        return $this->regsModel->getRecentActivities($userID);
    }

    public function getWorkoutTypes()
    {
        return $this->regsModel->getWorkoutTypes();
    }

    public function getGoalTypes()
    {
        return $this->regsModel->getGoalTypes();
    }

    public function getRunProgress($userID)
    {
        return $this->regsModel->getRunProgress($userID);
    }

    public function getWorkoutChart($userID)
    {
        return $this->regsModel->getWorkoutChart($userID);
    }

    public function getWeightProgress($userID)
    {
        return $this->regsModel->getWeightProgress($userID);
    }
}

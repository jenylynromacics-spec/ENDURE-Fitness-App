<?php
require_once "../model/connection_db.php";
require_once "../model/userModel.php";

class GoalManagement
{

    private $model;

    public function __construct()
    {
        $db = (new Database())->connect();
        $this->model = new UserModel($db);
    }

    public function createGoal($userID, $type, $target, $date)
    {
        if ($userID <= 0 || !$type || !$target || !$date) {
            return false;
        }

        return $this->model->insertGoal($userID, $type, $target, $date);
    }
}

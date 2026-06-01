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

    public function createGoal($user_id, $goal_type, $target_value, $target_date, $status)
    {
        if ($user_id <= 0 || !$goal_type || !$target_value || !$target_date) {
            return false;
        }

        return $this->model->insertGoal($user_id, $goal_type, $target_value, $target_date, $status);
    }
    public function getGoalsByUser($user_id)
    {
        return $this->model->fetchGoalsByUser($user_id);
    }
    public function getGoalTypes()
    {
        return $this->model->fetchGoalTypes();
    }
}

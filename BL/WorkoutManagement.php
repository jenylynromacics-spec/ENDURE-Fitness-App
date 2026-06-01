<?php
require_once "../model/connection_db.php";
require_once "../model/userModel.php";

class WorkoutManagement
{

    private $model;

    public function __construct()
    {
        $db = (new Database())->connect();
        $this->model = new UserModel($db);
    }
    public function logWorkout($userID, $type, $duration, $date)
    {
        if ($userID <= 0 || $type <= 0 || $duration <= 0) {
            return false;
        }

        return $this->model->insertWorkout($userID, $type, $duration, $date);
    }
    public function getWorkoutOverview($userID)
    {
        return $this->model->getWorkoutOverview($userID);
    }

    public function getRecentWorkouts($userID)
    {
        return $this->model->getRecentWorkouts($userID);
    }
    public function getWorkoutTypes()
    {
        return $this->model->getWorkoutTypes();
    }
}

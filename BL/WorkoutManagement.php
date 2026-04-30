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
    public function logWorkout($userID, $type, $duration)
    {
        if ($userID <= 0 || $type <= 0 || $duration <= 0) {
            return false;
        }

        return $this->model->insertWorkout($userID, $type, $duration, date("Y-m-d"));
    }
}

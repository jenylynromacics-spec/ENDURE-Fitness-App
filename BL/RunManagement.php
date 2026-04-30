<?php
require_once "../model/connection_db.php";
require_once "../model/userModel.php";

class RunManagement
{
    private $model;

    public function __construct()
    {
        $db = (new Database())->connect();
        $this->model = new UserModel($db);
    }

    public function logRun($userID, $distance, $time, $date)
    {
        if ($userID <= 0 || $distance <= 0 || $time <= 0) {
            return false;
        }

        $pace = $time / $distance;

        if (!$date) {
            $date = date("Y-m-d");
        }

        return $this->model->insertRun($userID, $distance, $time, $pace, $date);
    }
}

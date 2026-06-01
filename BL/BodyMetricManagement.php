<?php
require_once "../model/connection_db.php";
require_once "../model/userModel.php";

class BodyMetricManagement
{

    private $model;

    public function __construct()
    {
        $db = (new Database())->connect();
        $this->model = new UserModel($db);
    }

    public function logWeight($userID, $weight, $date)
    {
        if ($weight <= 0) return false;

        return $this->model->insertWeight($userID, $weight, $date);
    }
    public function getBodyOverview($userID)
    {
        return $this->model->getBodyOverview($userID);
    }

    public function getWeightHistory($userID)
    {
        return $this->model->getWeightHistory($userID);
    }
    public function getLatestWeight($userID)
    {
        return $this->model->getLatestWeight($userID);
    }

    public function getStartingWeight($userID)
    {
        return $this->model->getStartingWeight($userID);
    }
}

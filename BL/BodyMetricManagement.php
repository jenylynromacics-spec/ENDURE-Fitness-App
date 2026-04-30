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

    public function logWeight($userID, $weight)
    {

        if ($weight <= 0) return false;

        return $this->model->insertWeight($userID, $weight);
    }
}

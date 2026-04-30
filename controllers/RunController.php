<?php
session_start();

require_once "../BL/RunManagement.php";

$runManagement = new RunManagement();

if (isset($_POST["user_id"], $_POST["distance_km"], $_POST["time_minutes"])) {

    $runDate = isset($_POST["run_date"]) ? $_POST["run_date"] : date("Y-m-d");

    $result = $runManagement->logRun(
        $_POST["user_id"],
        $_POST["distance_km"],
        $_POST["time_minutes"],
        $runDate
    );

    echo $result ? "success" : "error";
}

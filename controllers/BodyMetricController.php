<?php
session_start();

require_once "../BL/BodyMetricManagement.php";

$metricManagement = new BodyMetricManagement();

if (isset($_POST["user_id"], $_POST["weight_kg"])) {

    $date = $_POST["record_date"] ?? date("Y-m-d");

    $result = $metricManagement->logWeight(
        $_POST["user_id"],
        $_POST["weight_kg"],
        $date
    );

    echo $result ? "success" : "error";
}

<?php
session_start();

require_once "../BL/BodyMetricManagement.php";

$metricManagement = new BodyMetricManagement();

if (isset($_POST["user_id"], $_POST["weight_kg"])) {

    $result = $metricManagement->logWeight(
        $_POST["user_id"],
        $_POST["weight_kg"]
    );

    echo $result ? "success" : "error";
}

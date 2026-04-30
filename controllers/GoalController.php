<?php
session_start();
require_once "../BL/GoalManagement.php";

$goal = new GoalManagement();

if (isset($_POST["user_id"], $_POST["goal_type_id"], $_POST["target_value"], $_POST["target_date"])) {

    $result = $goal->createGoal(
        $_POST["user_id"],
        $_POST["goal_type_id"],
        $_POST["target_value"],
        $_POST["target_date"]
    );

    echo $result ? "success" : "error";
}

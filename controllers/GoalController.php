<?php
require_once "../bl/GoalManagement.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_id = $_POST['user_id'];
    $goal_type = $_POST['goal_type_id'];
    $target_value = $_POST['target_value'];
    $target_date = $_POST['target_date'];
    $status = "Active";

    $goalManager = new GoalManagement();
    $result = $goalManager->createGoal($user_id, $goal_type, $target_value, $target_date, $status);

    if ($result) {
        echo json_encode([
            "status" => "success",
            "message" => "Goal saved successfully"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to save goal"
        ]);
    }
}

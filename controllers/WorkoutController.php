<?php
session_start();
require_once "../BL/WorkoutManagement.php";

$workout = new WorkoutManagement();

if (isset($_POST["user_id"], $_POST["workout_type_id"], $_POST["duration_minutes"])) {

    $result = $workout->logWorkout(
        $_POST["user_id"],
        $_POST["workout_type_id"],
        $_POST["duration_minutes"]
    );

    echo $result ? "success" : "error";
}

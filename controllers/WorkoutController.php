<?php
session_start();

require_once "../bl/WorkoutManagement.php";

$manager = new WorkoutManagement();

$userID = $_POST['user_id'] ?? 0;
$type = $_POST['workout_type_id'] ?? 0;
$duration = $_POST['duration_minutes'] ?? 0;
$date = $_POST['workout_date'] ?? "";

$result = $manager->logWorkout($userID, $type, $duration, $date);

echo $result ? "success" : "error";

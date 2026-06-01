<?php
session_start();

if (!isset($_SESSION["loggedUser"])) {
    header("Location: LoginPage.php");
    exit;
}

require_once "../bl/WorkoutManagement.php"; // ✅ ADD THIS

$user = $_SESSION["loggedUser"];
$workoutManager = new WorkoutManagement();
$overview = $workoutManager->getWorkoutOverview($user["user_id"]);
$workouts = $workoutManager->getRecentWorkouts($user["user_id"]);
$workoutTypes = $workoutManager->getWorkoutTypes();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Workout</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">


    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to right, #0f1a13, #1a2b1f);
            color: #e5e7eb;
            margin: 0;
        }

        /* SIDEBAR */
        .sidebar {
            position: fixed;
            height: 100vh;
            width: 230px;
            background: linear-gradient(180deg, #111c15, #0f1a13);
            padding: 25px;
            border-right: 1px solid #1f2d24;
        }

        .sidebar h5 {
            color: white;
            margin-bottom: 30px;
        }

        .sidebar button {
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            padding: 12px 15px;
            border-radius: 12px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #cbd5e1;
            transition: 0.2s;
        }

        .sidebar button:hover {
            background: rgba(74, 107, 63, 0.25);
        }

        .sidebar button.active {
            background: linear-gradient(to right, #4a6b3f, #6ea66a);
            color: white;
            font-weight: 600;
        }

        /* MAIN */
        .main-content {
            margin-left: 230px;
            padding: 40px;
        }

        /* TITLES */
        .title {
            font-size: 26px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #f9fafb;
        }

        .subtitle {
            font-size: 14px;
            color: #9ca3af;
            margin-top: 5px;
            margin-bottom: 20px;
        }

        /* CARD CONTAINER */
        .card-box {
            background: rgba(31, 45, 36, 0.9);
            border-radius: 18px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        }

        /* FORM */
        .form-group {
            margin-bottom: 20px;
        }

        .custom-input {
            width: 100%;
            height: 50px;
            border-radius: 12px;
            border: none;
            background: rgba(255, 255, 255, 0.08);
            color: white;
            padding: 12px 15px;
            font-size: 14px;
        }

        .custom-input:focus {
            outline: none;
            box-shadow: 0 0 0 2px #6ea66a;
        }

        /* BUTTON */
        .btn-save {
            width: 100%;
            padding: 14px;
            border-radius: 30px;
            border: none;
            background: linear-gradient(to right, #4ade80, #22c55e);
            color: white;
            font-weight: 600;
            margin-top: 15px;
        }

        /* OVERVIEW */
        .overview-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .overview-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 12px;
            text-align: center;
        }

        .small-label {
            font-size: 10px;
            color: #6b7280;
        }

        .big-value {
            font-size: 18px;
            font-weight: 600;
        }

        /* GOALS / CARDS GRID */
        .goals-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-top: 15px;
        }

        /* REUSABLE CARD */
        .card-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 20px;
            border-radius: 15px;
            min-height: 120px;
        }

        /* CARD TEXT */
        .card-title-small {
            font-size: 14px;
            color: #9ca3af;
        }

        .card-value {
            font-size: 18px;
            font-weight: 600;
            color: #4ade80;
            margin-top: 5px;
        }

        .card-date {
            font-size: 12px;
            color: #6b7280;
            margin-top: 5px;
        }

        /* PROGRESS */
        .progress-bar {
            height: 8px;
            background: #2f3e2f;
            border-radius: 10px;
            margin-top: 10px;
        }

        .progress-fill {
            height: 100%;
            width: 60%;
            background: linear-gradient(to right, #4ade80, #22c55e);
            border-radius: 10px;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(1);
            cursor: pointer;
        }

        select.custom-input {
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;

            background-image: url("data:image/svg+xml;charset=UTF-8,%3Csvg fill='white' height='24' viewBox='0 0 24 24' width='24'%3E%3Cpath d='M7 10l5 5 5-5z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 18px;
        }

        select.custom-input option {
            background: #1f2d24;
            color: white;
        }

        .custom-input option {
            background: #1f2d24;
            color: white;
        }

        .custom-input {
            width: 100%;
            height: 50px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.1);
            color: #fff;
            padding: 10px;
            font-size: 14px;
        }

        /* TABLE FIX (RECENT WORKOUTS ONLY) */
        .table-header {
            display: grid;
            grid-template-columns: 1.2fr 1fr 1fr;
            font-size: 12px;
            color: #9ca3af;
            margin-bottom: 12px;
            padding: 0 5px;
        }

        .table-row {
            display: grid;
            grid-template-columns: 1.2fr 1fr 1fr;
            padding: 12px 10px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.04);
            margin-top: 8px;
            align-items: center;
            transition: 0.2s;
        }

        /* HOVER EFFECT */
        .table-row:hover {
            background: rgba(74, 222, 128, 0.12);
        }

        /* TEXT ALIGNMENT */
        .table-row span,
        .table-header span {
            text-align: left;
        }

        /* EMPTY STATE FIX */
        .table-row span:only-child {
            text-align: center;
            width: 100%;
        }
    </style>


</head>

<body>

    <div class="d-flex">

        <!-- SIDEBAR -->
        <div class="sidebar">

            <h5 class="mb-4">ENDURE</h5>

            <button onclick="location.href='Dashboard.php'">
                <span class="material-icons">dashboard</span> Dashboard
            </button>

            <button onclick="location.href='RunPage.php'">
                <span class="material-icons">directions_run</span> Log Run
            </button>

            <button class="active">
                <span class="material-icons">fitness_center</span> Log Workout
            </button>

            <button onclick="location.href='BodyMetricsPage.php'">
                <span class="material-icons">monitor_weight</span> Body Metrics
            </button>

            <button onclick="location.href='GoalsPage.php'">
                <span class="material-icons">flag</span> Goals
            </button>

            <form method="POST" action="../controllers/UserController.php">
                <button onclick="logoutUser()">
                    <span class="material-icons">logout</span> Logout
                </button>

        </div>

        <!-- MAIN -->
        <div class="main-content w-100">

            <!-- TITLE -->
            <div class="title">
                Log Workout
            </div>

            <div class="subtitle">
                Record your workout sessions and monitor your consistency over time.
            </div>

            <p class="date-text"><?= date("F d, Y | l") ?></p>

            <div class="row mt-4 g-4">

                <!-- LEFT: FORM -->
                <div class="col m7">
                    <div class="card-box">
                        <p class="form-desc">Enter your workout details to keep track of your progress.</p>

                        <input type="hidden" id="user_id" value="<?= $user['user_id'] ?>">

                        <!-- WORKOUT TYPE -->
                        <div class="form-group">
                            <label>Workout Type</label>

                            <select id="workout_type" class="custom-input browser-default">
                                <option value="">Select Workout</option>

                                <?php if (!empty($workoutTypes)): ?>
                                    <?php foreach ($workoutTypes as $type): ?>
                                        <option value="<?= $type['workout_type_id'] ?>">
                                            <?= $type['workout_name'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <option>No workout types found</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- DURATION -->
                        <div class="form-group">
                            <label>Duration (minutes)</label>
                            <!-- <input type="text" id="workout_time" class="timepicker custom-input browser-default" placeholder="Select duration"> -->
                            <!-- <input type="text" id="workout_time" class="timepicker custom-input browser-default" placeholder="Select duration"> -->
                            <input type="text" id="workout_time" class="timepicker custom-input browser-default" placeholder="Select time">
                            <label for="workout_time">Duration (hh:mm)</label>
                        </div>

                        <!-- DATE -->
                        <div class="form-group">
                            <label>Workout Date</label>
                            <!-- <input type="date" id="workout_date" class="custom-input browser-default"> -->
                            <input type="date"
                                id="workout_date"
                                class="custom-input browser-default"
                                max="<?= date('Y-m-d') ?>"
                                value="<?= date('Y-m-d') ?>">
                        </div>

                        <!-- BUTTON -->
                        <button type="button" onclick="logWorkout()" class="btn-save">
                            Save Workout
                        </button>
                    </div>
                </div>
                <!-- RIGHT: CARDS -->
                <div class="col-md-5">

                    <!-- OVERVIEW -->
                    <div class="card-box mb-4">
                        <div class="card-title-small">Workout Overview</div>

                        <div class="overview-grid">

                            <div class="overview-item">
                                <div class="small-label">TOTAL WORKOUTS</div>
                                <div class="big-value"><?= $overview['total_workouts'] ?? 0 ?></div>
                            </div>

                            <div class="overview-item">
                                <div class="small-label">TOTAL TIME</div>
                                <div class="big-value"><?= $overview['total_duration'] ?? 0 ?> mins</div>
                            </div>

                            <div class="overview-item">
                                <div class="small-label">MOST FREQUENT</div>
                                <div class="big-value"><?= $overview['most_frequent'] ?? 'N/A' ?></div>
                            </div>

                        </div>
                    </div>

                    <!-- RECENT WORKOUTS -->
                    <!-- RECENT WORKOUTS -->
                    <div class="card-box">
                        <div class="card-title-small">Recent Workouts</div>

                        <div class="table-header">
                            <span>Type</span>
                            <span>Duration</span>
                            <span>Date</span>
                        </div>

                        <?php if ($workouts->rowCount() > 0): ?>

                            <?php while ($w = $workouts->fetch(PDO::FETCH_ASSOC)) { ?>
                                <div class="table-row">
                                    <span><?= $w['workout_name'] ?></span>
                                    <span><?= $w['duration_minutes'] ?> mins</span>
                                    <span><?= date("M d", strtotime($w['workout_date'])) ?></span>
                                </div>
                            <?php } ?>

                        <?php else: ?>

                            <div class="table-row">
                                <span style="grid-column: span 3; text-align:center;">
                                    No workouts yet
                                </span>
                            </div>

                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>
    </div>


    </div>

    </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="../Scripts/service.js"></script>
    <script>
        $(document).ready(function() {
            $('.timepicker').timepicker({
                twelveHour: false,
                autoClose: true
            });
        });
    </script>

</body>

</html>
<?php
session_start();

require_once "../bl/UserManagement.php";

$user = $_SESSION["loggedUser"];
$userID = $user["user_id"];

$userManager = new UserManagement();

$totalRuns = $userManager->getCardTotalRuns($userID);
$avgPace = $userManager->getCardAveragePace($userID);
$currentWeight = $userManager->getCardCurrentWeight($userID);
$currentWeightData = $currentWeight ?? null;
$activeGoals = $userManager->getCardActiveGoals($userID);
$activities = $userManager->getRecentActivities($userID);

$runProgress = $userManager->getRunProgress($userID);
$labels = array_column($runProgress, 'run_date');
$data = array_column($runProgress, 'distance_km');


$workoutChart = $userManager->getWorkoutChart($userID);
$workoutLabels = array_column($workoutChart, 'workout_name');
$workoutData = array_column($workoutChart, 'total');

$weightProgress = $userManager->getWeightProgress($userID);
$weightLabels = array_column($weightProgress, 'created_at');
$weightData = array_column($weightProgress, 'weight_kg');
?>

<!-- <?php
        session_start();

        if (!isset($_SESSION["loggedUser"])) {
            header("Location: LoginPage.php");
            exit;
        }

        $user = $_SESSION["loggedUser"];
        ?> -->

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>ENDURE Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #0f1411;
            color: #e5e7eb;
        }

        /* SIDEBAR */
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 220px;
            background: #161c17;
            padding-top: 20px;
        }

        /* SIDEBAR BUTTONS */
        .sidebar button {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            color: #cbd5e1;
            font-weight: 500;
        }

        .sidebar button:hover {
            background: rgba(74, 107, 63, 0.2);
        }

        .sidebar button.active {
            background: #4a6b3f;
            color: white;
        }

        /* MAIN */
        .main-content {
            padding: 40px;
            margin-left: 220px
        }

        /* HEADINGS */
        h1 {
            font-weight: 600;
            color: #f1f5f9;
        }

        .welcome-text {
            font-size: 26px;
            font-weight: 600;
            color: #7fa46b;
        }

        /* TEXT */
        .overview-text {
            font-size: 16px;
            color: #9ca3af;
            margin-bottom: 20px;
        }

        /* CARDS */

        .datepicker-modal {
            max-width: 325px !important;
        }

        .datepicker-date-display {
            background-color: #4a6b3f !important;
        }

        .datepicker-table td.is-selected {
            background-color: #4a6b3f !important;
        }

        .datepicker-table td.is-today {
            color: #4a6b3f !important;
        }

        /* .card-custom {
            background: #161c17;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            height: 100%;
        } */

        .card-title {
            color: #9ca3af;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .card-value {
            font-size: 30px;
            font-weight: 600;
            color: #f1f5f9;
        }

        /* BUTTONS */
        .btn-action {
            background: #4a6b3f;
            color: white;
            border-radius: 20px;
            padding: 8px 18px;
            border: none;
            font-weight: 500;
        }

        .btn-action:hover {
            background: #3d5934;
        }

        .text-muted {
            color: #e5e7eb !important;
        }

        /* ACTIVITY */
        .activity-header {
            display: grid;
            grid-template-columns: 40px 1fr 1fr 100px;
            font-size: 12px;
            font-weight: 600;
            color: #6b7280;
            margin-bottom: 5px;
        }

        .activity-row {
            display: grid;
            grid-template-columns: 40px 1fr 1fr 100px;
            padding: 14px 0;
            font-size: 14px;
            border-bottom: 1px solid #1f2a23;
        }

        .activity-row:last-child {
            border-bottom: none;
        }

        /* PROGRESS */
        .progress {
            height: 8px;
            background: #1f2a23;
            border-radius: 10px;
        }

        .progress-bar {
            background: #4a6b3f;
            border-radius: 10px;
        }

        /* ICONS */
        .material-icons {
            font-size: 20px;
        }

        /* LOGO */
        .sidebar-header img {
            width: 140px;
            opacity: 0.9;
        }

        /* HOVER EFFECT */
        .card-custom:hover {
            transform: translateY(-3px);
        }

        /* GLOBAL */
        * {
            transition: 0.2s ease;
        }

        .card-custom {
            background: #161c17;
            border-radius: 12px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
            height: 100%;
            padding: 20px;
        }
    </style>
</head>

<body>

    <div class="container-fluid">
        <div class="row">

            <!-- SIDEBAR -->
            <div class="col-md-2 sidebar">
                <div class="sidebar-header">
                    <img src="images/logo2.png" alt="ENDURE">
                </div>
                <button class="active">
                    <span class="material-icons">dashboard</span> Dashboard
                </button>

                <!-- Log Run -->
                <button class="btn btn-action" onclick="location.href='RunPage.php'">
                    <span class="material-icons">directions_run</span> Log Run
                </button>

                <!-- Log Workout -->
                <button class="btn btn-action" onclick="location.href='WorkoutPage.php'">
                    <span class="material-icons">fitness_center</span> Log Workout
                </button>

                <!-- Body Metrics -->
                <button class="btn btn-action" onclick="location.href='BodyMetricsPage.php'">
                    <span class="material-icons">monitor_weight</span> Body Metrics
                </button>

                <!-- Goals -->
                <button class="btn btn-action" onclick="location.href='GoalsPage.php'">
                    <span class="material-icons">flag</span> Goals
                </button>

                <form method="POST" action="../controllers/UserController.php">
                    <button type="submit" name="logout" class="btn btn-action d-flex align-items-center gap-2">
                        <span class="material-icons">logout</span>
                        Logout
                    </button>
                </form>
            </div>

            <!-- MAIN -->
            <div class="col-md-10 main-content">

                <!-- HEADER -->
                <div class="d-flex justify-content-between align-items-start mb-3">
                    <div>
                        <h1 class="fw-bold mb-1">Dashboard</h1>
                        <small class="text-muted">
                            <?= date("F d, Y | l") ?>
                        </small>
                    </div>

                    <div class="text-end">
                        <h4 class="welcome-text mb-1">
                            Welcome back, <?php echo $user["first_name"]; ?>!
                        </h4>
                        <small class="text-muted">Keep pushing your limits today.</small>
                    </div>
                </div>

                <!-- ACTION BUTTONS -->
                <div class="d-flex justify-content-end gap-2 mb-3">
                    <!-- <a class="btn btn-action" onclick="alert('Feature coming soon')">Log Run</a>
                    <a class="btn btn-action" onclick="alert('Feature coming soon')">Log Workout</a>
                    <a class="btn btn-action" onclick="alert('Feature coming soon')">Log Weight</a> -->
                    <a class="btn btn-action" href="RunPage.php">Log Run</a>
                    <a class="btn btn-action" href="WorkoutPage.php">Log Workout</a>
                    <a class="btn btn-action" href="BodyMetricsPage.php">Log Weight</a>
                </div>

                <!-- OVERVIEW TEXT -->
                <p class="overview-text">
                    Here's your fitness overview for today.
                </p>

                <!-- MAIN CHART -->
                <div class="row g-4">
                    <div class="col-md-12">
                        <div class="card-custom">
                            <canvas id="runChart" style="height:500px;"></canvas>
                        </div>
                    </div>
                </div>

                <!-- TWO CHARTS -->
                <div class="row g-4 mt-2">

                    <!-- Workout Chart -->
                    <div class="col-md-6">
                        <div class="card-custom">
                            <h6>Workout Distribution</h6>
                            <canvas id="workoutChart" style="height:250px;"></canvas>
                        </div>
                    </div>

                    <!-- Weight Chart -->
                    <div class="col-md-6">
                        <div class="card-custom">
                            <h6>Weight Progress</h6>
                            <canvas id="weightChart" style="height:250px;"></canvas>
                        </div>
                    </div>

                </div>

                <!-- CARDS -->
                <div class="row g-4 mt-2">
                    <div class="row g-3 align-items-stretch">

                        <!-- LEFT SIDE -->
                        <div class="col-md-6">
                            <div class="row g-3">

                                <!-- TOTAL RUNS -->
                                <div class="col-md-6">
                                    <div class="card-custom">
                                        <h3><?= isset($totalRuns['total_runs']) ? $totalRuns['total_runs'] : 0 ?></h3>
                                        <p>Total Runs</p>
                                    </div>
                                </div>

                                <!-- AVERAGE PACE -->
                                <div class="col-md-6">
                                    <div class="card-custom">
                                        <div class="card-title">Average Pace</div>
                                        <div class="card-value">
                                            <?= isset($avgPace['avg_pace']) ? number_format($avgPace['avg_pace'], 2) . ' min/km' : '0.00 min/km' ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- CURRENT WEIGHT -->
                                <div class="col-md-6">
                                    <div class="card-custom">
                                        <div class="card-title">Current Weight</div>
                                        <div class="card-value">
                                            <?= isset($currentWeight['weight_kg']) ? $currentWeight['weight_kg'] . ' kg' : '0 kg' ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- ACTIVE GOALS -->
                                <div class="col-md-6">
                                    <div class="card-custom">
                                        <div class="card-title">Active Goals</div>
                                        <div class="card-value">
                                            <?= isset($activeGoals['total_goals']) ? $activeGoals['total_goals'] : 0 ?>
                                        </div>

                                        <div class="progress mt-2">
                                            <div class="progress-bar"
                                                style="width: <?= isset($activeGoals['total_goals']) ? min($activeGoals['total_goals'] * 20, 100) : 0 ?>%">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- RIGHT SIDE -->
                        <div class="col-md-6">
                            <div class="card-custom h-100">

                                <div class="card-title mb-3">Recent Activity</div>

                                <!-- HEADER -->
                                <div class="activity-header">
                                    <span>#</span>
                                    <span>ACTIVITY</span>
                                    <span>INFORMATION</span>
                                    <span>DATE</span>
                                </div>

                                <!-- ROWS -->
                                <div class="activity-list">

                                    <?php if (!empty($activities)): ?>
                                        <?php $count = 1; ?>

                                        <?php foreach ($activities as $act): ?>
                                            <div class="activity-row">
                                                <span><?= $count++ ?></span>
                                                <span><?= htmlspecialchars($act['activity']) ?></span>
                                                <span><?= htmlspecialchars($act['info']) ?></span>
                                                <span><?= date("M d", strtotime($act['date'])) ?></span>
                                            </div>
                                        <?php endforeach; ?>

                                    <?php else: ?>
                                        <div class="activity-row text-muted">
                                            <span>—</span>
                                            <span>No Activity</span>
                                            <span>Start logging your workouts</span>
                                            <span>--</span>
                                        </div>
                                    <?php endif; ?>

                                </div>
                                <!-- FOOTER MESSAGE -->
                                <p class="mt-3 text-muted">
                                    <?php if (!empty($activities)): ?>
                                        Great job! You've been consistent with your workouts.
                                    <?php else: ?>
                                        No activity yet. Start your fitness journey today.
                                    <?php endif; ?>
                                </p>

                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        console.log("jQuery test:", typeof $);
    </script>

    <script>
        window.runChartData = {
            labels: <?= json_encode($labels) ?>,
            data: <?= json_encode($data) ?>
        }
    </script>

    <script>
        window.workoutChartData = {
            labels: <?= json_encode($workoutLabels) ?>,
            data: <?= json_encode($workoutData) ?>
        };
    </script>

    <script>
        window.weightChartData = {
            labels: <?= json_encode($weightLabels) ?>,
            data: <?= json_encode($weightData) ?>
        };
    </script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="../Scripts/service.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


</body>

</html>
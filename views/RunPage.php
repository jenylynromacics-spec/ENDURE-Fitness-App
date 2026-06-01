<?php
session_start();

if (!isset($_SESSION["loggedUser"])) {
    header("Location: LoginPage.php");
    exit;
}

$user = $_SESSION["loggedUser"];

require_once "../BL/RunManagement.php";

$runManager = new RunManagement();
$runOverview = $runManager->getRunOverview($user["user_id"]);
$recentRuns = $runManager->getRecentRuns($user["user_id"]);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Log Run</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
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
            margin-bottom: 15px;
        }

        .custom-input {
            width: 100%;
            height: 45px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.08);
            border: none;
            color: white;
            padding: 10px;
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

        .table-header {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 10px;
        }

        .table-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            padding: 10px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.03);
            margin-top: 5px;
        }

        .timepicker-modal {
            background: #ffffff !important;
            color: #000 !important;
        }

        .timepicker-digital-display {
            background: #22c55e !important;
        }

        .timepicker-text-container {
            color: #fff !important;
        }

        .timepicker-dial {
            background: #f5f5f5 !important;
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

            <button class="active">
                <span class="material-icons">directions_run</span> Log Run
            </button>

            <button onclick="location.href='WorkoutPage.php'">
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
            </form>
        </div>

        <div class="main-content w-100">

            <!-- HEADER -->
            <div class="mb-4">
                <h1 class="title">Log Run</h1>
                <p class="subtitle">Track your performance and improve your pace over time</p>
                <div class="date-text">
                    <?= date("F d, Y | l") ?>
                </div>
            </div>

            <div class="row g-4">

                <!-- LEFT: FORM -->
                <div class="col-lg-6">

                    <div class="card-box">

                        <p class="form-desc">
                            Enter your running details to track your performance.
                        </p>
                        <input type="hidden" id="user_id" value="<?= $user['user_id'] ?>">
                        <div class="form-group">
                            <label>Distance</label>
                            <input type="number" id="distance_km" class="custom-input browser-default" placeholder="e.g. 5 km">
                        </div>

                        <div class="form-group">
                            <label>Time</label>
                            <input type="text" id="time_minutes" class="timepicker custom-input browser-default" placeholder="Select time">
                        </div>


                        <div class="form-group">
                            <label>Run Date</label>
                            <input type="date"
                                id="run_date"
                                class="custom-input browser-default"
                                max="<?= date('Y-m-d') ?>"
                                value="<?= date('Y-m-d') ?>">
                        </div>

                        <button class="btn-save" onclick="logRun()">
                            Save Run
                        </button>

                    </div>

                </div>

                <!-- RIGHT: OVERVIEW -->
                <div class="col-lg-6">

                    <!-- OVERVIEW CARD -->
                    <div class="card-box mb-4">

                        <div class="card-title-small">Running Overview</div>

                        <div class="overview-grid">

                            <div class="overview-item">
                                <div class="small-label">DISTANCE</div>
                                <div class="big-value"> <?= number_format($runOverview["total_distance"] ?? 0, 2) ?> km</div>
                            </div>

                            <div class="overview-item">
                                <div class="small-label">AVG PACE</div>
                                <div class="big-value"><?= number_format($runOverview["avg_pace"] ?? 0, 2) ?> /km</div>
                            </div>

                            <div class="overview-item">
                                <div class="small-label">BEST PACE</div>
                                <div class="big-value"> <?= number_format($runOverview["best_pace"] ?? 0, 2) ?> /km</div>
                            </div>

                        </div>

                    </div>

                    <!-- RECENT RUNS -->
                    <div class="card-box">

                        <div class="card-title-small">Recent Runs</div>

                        <!-- HEADER -->
                        <div class="table-header">
                            <span>Distance</span>
                            <span>Time</span>
                            <span>Pace</span>
                            <span>Date</span>
                        </div>

                        <!-- ROWS -->
                        <?php while ($run = $recentRuns->fetch(PDO::FETCH_ASSOC)) { ?>
                            <div class="table-row">
                                <span><?= $run["distance_km"] ?> km</span>
                                <span><?= $run["time_minutes"] ?> mins</span>
                                <span><?= number_format($run["pace_per_km"], 2) ?>/km</span>
                                <span><?= date("M d", strtotime($run["run_date"])) ?></span>
                            </div>
                        <?php } ?>

                    </div>

                </div>

            </div>

        </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../Scripts/service.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>

</body>

</html>
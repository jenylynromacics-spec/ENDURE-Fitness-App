<?php
session_start();

if (!isset($_SESSION["loggedUser"])) {
    header("Location: LoginPage.php");
    exit;
}

require_once "../bl/BodyMetricManagement.php";

$user = $_SESSION["loggedUser"];
$userID = $user["user_id"];

$metricManager = new BodyMetricManagement();

// FETCH DATA
$currentWeight = $metricManager->getLatestWeight($userID);
$startingWeight = $metricManager->getStartingWeight($userID);
$weights = $metricManager->getWeightHistory($userID);

// COMPUTE CHANGE
$weightChange = $currentWeight - $startingWeight;

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Body Metrics</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <script src="../Scripts/service.js"></script>

    <style>
        body {
            background: #0f1c14;
            color: white;
            font-family: 'Inter', sans-serif;
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
            margin-left: 220px;
            padding: 40px;
        }

        /* TITLES */
        .title {
            font-size: 30px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .subtitle {
            color: #9ca3af;
            font-size: 14px;
            margin-top: 5px;
        }

        .date-text {
            color: #9ca3af;
            margin-top: 5px;
            font-size: 13px;
        }

        /* CARD */
        .card-box {
            border-radius: 18px;
            padding: 25px;
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(14px);
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        }

        /* FORM */
        .form-group {
            margin-bottom: 18px;
        }

        .custom-input {
            width: 100%;
            height: 45px;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.08);
            border: none;
            color: white;
            padding: 10px;
            font-size: 14px;
            transition: 0.2s;
        }

        .custom-input:focus {
            outline: none;
            box-shadow: 0 0 0 2px #4ade80;
            background: rgba(255, 255, 255, 0.1);
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
            transition: 0.2s;
        }

        .btn-save:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(74, 222, 128, 0.3);
        }

        /* CARD TITLE */
        .card-title {
            font-size: 13px;
            color: #9ca3af;
            margin-bottom: 15px;
        }

        /* OVERVIEW GRID */
        .overview-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
        }

        .overview-item {
            background: rgba(255, 255, 255, 0.05);
            padding: 15px;
            border-radius: 12px;
        }

        /* TEXT */
        .small-label {
            font-size: 10px;
            color: #6b7280;
        }

        .big-value {
            font-size: 18px;
            font-weight: 600;
        }

        /* TABLE */
        .run-header {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            font-size: 11px;
            color: #6b7280;
            margin-bottom: 10px;
        }

        .run-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            padding: 10px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.03);
            margin-top: 6px;
            transition: 0.2s;
        }

        .run-row:hover {
            background: rgba(255, 255, 255, 0.07);
        }

        /* RESPONSIVE */
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .sidebar {
                display: none;
            }

            .overview-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid d-flex">

        <?php $currentPage = basename($_SERVER['PHP_SELF']); ?>

        <div class="sidebar">
            <h5 class="mb-4">ENDURE</h5>

            <button class="<?= ($currentPage == 'Dashboard.php') ? 'active' : '' ?>" onclick="location.href='Dashboard.php'">
                <span class="material-icons">dashboard</span> Dashboard
            </button>

            <button class="<?= ($currentPage == 'RunPage.php') ? 'active' : '' ?>" onclick="location.href='RunPage.php'">
                <span class="material-icons">directions_run</span> Log Run
            </button>

            <button class="<?= ($currentPage == 'WorkoutPage.php') ? 'active' : '' ?>" onclick="location.href='WorkoutPage.php'">
                <span class="material-icons">fitness_center</span> Log Workout
            </button>

            <button class="<?= ($currentPage == 'BodyMetricsPage.php') ? 'active' : '' ?>" onclick="location.href='BodyMetricsPage.php'">
                <span class="material-icons">monitor_weight</span> Body Metrics
            </button>

            <button class="<?= ($currentPage == 'GoalsPage.php') ? 'active' : '' ?>" onclick="location.href='GoalsPage.php'">
                <span class="material-icons">flag</span> Goals
            </button>

            <form method="POST" action="../controllers/UserController.php">
                <button onclick="logoutUser()">
                    <span class="material-icons">logout</span> Logout
                </button>
            </form>
        </div>

        <div class="main-content w-100">

            <!-- TITLE -->
            <div class="title">
                Body Metrics
            </div>

            <div class="subtitle">
                Track your body weight and monitor your progress over time.
            </div>

            <div class="date-text">
                <?= date("F d, Y | l") ?>
            </div>

            <!-- GRID -->
            <div class="row mt-4 g-4">

                <!-- LEFT SIDE (FORM) -->
                <div class="col-md-7">

                    <div class="card-box">

                        <p class="subtitle mb-3">
                            Enter your body metrics details to track your performance.
                        </p>

                        <!-- USER ID -->
                        <input type="hidden" id="user_id" value="<?= $_SESSION['loggedUser']['user_id'] ?>">

                        <div class="form-group">
                            <label>Weight (kg)</label>
                            <input type="number" id="weight_kg" class="custom-input browser-default" placeholder="Enter weight (kg) e.g. 65 " step="0.1">
                        </div>

                        <div class="form-group">
                            <label>Record Date</label>
                            <input type="date"
                                id="record_date"
                                class="custom-input browser-default"
                                max="<?= date('Y-m-d') ?>"
                                value="<?= date('Y-m-d') ?>">
                        </div>

                        <button class="btn-save" onclick="logWeight()">
                            Save Body Metric
                        </button>

                    </div>

                </div>

                <!-- RIGHT SIDE -->
                <div class="col-md-5 d-flex flex-column gap-4">

                    <!-- BODY OVERVIEW -->
                    <div class="card-box">
                        <div class="card-title">Body Overview</div>

                        <div class="overview-grid">

                            <div class="overview-item">
                                <div class="small-label">CURRENT WEIGHT</div>
                                <div class="big-value"><?= $currentWeight ?? '0' ?> kg</div>
                            </div>

                            <div class="overview-item">
                                <div class="small-label">STARTING WEIGHT</div>
                                <div class="big-value"><?= $startingWeight ?? '0' ?> kg</div>
                            </div>

                            <div class="overview-item">
                                <div class="small-label">CHANGE</div>
                                <div class="big-value"><?= $weightChange ?? '0' ?> kg</div>
                            </div>

                        </div>
                    </div>

                    <!-- WEIGHT HISTORY -->
                    <div class="card-box">
                        <div class="card-title">Weight History</div>

                        <div class="run-header">
                            <span>Weight</span>
                            <span>Date</span>
                        </div>

                        <?php if (!empty($weights)): ?>
                            <?php foreach ($weights as $weight): ?>
                                <div class="run-row">
                                    <span><?= $weight['weight_kg'] ?> kg</span>
                                    <span><?= date("M d", strtotime($weight['record_date'])) ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="run-row">
                                <span>No data yet</span>
                            </div>
                        <?php endif; ?>

                    </div>

                </div>

            </div>

        </div>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
        <script src="../Scripts/service.js"></script>

</body>

</html>
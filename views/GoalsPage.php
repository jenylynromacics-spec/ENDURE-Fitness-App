<?php
session_start();

if (!isset($_SESSION["loggedUser"])) {
    header("Location: LoginPage.php");
    exit;
}

require_once "../bl/GoalManagement.php";

$user = $_SESSION["loggedUser"];
$goalManager = new GoalManagement();

$goalTypes = $goalManager->getGoalTypes();
$goalsStmt = $goalManager->getGoalsByUser($user['user_id']);
$goals = $goalsStmt->fetchAll(PDO::FETCH_ASSOC);

$totalGoals = count($goals);

$activeGoals = 0;
$completedGoals = 0;

foreach ($goals as $goal) {
    if (strtotime($goal['target_date']) >= time()) {
        $activeGoals++;
    } else {
        $completedGoals++;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Goals</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>


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


        select.custom-input option {
            color: #000;
            background-color: #fff;
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

            <button onclick="location.href='WorkoutPage.php'">
                <span class="material-icons">fitness_center</span> Log Workout
            </button>

            <button onclick="location.href='BodyMetricsPage.php'">
                <span class="material-icons">monitor_weight</span> Body Metrics
            </button>

            <button class="active">
                <span class="material-icons">flag</span> Goals
            </button>

            <button onclick="logoutUser()">
                <span class="material-icons">logout</span> Logout
            </button>

        </div>

        <!-- MAIN -->
        <div class="main-content w-100">

            <div class="title">
                Goals
            </div>

            <div class="subtitle">
                Set your fitness goals and track your progress over time.
            </div>

            <div class="date-text">
                <?= date("F d, Y | l") ?>
            </div>

            <div class="row mt-4 g-4">

                <!-- LEFT SIDE -->
                <div class="col-md-7">

                    <div class="card-box">

                        <p class="subtitle mb-3">
                            Setting clear goals helps you stay consistent and motivated.
                        </p>

                        <input type="hidden" id="user_id" value="<?= $user['user_id'] ?>">

                        <!-- //goal type -->
                        <div class="form-group">
                            <label>Goal Type</label>
                            <select id="goal_type_id" class="custom-input browser-default" onchange="updatePlaceholder()" placeholder="Select Goal Type">
                                <!-- <option value="">Select Goal Type</option> -->

                                <?php foreach ($goalTypes as $type): ?>
                                    <option value="<?= $type['goal_type_id'] ?>">
                                        <?= $type['goal_name'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!--target value -->
                        <div class="form-group">
                            <label>Target Value</label>
                            <input
                                type="text"
                                id="target_value"
                                class="custom-input browser-default"
                                placeholder="e.g. 5:30"
                                pattern="^\d{1,2}:\d{2}$"
                                title="Format should be mm:ss (e.g. 5:30)">
                        </div>

                        <!-- //target date -->
                        <div class="form-group">
                            <label>Target Date</label>
                            <input type="date" id="target_date" class="custom-input browser-default" min="<?= date('Y-m-d') ?>">
                        </div>

                        <button class="btn-save" onclick="createGoal()">
                            Save Goal
                        </button>
                    </div>

                </div>

                <!-- RIGHT SIDE -->
                <div class="col-md-5">

                    <!-- OVERVIEW -->
                    <div class="card-box mb-4" style="margin-bottom: 20px;">
                        <div class="card-title">Goals Overview</div>

                        <div class="overview-grid">
                            <div class="overview-item">
                                <div class="small-label">TOTAL</div>
                                <div class="big-value"><?= $totalGoals ?></div>
                            </div>

                            <div class="overview-item">
                                <div class="small-label">ACTIVE</div>
                                <div class="big-value"><?= $activeGoals ?></div>
                            </div>

                            <div class="overview-item">
                                <div class="small-label">COMPLETED</div>
                                <div class="big-value"><?= $completedGoals ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- GOALS GRID -->
                    <div class="goals-grid">

                        <?php foreach ($goals as $goal): ?>
                            <div class="card-item">

                                <div class="card-title-small">
                                    <?= $goal['goal_name'] ?>
                                </div>

                                <div class="card-value">

                                    <?= $goal['target_value'] ?>

                                    <?php
                                    if (strtolower($goal['goal_name']) === "weight goal") echo " kg";
                                    elseif (strtolower($goal['goal_name']) === "distance goal") echo " km";
                                    elseif (strtolower($goal['goal_name']) === "target pace") echo " min/km";
                                    ?>
                                </div>

                                <div class="card-date">
                                    Due: <?= date("M d", strtotime($goal['target_date'])) ?>
                                </div>

                            </div>
                        <?php endforeach; ?>

                    </div>

                </div>

            </div>

            <script src="../scripts/service.js"></script>
</body>

</html>
<?php
session_start();

if (!isset($_SESSION["loggedUser"])) {
    header("Location: LoginPage.php");
    exit;
}

require_once "../bl/UserManagement.php";

$userManager = new UserManagement();
$goalTypes = $userManager->getGoalTypes(); // 🔥 THIS IS MISSING

$user = $_SESSION["loggedUser"];
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>Goals</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
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
            height: 100vh;
            background: #111c15;
            padding: 25px;
            border-right: 1px solid #1f2d24;
            width: 230px;
        }

        .sidebar h5 {
            color: white;
        }

        .sidebar button {
            width: 100%;
            text-align: left;
            background: none;
            border: none;
            padding: 12px 15px;
            border-radius: 10px;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #cbd5e1;
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
        }

        /* TITLE */
        .title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #f9fafb;
        }

        /* CARD */
        .card-box {
            background: #1f2d24;
            border-radius: 18px;
            padding: 35px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.4);
            max-width: 500px;
        }

        /* INPUT */
        input[type="text"] {
            width: 100%;
            height: 45px;
            padding: 10px;
            border-radius: 10px;
            border: none;
            background: #2d3b32;
            color: white;
            margin-top: 10px;
        }

        input::placeholder {
            color: #9ca3af;
        }

        input:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(106, 168, 79, 0.4);
        }

        label {
            font-size: 14px;
            color: #9ca3af;
        }

        /* BUTTON */
        .btn-save {
            margin-top: 25px;
            width: 100%;
            background: #4a6b3f;
            color: white;
            border: none;
            border-radius: 20px;
            padding: 12px;
            font-weight: 500;
        }

        .btn-save:hover {
            background: #3d5934;
        }

        /* BACK BUTTON */
        .btn-back {
            background: transparent;
            border: 1px solid #4a6b3f;
            color: #cbd5e1;
            border-radius: 20px;
            padding: 6px 15px;
        }

        .btn-back:hover {
            background: #4a6b3f;
            color: white;
        }

        .subtitle {
            font-size: 14px;
            color: #9ca3af;
            margin-top: -5px;
        }

        select {
            width: 100%;
            height: 45px;
            padding: 10px;
            border-radius: 10px;
            border: none;
            background: #2d3b32;
            color: white;
        }

        select:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(106, 168, 79, 0.4);
        }

        /* Fix spacing of card (less cramped center look) */
        .card-box {
            max-width: 600px;
        }

        /* FIX INPUT FIELDS */
        input[type="number"],
        input[type="date"] {
            width: 100%;
            height: 50px;
            padding: 12px 15px;
            border-radius: 12px;
            border: none;
            background: #2d3b32;
            color: white;
            font-size: 15px;
            margin-top: 8px;
        }

        /* placeholder color */
        input::placeholder {
            color: #9ca3af;
        }

        /* focus effect */
        input:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(106, 168, 79, 0.5);
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

            <!-- HEADER -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <div class="title">
                        <span class="material-icons">flag</span>
                        Goals
                    </div>
                    <div class="subtitle">Set and track your fitness goals.</div>
                </div>

                <button class="btn-back"
                    onclick="location.href='Dashboard.php'">
                    ← Back
                </button>
            </div>

            <input type="hidden" id="user_id" value="<?= $user['user_id'] ?>">

            <!-- CARD -->
            <div class="card-box">

                <!-- GOAL TYPE -->
                <div class="mb-4">
                    <label>Goal Type</label>
                    <select id="goal_type_id">
                        <?php foreach ($goalTypes as $goal): ?>
                            <option value="<?= $goal['goal_type_id'] ?>">
                                <?= $goal['goal_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-4">
                    <label>Target Value</label>
                    <input type="number" id="target_value" placeholder="e.g. 5 (km or kg)">
                </div>

                <div class="mb-4">
                    <label>Target Date</label>
                    <input type="date" id="target_date">
                </div>

                <button class="btn-save" onclick="createGoal()">
                    Save Goal
                </button>

            </div>

        </div>

    </div>

    <script src="../scripts/service.js"></script>

</body>

</html>
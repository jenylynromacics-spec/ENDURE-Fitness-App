<?php
session_start();

if (!isset($_SESSION["loggedUser"])) {
    header("Location: LoginPage.php");
    exit;
}

require_once "../bl/UserManagement.php";

$userManager = new UserManagement();
$workoutTypes = $userManager->getWorkoutTypes();

$user = $_SESSION["loggedUser"];

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Workout</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icons -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(to right, #eef2f7, #f8fafc);
        }

        /* SIDEBAR */
        .sidebar {
            height: 100vh;
            background: #f1f5f9;
            padding: 25px;
            border-right: 1px solid #e2e8f0;
            width: 230px;
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
        }

        .sidebar button:hover,
        .sidebar button.active {
            background: #e2e8f0;
        }

        /* MAIN */
        .main-content {
            padding: 40px;
        }

        /* CARD */
        .card-box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
            max-width: 500px;
        }

        /* BUTTON */
        .btn-save {
            background: #4a6b3f;
            color: white;
            border-radius: 20px;
        }

        .btn-save:hover {
            background: #3d5934;
        }

        .title {
            font-size: 28px;
            font-weight: 600;
        }

        .subtitle {
            color: #666;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        input,
        select {
            width: 100%;
            height: 45px;
            padding: 10px;
            border-radius: 10px;
            border: none;
            background: #eee;
            margin-top: 8px;
            font-size: 14px;
        }

        select {
            appearance: none;
            cursor: pointer;
        }

        input:focus,
        select:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(74, 107, 63, 0.3);
        }

        .btn-save {
            margin-top: 10px;
            width: 100%;
            background: #4a6b3f;
            color: white;
            border: none;
            border-radius: 25px;
            padding: 12px;
            font-size: 15px;
        }

        .btn-save:hover {
            background: #3d5934;
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

            <button onclick="location.href='GoalPage.php'">
                <span class="material-icons">flag</span> Goals
            </button>

            <button onclick="logoutUser()">
                <span class="material-icons">logout</span> Logout
            </button>

        </div>

        <!-- MAIN -->
        <div class="main-content w-100">

            <!-- BACK BUTTON -->
            <div class="mb-3">
                <button class="btn btn-outline-secondary btn-sm"
                    onclick="location.href='Dashboard.php'">
                    ← Back
                </button>
            </div>

            <div class="title">Log Workout</div>
            <div class="subtitle">Track your workout sessions and stay consistent.</div>

            <input type="hidden" id="user_id" value="<?= $user['user_id'] ?>">

            <div class="card-box">

                <!-- Duration -->
                <div class="mb-3">
                    <label class="form-label">Duration (minutes)</label>
                    <input type="number" id="duration_minutes" class="form-control" placeholder="e.g. 45">
                </div>

                <!-- Workout Type -->
                <div class="mb-3">
                    <label class="form-label">Workout Type</label>
                    <select id="workout_type_id" class="form-select">
                        <?php foreach ($workoutTypes as $type): ?>
                            <option value="<?= $type['workout_type_id'] ?>">
                                <?= $type['workout_name'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- BUTTON -->
                <button class="btn btn-save w-100" onclick="logWorkout()">
                    Save Workout
                </button>
            </div>

        </div>

    </div>

    </div>

    <script src="../scripts/service.js"></script>

</body>

<!-- </html><?php
            session_start();

            if (!isset($_SESSION["loggedUser"])) {
                header("Location: LoginPage.php");
                exit;
            }

            $user = $_SESSION["loggedUser"];
            ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Log Workout</title>

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

    .title {
        font-size: 24px;
        font-weight: 600;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 10px;
        color: #f9fafb;
    }

    .subtitle {
        font-size: 14px;
        color: #9ca3af;
        margin-bottom: 30px;
    }

    /* CARD */
    .card-box {
        background: #1f2d24;
        border-radius: 18px;
        padding: 35px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        max-width: 500px;
    }

    /* INPUT */
    input {
        width: 100%;
        height: 50px;
        padding: 12px 15px;
        border-radius: 12px;
        border: none;
        background: #2d3b32;
        color: white;
        margin-top: 10px;
    }

    input:focus {
        outline: none;
        box-shadow: 0 0 0 2px rgba(106, 168, 79, 0.5);
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
        border-radius: 25px;
        padding: 14px;
    }

    .btn-save:hover {
        background: #3d5934;
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

            <button onclick="logoutUser()">
                <span class="material-icons">logout</span> Logout
            </button>

        </div>

        <!-- MAIN -->
        <div class="main-content w-100">

            <!-- HEADER -->
            <div class="mb-4">
                <div class="title">
                    <span class="material-icons">fitness_center</span>
                    Log Workout
                </div>

                <div class="subtitle">
                    Track your workout sessions and stay consistent.
                </div>
            </div>

            <!-- USER ID -->
            <input type="hidden" id="user_id" value="<?= $user['user_id'] ?>">

            <!-- CARD -->
            <div class="card-box">

                <!-- DURATION -->
                <div class="form-group">
                    <label>Duration (minutes)</label>
                    <input type="number" id="duration_minutes" placeholder="e.g. 45">
                </div>

                <!-- WORKOUT TYPE -->
                <div class="form-group">
                    <label>Workout Type</label>
                    <select id="workout_type_id">
                        <option value="1">Strength</option>
                        <option value="2">Cardio</option>
                    </select>
                </div>

                <!-- BUTTON (NOW CORRECTLY BELOW EVERYTHING) -->
                <button class="btn-save" onclick="logWorkout()">
                    Save Workout
                </button>

            </div>

        </div>
        <script src="../scripts/service.js"></script>

</body>

</html> -->
<?php
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
    <title>Body Metrics</title>

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
        input[type="number"] {
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

        /* INFO CARD */
        .info-card {
            margin-top: 25px;
            background: #1f2d24;
            padding: 20px;
            border-radius: 15px;
            font-size: 14px;
            color: #9ca3af;
            max-width: 500px;
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

            <button class="active">
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

            <div class="title">
                <span class="material-icons">monitor_weight</span>
                Body Metrics
            </div>

            <div class="subtitle">
                Track your weight and monitor your progress over time.
            </div>

            <!-- USER ID -->
            <input type="hidden" id="user_id" value="<?= $user['user_id'] ?>">

            <!-- CARD -->
            <div class="card-box">

                <div>
                    <label>Weight (kg)</label>
                    <input type="number" id="weight_kg" step="0.1" min="1" required>
                </div>

                <button class="btn-save" onclick="logWeight()">
                    Save Weight
                </button>

            </div>

            <!-- INFO -->
            <div class="info-card">
                Stay consistent with your weight tracking. Small progress leads to big results 💪
            </div>

        </div>

    </div>

    <script src="../scripts/service.js"></script>

</body>

</html>
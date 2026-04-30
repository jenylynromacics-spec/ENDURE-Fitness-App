<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>ENDURE Registration</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            height: 100vh;
            margin: 0;
            overflow: hidden;

            background: url('images/Registration.png') center/cover no-repeat;
        }

        /* OVERLAY */
        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            z-index: 1;
        }

        /* CONTAINER */
        .register-container {
            position: relative;
            z-index: 2;
            height: 100vh;
        }

        /* CARD */
        .register-card {
            background: rgba(74, 107, 63, 0.75);
            backdrop-filter: blur(18px);
            border-radius: 20px;
            padding: 40px 30px;
            color: white;
            width: 100%;
            max-width: 650px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);

            animation: fadeUp 0.6s ease;
        }

        .register-card h4 {
            font-weight: 600;
        }

        .register-card p {
            font-size: 13px;
            opacity: 0.85;
            margin-bottom: 20px;
        }

        .datepicker {
            border-radius: 12px;
            border: none;
            padding: 12px;
            background: rgba(255, 255, 255, 0.9);
            font-size: 14px;
        }

        /* INPUT */
        .form-control {
            border-radius: 12px;
            border: none;
            padding: 12px;
            background: rgba(255, 255, 255, 0.9);
            font-size: 14px;
        }

        .form-control:focus {
            box-shadow: none;
            border: 1px solid #cfe3c2;
        }

        label {
            font-size: 13px;
            margin-bottom: 5px;
        }

        /* BUTTON */
        .btn-register {
            border-radius: 25px;
            padding: 10px;
            background: transparent;
            border: 1px solid white;
            color: white;
            transition: 0.3s;
        }

        .btn-register:hover {
            background: white;
            color: #4a6b3f;
            transform: translateY(-1px);
        }

        /* LOGIN LINK */
        .login-link {
            margin-top: 15px;
            font-size: 13px;
        }

        .login-link a {
            color: white;
            text-decoration: underline;
        }

        /* RIGHT TEXT */
        .side-text {
            position: absolute;
            right: 60px;
            bottom: 80px;
            color: #eee;
            font-size: 26px;
            font-style: italic;
            max-width: 350px;
            z-index: 2;
            text-align: right;
        }

        /* LOGO */
        .logo {
            position: absolute;
            top: 30px;
            right: 40px;
            z-index: 2;
        }

        .logo img {
            width: 110px;
        }

        /* ANIMATION */
        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* MOBILE */
        @media (max-width: 768px) {
            .side-text {
                display: none;
            }

            .logo {
                right: 20px;
            }

            .register-container {
                justify-content: center !important;
            }
        }

        input[type="date"] {
            height: 45px;
            padding: 10px;
            border-radius: 10px;
            border: none;
            background: #f1f1f1;
            color: #333;
            width: 100%;
        }

        /* remove weird default styles */
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(30%) sepia(20%) saturate(300%) hue-rotate(60deg);
            cursor: pointer;
        }
    </style>

</head>

<body>

    <!-- LOGO
    <div class="logo">
        <img src="images/logo2.png" alt="ENDURE">
    </div>

    RIGHT TEXT -->
    <!-- <div class="side-text">
        Start your fitness journey with ENDURE.
    </div> -->

    <!-- forms -->
    <div class="container d-flex justify-content-start align-items-center register-container">

        <div class="register-card">

            <h4 class="text-center mb-2">Create Your Account</h4>

            <p class="text-center">
                Start tracking your workouts, runs, and fitness progress — all in one place.
            </p>

            <div class="row">

                <div class="col-6 mb-3">
                    <label>First Name</label>
                    <input id="first_name" type="text" class="form-control maxlength=" 99" required>
                </div>

                <div class="col-6 mb-3">
                    <label>Middle Initial</label>
                    <input id="middle_name" type="text" class="form-control maxlength=" 99">
                </div>

                <div class="col-6 mb-3">
                    <label>Last Name</label>
                    <input id="last_name" type="text" class="form-control maxlength=" 99" required>
                </div>

                <div class="col-6 mb-3">
                    <label>Birthday</label>
                    <input type="date" id="birthday" max="<?= date('Y-m-d') ?>">
                </div>

                <div class="col-12 mb-3">
                    <label>Email Address</label>
                    <input id="email" type="email" class="form-control maxlength=" 149" required>
                </div>

                <div class="col-12 mb-3">
                    <label>Password</label>
                    <input id="password" type="password" class="form-control maxlength=" 254" required>
                </div>

                <div class="mb-3">
                    <label>Confirm Password</label>
                    <input id="confirm_password" type="password" class="form-control" maxlength="254">
                </div>

            </div>

            <div class="d-grid">
                <button type="button" class="btn btn-register" onclick="registerUser()">
                    Create Account
                </button>
            </div>

            <div class="text-center login-link">
                Already have an account?
                <a href="LoginPage.php">Login</a>
            </div>

        </div>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
    <script src="../Scripts/service.js"></script>

</body>

</html>
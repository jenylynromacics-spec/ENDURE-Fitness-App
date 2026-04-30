<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>ENDURE Login</title>

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
            background: url('images/Log in.png') center/cover no-repeat;
        }

        body::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.35);
            z-index: 1;
        }

        .login-container {
            position: relative;
            z-index: 2;
            height: 100vh;
        }

        .login-card {
            background: rgba(74, 107, 63, 0.75);
            backdrop-filter: blur(18px);
            border-radius: 20px;
            padding: 45px 35px;
            color: white;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);

            animation: fadeUp 0.6s ease;
        }

        .login-card h4 {
            font-weight: 600;
            margin-bottom: 10px;
        }

        .login-card p {
            font-size: 13px;
            opacity: 0.85;
            margin-bottom: 25px;
        }

        /* LABEL */
        .login-card label {
            font-size: 13px;
            margin-bottom: 5px;
        }

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

        .btn-login {
            border-radius: 25px;
            padding: 10px;
            background: transparent;
            border: 1px solid white;
            color: white;
            transition: 0.3s;
        }

        .btn-login:hover {
            background: white;
            color: #4a6b3f;
            transform: translateY(-1px);
        }

        .register-link {
            margin-top: 15px;
            font-size: 13px;
        }

        .register-link a {
            color: white;
            text-decoration: underline;
        }

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

        @media (max-width: 768px) {
            .login-container {
                justify-content: center !important;
            }
        }
    </style>

</head>

<body>

    <!-- login -->
    <div class="container d-flex justify-content-end align-items-center login-container">

        <div class="login-card">

            <h4 class="text-center">Welcome Back</h4>

            <p class="text-center">
                Log in to continue tracking your workouts, runs, and fitness progress.
            </p>

            <!-- //email -->
            <div class="mb-3">
                <label>Email Address</label>
                <input id="login_email" type="email" class="form-control">
            </div>

            <!-- password -->
            <div class="mb-3">
                <label>Password</label>
                <input id="login_password" type="password" class="form-control">
            </div>

            <!-- buttons -->
            <div class="d-grid">
                <button class="btn btn-login" onclick="loginUser()">Continue</button>
            </div>

            <!-- register -->
            <div class="text-center register-link">
                Don’t have an account?
                <a href="RegistrationPage.php">Register</a>
            </div>

        </div>

    </div>

    <script src="../Scripts/service.js"></script>

</body>

</html>
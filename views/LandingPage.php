<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>ENDURE</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        /* HERO */
        .hero {
            height: 85vh;
            min-height: 500px;
            background: url('images/bgg.png') center/cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            text-align: center;
        }

        .hero::before {
            content: "";
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.3);
        }

        .hero-logo {
            position: absolute;
            top: 25px;
            left: 50%;
            transform: translateX(-50%);
        }

        .hero-logo img {
            width: 100px;
        }

        .hero-login {
            position: absolute;
            top: 25px;
            right: 30px;
        }

        .overlay {
            max-width: 650px;
            padding: 20px;
            position: relative;
            z-index: 2;
        }

        .hero h1 {
            font-size: 40px;
            font-weight: 600;
            color: white;
        }

        .hero p {
            color: #ddd;
            font-size: 15px;
        }

        /* BUTTONS */
        .btn-main {
            background: #4a6b3f;
            color: white;
            border-radius: 25px;
            padding: 10px 25px;
            border: none;
        }

        .btn-main:hover {
            background: #3d5a34;
        }

        .btn-outline-light {
            border-radius: 25px;
            padding: 10px 25px;
        }

        /* FEATURES */
        .features-section {
            padding: 80px 20px;
            background: url('images/feature-bg.png') right center no-repeat;
            background-size: cover;
        }

        .feature-card {
            background: #fff;
            padding: 30px 20px;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            position: relative;
        }

        .icon-box {
            width: 60px;
            height: 60px;
            background: #f1f1f1;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: #4a6b3f;

            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
        }

        .feature-card h5 {
            margin-top: 40px;
            font-weight: 600;
        }

        .feature-card p {
            font-size: 14px;
            color: #555;
        }

        /* CTA */
        .cta-section {
            padding: 100px 20px;
            background: linear-gradient(to right, #eef3ea, #f4f6f9);
            text-align: center;
        }

        .cta-section h2 {
            font-size: 36px;
            font-weight: 600;
            color: #4a6b3f;
        }

        .cta-section p {
            color: #555;
            margin-top: 10px;
        }

        /* FOOTER */
        .footer {
            background: #6f855b;
            color: #f4f6f9;
            padding: 40px 20px;
            text-align: center;
        }

        .footer .small {
            font-size: 13px;
            opacity: 0.8;
        }
    </style>
</head>

<body>

    <!-- hero -->
    <section class="hero">

        <div class="hero-logo">
            <img src="images/logo2.png" alt="ENDURE Logo">
        </div>

        <div class="hero-login">
            <a href="LoginPage.php" class="btn btn-outline-light btn-sm">Login</a>
        </div>

        <div class="overlay">

            <h1 class="mb-3">
                Track Your Progress.<br>
                Stay Consistent.
            </h1>

            <p class="mb-4">
                ENDURE is a simple fitness tracking system where you can log workouts,
                track your runs, and monitor your progress over time.
            </p>

            <a href="RegistrationPage.php" class="btn btn-main me-2">Get Started</a>
            <a href="#features" class="btn btn-outline-light">Learn More</a>

        </div>

    </section>

    <!-- features -->
    <section id="features" class="features-section">

        <div class="container text-center">

            <h1 class="mb-5">What You Can Do</h1>

            <div class="row g-5">

                <div class="col-md-6">
                    <div class="feature-card">
                        <div class="icon-box">
                            <span class="material-icons">fitness_center</span>
                        </div>
                        <h5>Workout Logging</h5>
                        <p>Record your workouts daily and stay consistent.</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="feature-card">
                        <div class="icon-box">
                            <span class="material-icons">directions_run</span>
                        </div>
                        <h5>Running Tracker</h5>
                        <p>Track your runs, distance, and time.</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="feature-card">
                        <div class="icon-box">
                            <span class="material-icons">analytics</span>
                        </div>
                        <h5>Body Progress</h5>
                        <p>Monitor your weight and improvements over time.</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="feature-card">
                        <div class="icon-box">
                            <span class="material-icons">insights</span>
                        </div>
                        <h5>Performance Overview</h5>
                        <p>View your stats and overall progress.</p>
                    </div>
                </div>

            </div>

        </div>

    </section>

    <!-- cta -->
    <section class="cta-section">

        <div class="container">

            <h2>Start Your Fitness Journey</h2>

            <p>
                Track your progress and stay motivated with ENDURE.
            </p>

            <a href="RegistrationPage.php" class="btn btn-main mt-3">
                Create Account
            </a>

        </div>

    </section>

    <!-- footer -->
    <footer class="footer">

        <div class="container">

            <p class="mb-1">© 2026 ENDURE. All rights reserved.</p>
            <p class="small">A simple fitness tracking system</p>
            <p class="small">Developed by Jenlyn P. Roma</p>

        </div>

    </footer>

</body>

</html>
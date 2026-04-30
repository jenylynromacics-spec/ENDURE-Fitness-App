<?php
session_start();

require_once "../BL/UserManagement.php";
require_once "../helper/sendEmail.php";

$user = new UserManagement();


// ================= REGISTER =================
if (isset($_POST["first_name"])) {

    $result = $user->registerUser(
        $_POST["first_name"],
        $_POST["middle_name"],
        $_POST["last_name"],
        $_POST["birthday"],
        $_POST["email"],
        $_POST["password"]
    );

    // ✅ SEND EMAIL IF SUCCESS
    if ($result === "registered") {

        $name = htmlspecialchars($_POST["first_name"]);
        $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);

        if ($email) {

            $body = '
<div style="font-family: Arial, sans-serif; background-color: #f4f6f9; padding: 20px;">
    
    <div style="max-width: 500px; margin: auto; background: #ffffff; border-radius: 10px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">

        <!-- HEADER -->
        <h2 style="color: #1e88e5; text-align: center; margin-bottom: 10px;">
            ENDURE 💪
        </h2>

        <p style="text-align: center; color: #777; font-size: 14px;">
            Discipline in Motion
        </p>

        <hr style="margin: 20px 0;">

        <!-- CONTENT -->
        <h3 style="color: #333;">Welcome, ' . $name . ' 👋</h3>

        <p style="color: #555; font-size: 14px;">
            Your account has been successfully created.
        </p>

        <p style="color: #555; font-size: 14px;">
            You can now start tracking your fitness journey with ENDURE — log your runs, workouts, and progress all in one place.
        </p>

        <hr style="margin: 20px 0;">

        <!-- FOOTER -->
        <p style="font-size: 12px; color: #999; text-align: center;">
            This is an automated message from ENDURE.<br>
            Stay consistent. Stay strong.
        </p>

    </div>

</div>
';

            sendEmail(
                $email,
                $name,
                "Welcome to ENDURE",
                $body
            );
        }

        echo "registered";
        exit;
    }

    echo $result;
    exit;
}


// ================= LOGIN =================
if (isset($_POST["login_email"])) {

    $user->loginUser(
        $_POST["login_email"],
        $_POST["login_password"]
    );

    exit;
}


// ================= LOGOUT =================
if (isset($_POST["logout"])) {

    $user->logoutUser();
    header("Location: ../views/LandingPage.php");
    exit;
}

<?php

class UserModel
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function checkEmail($email)
    {
        $query = "SELECT user_id FROM tbl_users WHERE email = :email";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createUser($firstName, $middleName, $lastName, $birthday, $email, $password, $created_at)
    {
        $query = "INSERT INTO tbl_users 
                (first_name, middle_name, last_name, birthday, email, password, role_id, created_at)
              VALUES 
                (:first_name, :middle_name, :last_name, :birthday, :email, :password, 2, :created_at)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":first_name", $firstName);
        $stmt->bindParam(":middle_name", $middleName);
        $stmt->bindParam(":last_name", $lastName);
        $stmt->bindParam(":birthday", $birthday);
        $stmt->bindParam(":email", $email);
        $stmt->bindParam(":password", $password);
        $stmt->bindParam(":created_at", $created_at);

        return $stmt->execute();
    }

    public function getUserByEmail($email)
    {
        $query = "SELECT 
                    u.user_id,
                    u.first_name,
                    u.middle_name,
                    u.last_name,
                    u.birthday,
                    u.email,
                    u.password,
                    u.role_id
                  FROM tbl_users u
                  WHERE u.email = :email";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllUsers()
    {
        $query = "SELECT 
                    user_id,
                    first_name,
                    middle_name,
                    last_name,
                    birthday,
                    email,
                    created_at
                  FROM tbl_users
                  ORDER BY user_id DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ========================
    // DASHBOARD CARD FUNCTIONS
    // ========================

    public function cardTotalRuns($userID)
    {
        $query = "SELECT COUNT(*) AS total_runs 
                  FROM tbl_runs 
                  WHERE user_id = :user_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userID);
        $stmt->execute();

        return $stmt;
    }

    public function cardAveragePace($userID)
    {
        $query = "SELECT AVG(pace_per_km) as avg_pace
                  FROM tbl_runs 
                  WHERE user_id = :user_id AND distance_km > 0";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userID);
        $stmt->execute();

        return $stmt;
    }

    public function cardCurrentWeight($userID)
    {
        $query = "SELECT weight_kg 
              FROM tbl_body_metrics 
              WHERE user_id = :user_id 
              ORDER BY created_at DESC 
              LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userID);
        $stmt->execute();

        return $stmt;
    }

    public function cardActiveGoals($userID)
    {
        $query = "SELECT COUNT(*) AS total_goals 
                  FROM tbl_goals 
                  WHERE user_id = :user_id 
                  AND status = 'active'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userID);
        $stmt->execute();

        return $stmt;
    }

    public function insertRun($userID, $distance, $time, $pace, $date)
    {
        $query = "INSERT INTO tbl_runs 
              (user_id, distance_km, time_minutes, pace_per_km, run_date)
              VALUES (:user_id, :distance, :time, :pace, :date)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userID);
        $stmt->bindParam(":distance", $distance);
        $stmt->bindParam(":time", $time);
        $stmt->bindParam(":pace", $pace);
        $stmt->bindParam(":date", $date);

        return $stmt->execute();
    }

    public function insertGoal($userID, $type, $target, $date)
    {
        $query = "INSERT INTO tbl_goals 
              (user_id, goal_type_id, target_value, target_date, status)
              VALUES (:user_id, :type, :target, :date, 'active')";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userID);
        $stmt->bindParam(":type", $type);
        $stmt->bindParam(":target", $target);
        $stmt->bindParam(":date", $date);

        return $stmt->execute();
    }

    public function insertWeight($userID, $weight)
    {
        $query = "INSERT INTO tbl_body_metrics 
              (user_id, weight_kg, created_at)
              VALUES (:user_id, :weight, NOW())";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userID);
        $stmt->bindParam(":weight", $weight);

        return $stmt->execute();
    }

    public function insertWorkout($userID, $type, $duration, $date)
    {
        $query = "INSERT INTO tbl_workouts 
              (user_id, workout_type_id, duration_minutes, workout_date)
              VALUES (:user_id, :type, :duration, :date)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userID);
        $stmt->bindParam(":type", $type);
        $stmt->bindParam(":duration", $duration);
        $stmt->bindParam(":date", $date);

        return $stmt->execute();
    }

    public function getRecentActivities($userID)
    {
        $query = "
        SELECT 'Run' AS activity,
               CONCAT(distance_km, ' km | ', time_minutes, ' mins') AS info,
               run_date AS date
        FROM tbl_runs
        WHERE user_id = :user_id

        UNION

        SELECT 'Workout' AS activity,
               CONCAT(wt.workout_name, ' | ', w.duration_minutes, ' mins') AS info,
               w.workout_date AS date
        FROM tbl_workouts w
        JOIN tbl_workout_types wt ON w.workout_type_id = wt.workout_type_id
        WHERE w.user_id = :user_id

        UNION

        SELECT 'Weight' AS activity,
               CONCAT(weight_kg, ' kg') AS info,
               created_at AS date
        FROM tbl_body_metrics
        WHERE user_id = :user_id

        ORDER BY date DESC
        LIMIT 5
    ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userID);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getWorkoutTypes()
    {
        $query = "SELECT workout_type_id, workout_name FROM tbl_workout_types";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getGoalTypes()
    {
        $query = "SELECT goal_type_id, goal_name FROM tbl_goal_types";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRunProgress($userID)
    {
        $query = "SELECT run_date, distance_km 
              FROM tbl_runs 
              WHERE user_id = :user_id
              ORDER BY run_date ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userID);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getWorkoutChart($userID)
    {
        $query = "SELECT wt.workout_name, COUNT(*) as total
              FROM tbl_workouts w
              JOIN tbl_workout_types wt 
              ON w.workout_type_id = wt.workout_type_id
              WHERE w.user_id = :user_id
              GROUP BY wt.workout_name";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userID);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getWeightProgress($userID)
    {
        $query = "SELECT created_at, weight_kg 
              FROM tbl_body_metrics
              WHERE user_id = :user_id
              ORDER BY created_at ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userID);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

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

    public function createUser($fName, $mName, $lName, $birthday, $contact, $email, $password, $created_at)
    {
        $query = "INSERT INTO tbl_users 
        (first_name, middle_name, last_name, birthday, contact_number, email, role_id, password, created_at)
        VALUES 
        (:fName, :mName, :lName, :birthday, :contact, :email, 2, :password, :created_at)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":fName", $fName);
        $stmt->bindParam(":mName", $mName);
        $stmt->bindParam(":lName", $lName);
        $stmt->bindParam(":birthday", $birthday);
        $stmt->bindParam(":contact", $contact);
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

    public function cardActiveGoals($userID)
    {
        $query = "SELECT COUNT(*) as total 
              FROM tbl_goals 
              WHERE user_id = :user_id 
              AND status = 'Active'";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userID);
        $stmt->execute();

        return $stmt;
    }

    public function fetchGoalsByUser($user_id)
    {
        $query = "SELECT g.*, t.goal_name 
              FROM tbl_goals g
              JOIN tbl_goal_types t ON g.goal_type_id = t.goal_type_id
              WHERE g.user_id = :user_id
              ORDER BY g.created_at DESC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $user_id);
        $stmt->execute();

        return $stmt;
    }

    public function fetchGoalTypes()
    {
        $query = "SELECT * FROM tbl_goal_types";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function insertGoal($userID, $type, $target, $date)
    {
        $query = "INSERT INTO tbl_goals (user_id, goal_type_id, target_value, target_date)
              VALUES (:user_id, :type, :target, :date)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userID);
        $stmt->bindParam(":type", $type);
        $stmt->bindParam(":target", $target);
        $stmt->bindParam(":date", $date);

        return $stmt->execute();
    }

    public function insertWeight($userID, $weight, $date)
    {
        $query = "INSERT INTO tbl_body_metrics 
        (user_id, weight_kg, record_date) 
        VALUES (:user_id, :weight, :date)";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":user_id", $userID);
        $stmt->bindParam(":weight", $weight);
        $stmt->bindParam(":date", $date);

        return $stmt->execute();
    }
    public function getBodyOverview($userID)
    {
        $query = "
    SELECT 
        (SELECT weight_kg FROM tbl_body_metrics 
         WHERE user_id = :user_id 
         ORDER BY record_date DESC LIMIT 1) AS current_weight,

        (SELECT weight_kg FROM tbl_body_metrics 
         WHERE user_id = :user_id 
         ORDER BY record_date ASC LIMIT 1) AS start_weight
    ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userID);
        $stmt->execute();

        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($data && $data['current_weight'] && $data['start_weight']) {
            $data['weight_change'] = $data['current_weight'] - $data['start_weight'];
        } else {
            $data['weight_change'] = 0;
        }

        return $data;
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
    public function getWeightHistory($userID)
    {
        $query = "
        SELECT weight_kg, record_date
        FROM tbl_body_metrics
        WHERE user_id = :user_id
        ORDER BY record_date DESC
        LIMIT 5
    ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userID);
        $stmt->execute();

        return $stmt;
    }

    public function getLatestWeight($userID)
    {
        $query = "SELECT weight_kg FROM tbl_body_metrics 
              WHERE user_id = :user_id 
              ORDER BY created_at DESC LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userID);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['weight_kg'] : 0;
    }

    public function getStartingWeight($userID)
    {
        $query = "SELECT weight_kg FROM tbl_body_metrics 
              WHERE user_id = :user_id 
              ORDER BY created_at ASC LIMIT 1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userID);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['weight_kg'] : 0;
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

    public function getWorkoutOverview($userID)
    {
        $query = "
    SELECT 
        COUNT(*) AS total_workouts,
        SUM(duration_minutes) AS total_duration,
        (
            SELECT wt.workout_name
            FROM tbl_workouts w
            JOIN tbl_workout_types wt 
                ON w.workout_type_id = wt.workout_type_id
            WHERE w.user_id = :user_id
            GROUP BY w.workout_type_id
            ORDER BY COUNT(*) DESC
            LIMIT 1
        ) AS most_frequent
    FROM tbl_workouts
    WHERE user_id = :user_id
    ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userID);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRecentWorkouts($userID)
    {
        $query = "
    SELECT 
        wt.workout_name,
        w.duration_minutes,
        w.workout_date
    FROM tbl_workouts w
    JOIN tbl_workout_types wt 
        ON w.workout_type_id = wt.workout_type_id
    WHERE w.user_id = :user_id
    ORDER BY w.workout_date DESC
    LIMIT 5
    ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userID);
        $stmt->execute();

        return $stmt;
    }
    public function getRecentActivities($userID)
    {
        $query = "
    (
    SELECT 'Run' AS activity, 
           CONCAT(distance_km, ' km') AS info, 
           run_date AS date
    FROM tbl_runs
    WHERE user_id = :user_id
    ORDER BY run_date DESC
    LIMIT 2
)

UNION ALL

(
    SELECT 'Workout' AS activity, 
           CONCAT(wt.workout_name, ' | ', w.duration_minutes, ' mins') AS info, 
           w.workout_date AS date
    FROM tbl_workouts w
    JOIN tbl_workout_types wt ON w.workout_type_id = wt.workout_type_id
    WHERE w.user_id = :user_id
    ORDER BY w.workout_date DESC
    LIMIT 2
)

UNION ALL

(
    SELECT 'Goal' AS activity,
           CONCAT(gt.goal_name, ' | Target: ', g.target_value) AS info,
           g.target_date AS date
    FROM tbl_goals g
    JOIN tbl_goal_types gt ON g.goal_type_id = gt.goal_type_id
    WHERE g.user_id = :user_id
    ORDER BY g.target_date DESC
    LIMIT 2
)

ORDER BY date DESC
LIMIT 5
    ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userID);
        $stmt->execute();

        return $stmt;
    }

    public function getWorkoutTypes()
    {
        $query = "SELECT workout_type_id, workout_name FROM tbl_workout_types";

        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC); // 🔥 THIS LINE FIXES EVERYTHING
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

    //cc: Run Page Cards
    public function getRunOverview($userID)
    {
        $query = "
        SELECT 
            SUM(distance_km) AS total_distance,
            AVG(pace_per_km) AS avg_pace,
            MIN(pace_per_km) AS best_pace
        FROM tbl_runs
        WHERE user_id = :user_id
    ";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":user_id", $userID);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getRecentRuns($userID)
    {
        $query = "
        SELECT 
            distance_km,
            time_minutes,
            pace_per_km,
            run_date
        FROM tbl_runs
        WHERE user_id = :user_id
        ORDER BY run_date DESC
        LIMIT 5
    ";

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

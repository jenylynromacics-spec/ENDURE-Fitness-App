["first_name", "middle_name", "last_name"].forEach((id) => {
  const input = document.getElementById(id);

  if (input) {
    input.addEventListener("input", function () {
      this.value = this.value.replace(/[^a-zA-Z\s]/g, "");
    });
  }
});

function registerUser() {
  let firstName = document.getElementById("first_name").value.trim();
  let middleName = document.getElementById("middle_name").value.trim();
  let lastName = document.getElementById("last_name").value.trim();
  let birthday = document.getElementById("birthday").value;
  let email = document.getElementById("email").value.trim();
  let password = document.getElementById("password").value.trim();

  // required fields
  if (!firstName || !lastName || !email || !password) {
    alert("Please fill all required fields");
    return;
  }

  // min length
  if (firstName.length < 2 || lastName.length < 2) {
    alert("Name must be at least 2 characters");
    return;
  }

  // email val
  let emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailPattern.test(email)) {
    alert("Invalid email format");
    return;
  }

  // Pass val
  let strongPassword = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/;

  if (!strongPassword.test(password)) {
    alert(
      "Password must be at least 8 characters with uppercase, lowercase, and number",
    );
    return;
  }

  // 🔥 ADD THIS
  let confirmPassword = document
    .getElementById("confirm_password")
    .value.trim();

  //confirm pass
  if (password !== confirmPassword) {
    alert("Passwords do not match");
    return;
  }

  // SEND DATA
  let formData = new URLSearchParams();
  formData.append("first_name", firstName);
  formData.append("middle_name", middleName);
  formData.append("last_name", lastName);
  formData.append("birthday", birthday);
  formData.append("email", email);
  formData.append("password", password);

  fetch("../controllers/UserController.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.text())
    .then((data) => {
      if (data === "registered") {
        alert("Registered successfully!");
        window.location.href = "LoginPage.php";
      } else {
        alert(data);
      }
    });
}
function loginUser() {
  let formData = new URLSearchParams();

  formData.append("login_email", document.getElementById("login_email").value);
  formData.append(
    "login_password",
    document.getElementById("login_password").value,
  );

  fetch("../controllers/UserController.php", {
    method: "POST",
    body: formData,
  })
    .then((res) => res.text())
    .then((data) => {
      console.log(data);

      if (data === "user") {
        window.location.href = "Dashboard.php";
      } else {
        alert("Login failed: " + data);
      }
    });
}

function logRun() {
  var userID = document.getElementById("user_id").value;
  var distance = document.getElementById("distance_km").value;
  var time = document.getElementById("time_minutes").value;
  var date = document.getElementById("run_date").value;

  if (distance === "" || time === "") {
    M.toast({ html: "Please complete all fields" });
    return;
  }

  $.post(
    "../controllers/RunController.php",
    {
      user_id: userID,
      distance_km: distance,
      time_minutes: time,
      run_date: date,
    },
    function () {
      M.toast({ html: "Run saved!" });
      window.location.href = "Dashboard.php";
    },
  );
}

// ================= WORKOUT =================
function logWorkout() {
  let userID = document.getElementById("user_id").value;
  let type = document.getElementById("workout_type_id").value;
  let duration = document.getElementById("duration_minutes").value;

  if (type === "" || duration === "") {
    alert("Complete all fields");
    return;
  }

  fetch("../controllers/WorkoutController.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `user_id=${userID}&workout_type_id=${type}&duration_minutes=${duration}`,
  })
    .then((res) => res.text())
    .then((data) => {
      console.log(data);

      if (data === "success") {
        alert("Workout saved!");
        window.location.href = "Dashboard.php";
      } else {
        alert("Error saving workout");
      }
    });
}

// ================= BODY METRICS =================
function logWeight() {
  let userID = document.getElementById("user_id").value;
  let weight = document.getElementById("weight_kg").value;

  if (weight === "") {
    alert("Enter weight");
    return;
  }

  fetch("../controllers/BodyMetricController.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `user_id=${userID}&weight_kg=${weight}`,
  })
    .then((res) => res.text())
    .then((data) => {
      console.log(data);

      if (data === "success") {
        alert("Weight saved!");
        window.location.href = "Dashboard.php";
      } else {
        alert("Error saving weight");
      }
    });
}

// ================= GOALS =================
function createGoal() {
  let userID = document.getElementById("user_id").value;
  let type = document.getElementById("goal_type_id").value;
  let target = document.getElementById("target_value").value;
  let date = document.getElementById("target_date").value;

  if (type === "" || target === "" || date === "") {
    alert("Complete all fields");
    return;
  }

  fetch("../controllers/GoalController.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `user_id=${userID}&goal_type_id=${type}&target_value=${target}&target_date=${date}`,
  })
    .then((res) => res.text())
    .then((data) => {
      console.log(data);

      if (data === "success") {
        alert("Goal saved!");
        window.location.href = "Dashboard.php";
      } else {
        alert("Error saving goal");
      }
    });
}

document.addEventListener("DOMContentLoaded", function () {
  var elems = document.querySelectorAll(".datepicker");
  M.Datepicker.init(elems, {
    format: "yyyy-mm-dd",
    yearRange: 100,
    maxDate: new Date(), // ❗ disables future dates
    defaultDate: new Date(2000, 0, 1),
    setDefaultDate: true,
  });
});

const ctx = document.getElementById("runChart");

if (ctx && window.runChartData) {
  new Chart(ctx, {
    type: "line",
    data: {
      labels: window.runChartData.labels,
      datasets: [
        {
          label: "Distance (km)",
          data: window.runChartData.data,
          borderWidth: 2,
          tension: 0.3,
          pointRadius: 6,
          pointBackgroundColor: "#4ade80",
          pointHoverRadius: 8,
          fill: true,
        },
      ],
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: true,
        },
      },
    },
  });
}

document.addEventListener("DOMContentLoaded", function () {
  const ctx2 = document.getElementById("workoutChart");

  if (!ctx2) return;

  new Chart(ctx2, {
    type: "bar",
    data: {
      labels: window.workoutChartData.labels,
      datasets: [
        {
          label: "Workout Count",
          data: window.workoutChartData.data,
          borderWidth: 1,
          backgroundColor: [
            "#4ade80", // green (strength)
            "#60a5fa", // blue (cardio)
            "#facc15", // yellow (rest if meron)
          ],
          borderRadius: 10,
        },
      ],
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
        },
      },
    },
  });
});

document.addEventListener("DOMContentLoaded", function () {
  const ctx3 = document.getElementById("weightChart");

  if (!ctx3) return;

  new Chart(ctx3, {
    type: "line",
    data: {
      labels: window.weightChartData.labels,
      datasets: [
        {
          label: "Weight (kg)",
          data: window.weightChartData.data,
          borderWidth: 2,
          tension: 0.3,
          pointRadius: 5,
          fill: true,
        },
      ],
    },
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: false,
        },
      },
    },
  });
});

["first_name", "middle_name", "last_name"].forEach((id) => {
  const input = document.getElementById(id);

  if (input) {
    input.addEventListener("input", function () {
      this.value = this.value.replace(/[^a-zA-Z\s]/g, "");
    });
  }
});

function registerUser() {
  console.log("registerUser function started");

  // =========================
  // GET VALUES
  // =========================
  var firstName = document.getElementById("first_name").value.trim();
  var middleName = document.getElementById("middle_name").value.trim();
  var lastName = document.getElementById("last_name").value.trim();
  var birthday = document.getElementById("birthday").value;
  var email = document.getElementById("email").value.trim();
  var contactNumber = document.getElementById("contact_number").value.trim();
  var password = document.getElementById("password").value;
  var confirmPassword = document.getElementById("confirm_password").value;

  // =========================
  // VALIDATIONS
  // =========================
  var isFirstNameValid = firstName !== "";
  var isLastNameValid = lastName !== "";
  var isBirthdayValid = birthday !== "";

  var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  var isEmailValid = emailPattern.test(email);

  var contactPattern = /^[0-9]{11}$/;
  var isContactValid = contactPattern.test(contactNumber);

  var isPasswordLengthValid = password.length >= 8;
  var isPasswordMatch = password === confirmPassword;

  // =========================
  // CHECKS
  // =========================

  if (!isFirstNameValid) {
    Swal.fire({
      icon: "warning",
      title: "First Name Required",
      text: "Please enter your first name",
      confirmButtonColor: "#556b2f",
    });
    return;
  }

  if (!isLastNameValid) {
    Swal.fire({
      icon: "warning",
      title: "Last Name Required",
      text: "Please enter your last name",
      confirmButtonColor: "#556b2f",
    });
    return;
  }

  if (!isBirthdayValid) {
    Swal.fire({
      icon: "warning",
      title: "Birthday Required",
      text: "Please select your birthday",
      confirmButtonColor: "#556b2f",
    });
    return;
  }

  if (!isEmailValid) {
    Swal.fire({
      icon: "error",
      title: "Invalid Email",
      text: "Please enter a valid email address",
      confirmButtonColor: "#556b2f",
    });
    return;
  }

  if (!isContactValid) {
    Swal.fire({
      icon: "error",
      title: "Invalid Contact Number",
      text: "Contact number must be 11 digits",
      confirmButtonColor: "#556b2f",
    });
    return;
  }

  if (!isPasswordLengthValid) {
    Swal.fire({
      icon: "warning",
      title: "Weak Password",
      text: "Password must be at least 8 characters",
      confirmButtonColor: "#556b2f",
    });
    return;
  }

  if (!isPasswordMatch) {
    Swal.fire({
      icon: "error",
      title: "Password Mismatch",
      text: "Passwords do not match",
      confirmButtonColor: "#556b2f",
    });
    return;
  }

  // =========================
  // AJAX
  // =========================

  $.ajax({
    url: "../controllers/UserController.php",
    method: "POST",
    data: {
      first_name: firstName,
      middle_name: middleName,
      last_name: lastName,
      birthday: birthday,
      email: email,
      contact_number: contactNumber,
      password: password,
    },

    success: function (response) {
      console.log(response);

      Swal.fire({
        icon: "success",
        title: "Registration Successful!",
        text: response,
        confirmButtonColor: "#556b2f",
      }).then(() => {
        window.location.href = "LoginPage.php";
      });
    },

    error: function (xhr, status, error) {
      console.log(error);

      Swal.fire({
        icon: "error",
        title: "AJAX Error",
        text: "Something went wrong while processing your request",
        confirmButtonColor: "#556b2f",
      });
    },
  });
}

function loginUser() {
  var email = document.getElementById("login_email").value.trim();
  var password = document.getElementById("login_password").value;

  // =========================
  // VALIDATION
  // =========================

  if (!email || !password) {
    Swal.fire({
      icon: "warning",
      title: "Missing Fields",
      text: "Please enter email and password.",
      background: "#0f1c14",
      color: "#fff",
      confirmButtonColor: "#22c55e",
    });
    return;
  }

  // EMAIL FORMAT CHECK
  var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailPattern.test(email)) {
    Swal.fire({
      icon: "error",
      title: "Invalid Email",
      text: "Please enter a valid email address.",
      background: "#0f1c14",
      color: "#fff",
      confirmButtonColor: "#ef4444",
    });
    return;
  }

  // PASSWORD LENGTH CHECK (optional but good)
  if (password.length < 6) {
    Swal.fire({
      icon: "warning",
      title: "Invalid Password",
      text: "Password must be at least 6 characters.",
      background: "#0f1c14",
      color: "#fff",
      confirmButtonColor: "#f59e0b",
    });
    return;
  }

  // =========================
  // FETCH LOGIN
  // =========================
  fetch("../controllers/UserController.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `login_email=${email}&login_password=${password}`,
  })
    .then((res) => res.text())
    .then((data) => {
      console.log("LOGIN RESPONSE:", data);

      if (data.trim() === "user") {
        Swal.fire({
          icon: "success",
          title: "Welcome Back!",
          text: "Login successful.",
          background: "#0f1c14",
          color: "#fff",
          confirmButtonColor: "#22c55e",
          showConfirmButton: false,
          timer: 1500,
        });

        setTimeout(() => {
          window.location.href = "Dashboard.php";
        }, 1500);
      } else {
        Swal.fire({
          icon: "error",
          title: "Login Failed",
          text: "Invalid email or password.",
          background: "#0f1c14",
          color: "#fff",
          confirmButtonColor: "#ef4444",
        });
      }
    })
    .catch(() => {
      Swal.fire({
        icon: "error",
        title: "Server Error",
        text: "Something went wrong. Try again.",
        background: "#0f1c14",
        color: "#fff",
        confirmButtonColor: "#ef4444",
      });
    });
}

function logRun() {
  var userID = document.getElementById("user_id").value;
  var distance = document.getElementById("distance_km").value;
  var timeInput = document.getElementById("time_minutes").value; // HH:MM
  var date = document.getElementById("run_date").value;

  // VALIDATION
  if (!distance || !timeInput || !date) {
    Swal.fire({
      icon: "warning",
      title: "Missing Fields",
      text: "Please complete all fields.",
      background: "#0f1c14",
      color: "#fff",
      confirmButtonColor: "#22c55e",
    });
    return;
  }

  if (distance <= 0) {
    Swal.fire({
      icon: "error",
      title: "Invalid Input",
      text: "Distance must be greater than 0.",
      background: "#0f1c14",
      color: "#fff",
      confirmButtonColor: "#22c55e",
    });
    return;
  }

  // 🔥 CONVERT HH:MM → TOTAL MINUTES
  var cleanTime = timeInput.replace(/ AM| PM/i, ""); // remove AM/PM if any
  var parts = cleanTime.split(":");

  if (parts.length !== 2) {
    Swal.fire({
      icon: "error",
      title: "Invalid Time Format",
      text: "Please select a valid time.",
      background: "#0f1c14",
      color: "#fff",
      confirmButtonColor: "#22c55e",
    });
    return;
  }

  var hours = parseInt(parts[0]) || 0;
  var minutes = parseInt(parts[1]) || 0;

  var totalMinutes = hours * 60 + minutes;

  if (totalMinutes <= 0) {
    Swal.fire({
      icon: "error",
      title: "Invalid Time",
      text: "Time must be greater than 0.",
      background: "#0f1c14",
      color: "#fff",
      confirmButtonColor: "#22c55e",
    });
    return;
  }

  // AJAX
  $.post(
    "../controllers/RunController.php",
    {
      user_id: userID,
      distance_km: distance,
      time_minutes: totalMinutes, // ✅ converted value
      run_date: date,
    },
    function (response) {
      console.log("RUN RESPONSE:", response);

      if (response.trim() === "success") {
        Swal.fire({
          icon: "success",
          title: "Run Saved!",
          html: `
            <b>${distance} km</b><br>
            Time: ${timeInput}
          `,
          background: "#0f1c14",
          color: "#fff",
          confirmButtonColor: "#22c55e",
          showConfirmButton: false,
          timer: 1500,
        });

        setTimeout(() => {
          location.reload();
        }, 1500);
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Failed to save run. Try again.",
          background: "#0f1c14",
          color: "#fff",
          confirmButtonColor: "#ef4444",
        });
      }
    },
  );
}

function logWorkout() {
  var user_id = document.getElementById("user_id").value;
  var type = document.getElementById("workout_type").value;
  var timeInput = document.getElementById("workout_time").value; // make sure this exists
  var date = document.getElementById("workout_date").value;

  console.log("TIME ELEMENT:", document.getElementById("workout_time"));

  if (!type || !timeInput || !date) {
    Swal.fire({
      icon: "warning",
      title: "Missing Fields",
      text: "Please complete all fields.",
      background: "#0f1c14",
      color: "#fff",
      confirmButtonColor: "#22c55e",
    });
    return;
  }

  if (!timeInput.includes(":")) {
    Swal.fire({
      icon: "error",
      title: "Invalid Time",
      text: "Please select a valid duration.",
      background: "#0f1c14",
      color: "#fff",
      confirmButtonColor: "#ef4444",
    });
    return;
  }

  var cleanTime = timeInput.replace(/ AM| PM/i, "");
  var parts = cleanTime.split(":");

  var hours = parseInt(parts[0]) || 0;
  var minutes = parseInt(parts[1]) || 0;

  var totalMinutes = hours * 60 + minutes;

  if (totalMinutes <= 0) {
    Swal.fire({
      icon: "error",
      title: "Invalid Duration",
      text: "Duration must be greater than 0.",
      background: "#0f1c14",
      color: "#fff",
      confirmButtonColor: "#ef4444",
    });
    return;
  }

  var today = new Date();
  var selectedDate = new Date(date);

  today.setHours(0, 0, 0, 0);
  selectedDate.setHours(0, 0, 0, 0);

  if (selectedDate > today) {
    Swal.fire({
      icon: "error",
      title: "Invalid Date",
      text: "Future workouts are not allowed.",
      background: "#0f1c14",
      color: "#fff",
      confirmButtonColor: "#ef4444",
    });
    return;
  }

  fetch("../controllers/WorkoutController.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `user_id=${user_id}&workout_type_id=${type}&duration_minutes=${totalMinutes}&workout_date=${date}`,
  })
    .then((res) => res.text())
    .then((response) => {
      if (response.trim() === "success") {
        Swal.fire({
          icon: "success",
          title: "Workout Saved!",
          html: `<b>${timeInput}</b><br>${document.getElementById("workout_type").selectedOptions[0].text}`,
          background: "#0f1c14",
          color: "#fff",
          confirmButtonColor: "#22c55e",
          showConfirmButton: false,
          timer: 1500,
        });

        setTimeout(() => location.reload(), 1500);
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Failed to save workout.",
          background: "#0f1c14",
          color: "#fff",
          confirmButtonColor: "#ef4444",
        });
      }
    })
    .catch(() => {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Something went wrong.",
        background: "#0f1c14",
        color: "#fff",
        confirmButtonColor: "#ef4444",
      });
    });
}

function logWeight() {
  let userID = document.getElementById("user_id").value;
  let weightInput = document.getElementById("weight_kg").value;
  let date = document.getElementById("record_date").value;

  let weight = parseFloat(weightInput);

  // =========================
  // VALIDATION
  // =========================

  // REQUIRED
  if (!weightInput || !date) {
    Swal.fire({
      icon: "warning",
      title: "Missing Fields",
      text: "Please enter weight and date.",
      background: "#0f1c14",
      color: "#fff",
      confirmButtonColor: "#22c55e",
    });
    return;
  }

  // NUMBER CHECK
  if (isNaN(weight)) {
    Swal.fire({
      icon: "error",
      title: "Invalid Input",
      text: "Weight must be a number.",
      background: "#0f1c14",
      color: "#fff",
      confirmButtonColor: "#ef4444",
    });
    return;
  }

  // POSITIVE CHECK
  if (weight <= 0) {
    Swal.fire({
      icon: "error",
      title: "Invalid Weight",
      text: "Weight must be greater than 0.",
      background: "#0f1c14",
      color: "#fff",
      confirmButtonColor: "#ef4444",
    });
    return;
  }

  // REALISTIC RANGE
  if (weight < 20 || weight > 300) {
    Swal.fire({
      icon: "warning",
      title: "Unusual Value",
      text: "Please enter a realistic weight (20–300 kg).",
      background: "#0f1c14",
      color: "#fff",
      confirmButtonColor: "#f59e0b",
    });
    return;
  }

  // DATE VALIDATION
  let today = new Date();
  let selectedDate = new Date(date);

  today.setHours(0, 0, 0, 0);
  selectedDate.setHours(0, 0, 0, 0);

  if (selectedDate > today) {
    Swal.fire({
      icon: "error",
      title: "Invalid Date",
      text: "Future entries are not allowed.",
      background: "#0f1c14",
      color: "#fff",
      confirmButtonColor: "#ef4444",
    });
    return;
  }

  // =========================
  // FETCH
  // =========================
  fetch("../controllers/BodyMetricController.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `user_id=${userID}&weight_kg=${weight}&record_date=${date}`,
  })
    .then((res) => res.text())
    .then((data) => {
      console.log("SERVER:", data);

      if (data.trim() === "success") {
        Swal.fire({
          icon: "success",
          title: "Weight Logged!",
          html: `
            <b>${weight} kg</b><br>
            ${new Date(date).toLocaleDateString()}
          `,
          background: "#0f1c14",
          color: "#fff",
          confirmButtonColor: "#22c55e",
          showConfirmButton: false,
          timer: 1500,
        });

        setTimeout(() => {
          location.reload();
        }, 1500);
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Failed to save weight.",
          background: "#0f1c14",
          color: "#fff",
          confirmButtonColor: "#ef4444",
        });
      }
    })
    .catch(() => {
      Swal.fire({
        icon: "error",
        title: "Error",
        text: "Something went wrong.",
        background: "#0f1c14",
        color: "#fff",
        confirmButtonColor: "#ef4444",
      });
    });
}

function saveBodyMetric() {
  var userID = document.getElementById("user_id").value;
  var weight = parseFloat(document.getElementById("weight").value);
  var date = document.getElementById("record_date").value;

  // REQUIRED
  if (!weight || !date) {
    Swal.fire({
      icon: "warning",
      title: "Missing Fields",
      text: "Please complete all fields.",
      background: "#0f1c14",
      color: "#fff",
      confirmButtonColor: "#22c55e",
    });
    return;
  }

  // NUMBER CHECK
  if (isNaN(weight)) {
    Swal.fire({
      icon: "error",
      title: "Invalid Input",
      text: "Weight must be a number.",
      background: "#0f1c14",
      color: "#fff",
      confirmButtonColor: "#ef4444",
    });
    return;
  }

  // POSITIVE
  if (weight <= 0) {
    Swal.fire({
      icon: "error",
      title: "Invalid Weight",
      text: "Weight must be greater than 0.",
      background: "#0f1c14",
      color: "#fff",
      confirmButtonColor: "#ef4444",
    });
    return;
  }

  // REALISTIC RANGE
  if (weight < 20 || weight > 300) {
    Swal.fire({
      icon: "warning",
      title: "Unusual Weight",
      text: "Please enter a realistic weight (20–300 kg).",
      background: "#0f1c14",
      color: "#fff",
      confirmButtonColor: "#f59e0b",
    });
    return;
  }

  // DATE VALIDATION
  var today = new Date();
  var selectedDate = new Date(date);

  today.setHours(0, 0, 0, 0);
  selectedDate.setHours(0, 0, 0, 0);

  if (selectedDate > today) {
    Swal.fire({
      icon: "error",
      title: "Invalid Date",
      text: "Future dates are not allowed.",
      background: "#0f1c14",
      color: "#fff",
      confirmButtonColor: "#ef4444",
    });
    return;
  }

  // AJAX
  $.post(
    "../controllers/BodyMetricController.php",
    {
      user_id: userID,
      weight: weight,
      record_date: date,
    },
    function (response) {
      if (response.trim() === "success") {
        Swal.fire({
          icon: "success",
          title: "Saved!",
          text: `Weight: ${weight} kg`,
          background: "#0f1c14",
          color: "#fff",
          confirmButtonColor: "#22c55e",
          showConfirmButton: false,
          timer: 1500,
        });

        setTimeout(() => location.reload(), 1500);
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: "Failed to save data.",
          background: "#0f1c14",
          color: "#fff",
          confirmButtonColor: "#ef4444",
        });
      }
    },
  );
}

function createGoal() {
  var userID = document.getElementById("user_id").value;
  var type = document.getElementById("goal_type_id").value;
  var value = document.getElementById("target_value").value;
  var date = document.getElementById("target_date").value;

  // =========================
  // REQUIRED
  // =========================
  if (!type || !value || !date) {
    Swal.fire({
      icon: "warning",
      title: "Missing Fields",
      text: "Please complete all fields.",
      background: "#0f1c14",
      color: "#fff",
      confirmButtonColor: "#22c55e",
    });
    return;
  }

  // =========================
  // DATE VALIDATION
  // =========================
  var today = new Date().toISOString().split("T")[0];

  if (date < today) {
    Swal.fire({
      icon: "error",
      title: "Invalid Date",
      text: "Goal date must be today or future.",
      background: "#0f1c14",
      color: "#fff",
      confirmButtonColor: "#ef4444",
    });
    return;
  }

  // =========================
  // TYPE-BASED VALIDATION
  // =========================

  // 🔹 Distance Goal
  if (type == "distance") {
    if (isNaN(value) || value <= 0) {
      Swal.fire({
        icon: "error",
        title: "Invalid Distance",
        text: "Enter a valid distance (km).",
        background: "#0f1c14",
        color: "#fff",
        confirmButtonColor: "#ef4444",
      });
      return;
    }
  }

  // 🔹 Weight Goal
  if (type == "weight") {
    if (isNaN(value) || value <= 0) {
      Swal.fire({
        icon: "error",
        title: "Invalid Weight",
        text: "Enter a valid weight (kg).",
        background: "#0f1c14",
        color: "#fff",
        confirmButtonColor: "#ef4444",
      });
      return;
    }
  }

  // 🔹 Target Pace (mm:ss)
  if (type == "pace") {
    if (!value.includes(":")) {
      Swal.fire({
        icon: "error",
        title: "Invalid Pace",
        text: "Format must be mm:ss (e.g. 5:30).",
        background: "#0f1c14",
        color: "#fff",
        confirmButtonColor: "#ef4444",
      });
      return;
    }

    var parts = value.split(":");

    if (parts.length !== 2) {
      Swal.fire({
        icon: "error",
        title: "Invalid Pace",
        text: "Format must be mm:ss.",
        background: "#0f1c14",
        color: "#fff",
        confirmButtonColor: "#ef4444",
      });
      return;
    }

    var minutes = parseInt(parts[0]);
    var seconds = parseInt(parts[1]);

    if (isNaN(minutes) || isNaN(seconds) || seconds >= 60 || minutes <= 0) {
      Swal.fire({
        icon: "error",
        title: "Invalid Pace",
        text: "Enter a valid pace (e.g. 5:30).",
        background: "#0f1c14",
        color: "#fff",
        confirmButtonColor: "#ef4444",
      });
      return;
    }
  }

  // =========================
  // FETCH
  // =========================
  fetch("../controllers/GoalController.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    // body: `user_id=${userID}&goal_type=${type}&target_value=${value}&target_date=${date}`,
    body: `user_id=${userID}&goal_type_id=${type}&target_value=${value}&target_date=${date}`,
  })
    .then((res) => res.json())
    .then((data) => {
      if (data.status === "success") {
        Swal.fire({
          icon: "success",
          title: "Goal Saved!",
          text: "Your goal has been successfully added 🎯",
          background: "#0f1c14",
          color: "#fff",
          confirmButtonColor: "#22c55e",
          showConfirmButton: false,
          timer: 1500,
        });

        setTimeout(() => location.reload(), 1500);
      } else {
        Swal.fire({
          icon: "error",
          title: "Oops...",
          text: data.message || "Failed to save goal.",
          background: "#0f1c14",
          color: "#fff",
          confirmButtonColor: "#ef4444",
        });
      }
    });
}

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

document.addEventListener("DOMContentLoaded", function () {
  var elems = document.querySelectorAll(".timepicker");

  M.Timepicker.init(elems, {
    twelveHour: false,
    autoClose: true,
  });
});

function logoutUser() {
  fetch("../controllers/LogoutController.php", {
    method: "POST",
  })
    .then(() => {
      window.location.href = "LoginPage.php";
    })
    .catch((err) => console.error(err));
}

function updatePlaceholder() {
  var goalTypeSelect = document.getElementById("goal_type_id");
  var targetInput = document.getElementById("target_value");

  var selectedText = goalTypeSelect.options[goalTypeSelect.selectedIndex].text;

  if (selectedText === "Weight Goal") {
    targetInput.type = "number";
    targetInput.placeholder = "e.g. 65 (kg)";
  } else if (selectedText === "Distance Goal") {
    targetInput.type = "number";
    targetInput.placeholder = "e.g. 5 (km)";
  } else if (selectedText === "Target Pace") {
    targetInput.type = "text"; // 🔥 IMPORTANT
    targetInput.placeholder = "e.g. 5:30 (min/km)";
  } else {
    targetInput.type = "number";
    targetInput.placeholder = "Enter target value";
  }
}

const contactInput = document.getElementById("contact_number");

if (contactInput) {
  contactInput.addEventListener("input", function () {
    // allow numbers only
    this.value = this.value.replace(/[^0-9]/g, "");

    // limit to 11 digits
    if (this.value.length > 11) {
      this.value = this.value.slice(0, 11);
    }
  });
}

<?php
session_start();
include("db_connect.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? '');
    $email = trim($_POST["email"] ?? '');
    $password = trim($_POST["password"] ?? '');
    $confirm  = trim($_POST["confirm"] ?? '');

    if ($password !== $confirm) {
        $message = "<div class='alert alert-warning'>Passwords do not match!</div>";
    } else {
        if ($stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ? LIMIT 1")) {
            $stmt->bind_param("ss", $username, $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $message = "<div class='alert alert-warning'>Username or email already taken.</div>";
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                if ($insert = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)")) {
                    $insert->bind_param("sss", $username, $email, $hashed);
                    if ($insert->execute()) {
                        $message = "<div class='alert alert-success'>Registration successful. <a href='login.php'>Login now</a></div>";
                    } else {
                        $message = "<div class='alert alert-danger'>Registration failed. Try again.</div>";
                    }
                    $insert->close();
                } else {
                    $message = "<div class='alert alert-danger'>Database error.</div>";
                }
            }
            $stmt->close();
        } else {
            $message = "<div class='alert alert-danger'>Database error.</div>";
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Register - Samimy Pro</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
  <div class="d-flex align-items-center justify-content-center" style="min-height:100vh;">
    <div class="card p-4" style="max-width:520px; width:100%;">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h4 class="mb-0">Create account</h4>
          <small class="text-muted">Join Samimy Pro</small>
        </div>
        <button id="theme-toggle-btn" class="btn btn-sm btn-outline-secondary"></button>
      </div>
      <?php echo $message; ?>
      <form method="post" class="validate-form" novalidate>
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input required name="username" type="text" class="form-control" placeholder="Choose a username">
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input required name="email" type="email" class="form-control" placeholder="you@example.com">
        </div>
        <div class="mb-3">
          <label class="form-label">Password (min 6 chars)</label>
          <input required name="password" type="password" class="form-control" placeholder="Enter password">
        </div>
        <div class="mb-3">
          <label class="form-label">Confirm password</label>
          <input required name="confirm" type="password" class="form-control" placeholder="Confirm password">
        </div>
        <div class="d-grid">
          <button class="btn btn-success" type="submit">Register</button>
        </div>
      </form>
      <div class="mt-3 text-center">
        <a href="login.php">Already have an account? Login</a>
      </div>
    </div>
  </div>

  <script src="assets/js/validation.js"></script>
  <script src="assets/js/theme.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

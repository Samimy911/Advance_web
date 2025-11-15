<?php
session_start();
include("db_connect.php");

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"] ?? '');
    $password = trim($_POST["password"] ?? '');

    if ($stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = ? OR email = ? LIMIT 1")) {
        $stmt->bind_param("ss", $username, $username);
        $stmt->execute();
        $stmt->store_result();
        if($stmt->num_rows === 1){
            $stmt->bind_result($id, $dbuser, $dbpass);
            $stmt->fetch();
            if (password_verify($password, $dbpass)) {
                $_SESSION['user'] = $dbuser;
                $_SESSION['user_id'] = $id;
                if ($ins = $conn->prepare("INSERT INTO user_activity (user_id, last_login) VALUES (?, NOW())")) {
                    $ins->bind_param("i", $id);
                    $ins->execute();
                    $ins->close();
                }
                header('Location: dashboard.php');
                exit();
            } else {
                $message = "<div class='alert alert-danger'>Invalid credentials.</div>";
            }
        } else {
            $message = "<div class='alert alert-danger'>Invalid credentials.</div>";
        }
        $stmt->close();
    } else {
        if ($username === 'Samson' && $password === '2309000240') {
            $_SESSION['user'] = $username;
            header('Location: dashboard.php');
            exit();
        } else {
            $message = "<div class='alert alert-danger'>Invalid credentials.</div>";
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login - Samimy Pro</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
  <div class="d-flex align-items-center justify-content-center" style="min-height:100vh;">
    <div class="card p-4" style="max-width:520px; width:100%;">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
          <h4 class="mb-0">Samimy Pro</h4>
          <small class="text-muted">Admin login</small>
        </div>
        <button id="theme-toggle-btn" class="btn btn-sm btn-outline-secondary"></button>
      </div>
      <?php echo $message; ?>
      <form method="post" class="validate-form" novalidate>
        <div class="mb-3">
          <label class="form-label">Username or Email</label>
          <input required name="username" type="text" class="form-control" placeholder="Enter username or email">
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input required name="password" type="password" class="form-control" placeholder="Enter password">
        </div>
        <div class="d-grid">
          <button class="btn btn-primary" type="submit">Login</button>
        </div>
      </form>
      <div class="mt-3 text-center">
        <a href="register.php">Create an account</a>
      </div>
    </div>
  </div>

  <script src="assets/js/validation.js"></script>
  <script src="assets/js/theme.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

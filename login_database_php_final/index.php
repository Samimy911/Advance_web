<?php
session_start(); // Start session to track logged-in user

// Dummy credentials (you can later replace with a database)
$valid_username = "Samson";
$valid_password = "2309000240";

// Handle login form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Simple authentication check
    if ($username === $valid_username && $password === $valid_password) {
        $_SESSION["user"] = $username; // store user in session
        header("Location: dashboard.php"); // redirect to dashboard
        exit();
    } else {
        $error = "Invalid username or password!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP Login System</title>
    <style>
        body {
            background: linear-gradient(120deg, #2980b9, #8e44ad);
            font-family: 'Segoe UI', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .login-container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.2);
            width: 350px;
            text-align: center;
        }

        h2 {
            color: #333;
            margin-bottom: 20px;
        }

        input[type="text"], input[type="password"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        button {
            background-color: #2980b9;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            width: 100%;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        button:hover {
            background-color: #1f6391;
        }

        .error {
            color: red;
            margin-top: 15px;
        }

        .footer {
            margin-top: 25px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Login</h2>
        <form method="POST" action="">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>

        <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>

        <div class="footer">Default login: Samson/2309000240</div>
    </div>
</body>
</html>

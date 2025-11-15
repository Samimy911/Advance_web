<?php
session_start();

if (!isset($_SESSION["user"])) {
    header("Location: indexx.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <style>
        body {
            background: linear-gradient(to right, #11998e, #38ef7d);
            font-family: 'Segoe UI', sans-serif;
            color: white;
            text-align: center;
            margin-top: 100px;
        }
        .logout {
            background: #e74c3c;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            transition: background 0.3s ease;
        }
        .logout:hover {
            background: #c0392b;
        }
    </style>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION["user"]); ?> 👋</h1>
    <p>You have successfully logged in using MySQL authentication!</p>
    <a href="logout.php" class="logout">Logout</a>
</body>
</html>

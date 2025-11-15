<?php
session_start();
include('db_connect.php');
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}
$user = $_SESSION['user'];
$user_id = $_SESSION['user_id'] ?? null;

$users_count = 0;
$activity_count = 0;
$last_login = null;
$recent_activity = [];

try {
    if ($stmt = $conn->prepare('SELECT COUNT(*) FROM users')) {
        $stmt->execute();
        $stmt->bind_result($users_count);
        $stmt->fetch();
        $stmt->close();
    }
} catch (Exception $e) { $users_count = 0; }

try {
    if ($stmt = $conn->prepare('SELECT COUNT(*) FROM user_activity')) {
        $stmt->execute();
        $stmt->bind_result($activity_count);
        $stmt->fetch();
        $stmt->close();
    }
} catch (Exception $e) { $activity_count = 0; }

try {
    if ($res = $conn->query("SELECT ua.id, u.username, ua.last_login FROM user_activity ua LEFT JOIN users u ON ua.user_id = u.id ORDER BY ua.last_login DESC LIMIT 8")) {
        while ($row = $res->fetch_assoc()) {
            $recent_activity[] = $row;
        }
    }
} catch (Exception $e) { $recent_activity = []; }

try {
    if ($user_id) {
        if ($stmt = $conn->prepare('SELECT last_login FROM user_activity WHERE user_id = ? ORDER BY last_login DESC LIMIT 1')) {
            $stmt->bind_param('i', $user_id);
            $stmt->execute();
            $stmt->bind_result($last_login);
            $stmt->fetch();
            $stmt->close();
        }
    }
} catch (Exception $e) { $last_login = null; }

?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Admin Dashboard - Samimy Pro</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="assets/css/style.css" rel="stylesheet">
</head>
<body class="dark-mode">
  <div class="sidebar">
    <div class="brand">✨ Samimy Pro</div>
    <div class="menu">
      <a href="dashboard.php" class="active">🏠 Dashboard</a>
      <a href="#" onclick="alert('Profile coming soon')">👤 Profile</a>
      <a href="#" onclick="alert('Settings coming soon')">⚙️ Settings</a>
      <a href="logout.php">🚪 Logout</a>
    </div>
    <div class="footer-note">Samimy Pro • Admin panel</div>
  </div>

  <div class="main">
    <div class="topbar">
      <div>
        <h4>Welcome, <?php echo htmlspecialchars($user); ?></h4>
        <div class="text-muted small">Overview of recent activity</div>
      </div>
      <div class="actions">
        <div class="me-2 text-muted small">
          Last login: <?php echo htmlspecialchars($last_login ?? 'N/A'); ?>
        </div>
        <button id="theme-toggle-btn" class="btn btn-outline-secondary"></button>
      </div>
    </div>

    <div class="stats">
      <div class="card stat">
        <h6 class="text-muted">Users</h6>
        <h2><?php echo intval($users_count); ?></h2>
        <p class="footer-note">Total registered users</p>
      </div>
      <div class="card stat">
        <h6 class="text-muted">Logins</h6>
        <h2><?php echo intval($activity_count); ?></h2>
        <p class="footer-note">Total login events</p>
      </div>
      <div class="card stat">
        <h6 class="text-muted">Quick Actions</h6>
        <div class="mt-2">
          <a class="btn btn-primary btn-sm" href="register.php">Add user</a>
          <a class="btn btn-outline-primary btn-sm" href="#" onclick="alert('Feature coming')">Add product</a>
        </div>
        <p class="footer-note mt-2">Use prepared statements and hashed passwords.</p>
      </div>
    </div>

    <div class="row mt-4">
      <div class="col-md-8">
        <div class="card">
          <h6>Recent activity</h6>
          <?php if (count($recent_activity) === 0): ?>
            <p class="text-muted">No activity yet.</p>
          <?php else: ?>
            <div class="list-group">
              <?php foreach($recent_activity as $a): ?>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                  <div>
                    <strong><?php echo htmlspecialchars($a['username'] ?? 'Unknown'); ?></strong>
                    <div class="text-muted small"><?php echo htmlspecialchars($a['last_login']); ?></div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card">
          <h6>Profile</h6>
          <div class="profile">
            <div style="width:52px;height:52px;border-radius:10px;background:var(--card-contrast);display:flex;align-items:center;justify-content:center;">
              <span style="font-weight:700;"><?php echo strtoupper(substr($user,0,1)); ?></span>
            </div>
            <div>
              <div><strong><?php echo htmlspecialchars($user); ?></strong></div>
              <div class="text-muted small">Last login: <?php echo htmlspecialchars($last_login ?? 'N/A'); ?></div>
            </div>
          </div>
          <div class="footer-note">Member since: <?php
            try {
              if ($stmt = $conn->prepare('SELECT created_at FROM users WHERE username = ? LIMIT 1')) {
                $stmt->bind_param('s', $user);
                $stmt->execute();
                $stmt->bind_result($created);
                $stmt->fetch();
                $stmt->close();
                echo htmlspecialchars($created ?? 'N/A');
              }
            } catch (Exception $e) { echo 'N/A'; }
          ?></div>
        </div>
      </div>
    </div>
  </div>

  <script src="assets/js/validation.js"></script>
  <script src="assets/js/theme.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

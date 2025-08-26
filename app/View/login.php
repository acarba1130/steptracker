<?php

if (isset($_COOKIE['logout_reason'])) {
  $error = htmlspecialchars($_COOKIE['logout_reason']);
  setcookie("logout_reason", "", time() - 3600, "/");
} else {
  $error = $_SESSION['error'] ?? '';
  unset($_SESSION['error']);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Step Tracker - Login</title>
  <link rel="stylesheet" href="/../assets/css/style.css" />
</head>
<body>
  <div class="container">
    <form action="index.php?page=login-submit" method="POST" class="form-box">
      <h2 class="form-title">Step Tracker Login</h2>

      <?php if (!empty($error)): ?>
        <div class="form-error"><?php echo htmlspecialchars($error); ?></div>
      <?php endif; ?>

      <label for="username">Username</label>
      <input type="text" id="username" name="username" required />

      <label for="password">Password</label>
      <input type="password" id="password" name="password" required />

      <button type="submit" class="btn">Login</button>
    </form>
  </div>
</body>
</html>

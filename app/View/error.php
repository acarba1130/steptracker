<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Error</title>
  <link rel="stylesheet" href="/../assets/css/style.css" />
</head>
<body>
  <div class="container">
    <div class="form-box">
      <h2 class="form-title">Oops! Something went wrong.</h2>

      <?php if (!empty($errorMessage)): ?>
        <div class="form-error">
          <?php echo htmlspecialchars($errorMessage); ?>
        </div>
      <?php else: ?>
        <div class="form-error">
          An unknown error occurred.
        </div>
      <?php endif; ?>

      <div style="text-align: center; margin-top: 20px; text-decoration: none;">
        <a href="/../../index.php" class="btn" style="text-align: center; margin-top: 20px; text-decoration: none;">Go to Home</a>
      </div>
    </div>
  </div>
</body>
</html>

<?php
    $error = $_SESSION['error'] ?? '';
    unset($_SESSION['error']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Log Step</title>
  <link rel="stylesheet" href="/../assets/css/style.css" />
  <style>
    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #4CAF50;
      color: white;
      padding: 15px 20px;
      border-radius: 8px;
      margin-bottom: 20px;
    }

    .header a {
      color: white;
      text-decoration: none;
      font-weight: bold;
      margin-left: 15px;
    }

    .container {
      max-width: 600px;
      margin: 0 auto;
    }

    .form-box {
      background: #fff;
      padding: 30px;
      border-radius: 8px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    }

    .form-box h3 {
      text-align: center;
      color: #333;
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-bottom: 5px;
      color: #333;
      font-weight: bold;
    }

    input[type="text"] {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
      border: 1px solid #ccc;
      border-radius: 5px;
      font-size: 1rem;
    }

    .btn {
      width: 100%;
      padding: 12px;
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 6px;
      font-size: 1rem;
      cursor: pointer;
      transition: background 0.3s ease;
    }

    .btn:hover {
      background-color: #0056b3;
    }

    .form-error {
      background-color: #ffe6e6;
      color: #cc0000;
      padding: 10px;
      border-radius: 5px;
      margin-bottom: 15px;
      font-size: 0.95rem;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="container">

    <div class="form-box">
      <h3>
        Log Step for <?= htmlspecialchars(date('m/d/Y', strtotime($date))) ?>
      </h3>

      <?php if (!empty($error)): ?>
        <div class="form-error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>

      <form action="index.php?page=log-step-submit" method="post">
        <label for="step">Step Count</label>
        <input type="text" name="stepCount" id="step" required />

        <button type="submit" class="btn">Log</button>

        <div style="text-align: center; margin-top: 15px;">
          <a href="index.php?page=log-steps" class="btn" style="background-color: #6c757d; margin-top: 10px; display: inline-block; width: auto; padding: 12px 20px; text-align: center; text-decoration: none;">
            Cancel
          </a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>

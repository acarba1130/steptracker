<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Step Tracker - Log Steps</title>
  <link rel="stylesheet" href="/../assets/css/style.css" />
  <style>
    /* Responsive container */
    .container {
      margin: 0 auto;
      padding: 0 15px;
      max-width: 95%;
    }
    @media (min-width: 600px) {
      .container {
        max-width: 600px;
      }
    }
    @media (min-width: 900px) {
      .container {
        max-width: 900px;
      }
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #4CAF50;
      color: white !important;  /* force white text */
      padding: 15px 20px;
      border-radius: 8px;
      margin-top: 20px;
      margin-bottom: 20px;
    }
    .header a {
      color: white !important;
      text-decoration: none;
      font-weight: bold;
      margin-left: 15px;
    }

    h2, h3 {
      color: #333;
    }

    .step-entry {
      background: #f9f9f9;
      border: 1px solid #ddd;
      padding: 15px;
      margin-bottom: 15px;
      border-radius: 6px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      flex-wrap: wrap;
    }
    .step-info {
      font-size: 1.1rem;
      color: #444;
      flex: 1 1 auto;
      min-width: 200px;
    }
    .btn {
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 8px 14px;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
      font-size: 0.9rem;
      margin-left: 10px;
      white-space: nowrap;
    }
    .btn:hover {
      background-color: #45a049;
    }
    form.inline {
      margin: 0;
      display: inline;
    }
    form.inline input[type="number"] {
      padding: 6px 8px;
      width: 100px;
      font-size: 1rem;
      margin-right: 10px;
      border-radius: 4px;
      border: 1px solid #ccc;
      vertical-align: middle;
    }
    .error {
      color: red;
      margin-bottom: 10px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h2 style="color:white;">Step Tracker</h2>
      <div>
        <a href="index.php?page=home">Home</a>
        <a href="index.php?page=account">Account</a>
        <a href="index.php?page=logout">Logout</a>
      </div>
    </div>

    <h3 style="margin-bottom:10px;">Log Your Steps</h3>

    <?php if (!empty($errorMessage)): ?>
      <div class="error"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <?php foreach ($datesToShow as $date):
      $canLog = $date <= date('Y-m-d'); // only today or past dates
      $logged = isset($loggedSteps[$date]);
      $stepsLogged = $logged ? $loggedSteps[$date] : 0;
      $formattedDate = date('m/d/Y', strtotime($date));
    ?>
      <div class="step-entry" id="entry-<?= htmlspecialchars($date) ?>">
        <div class="step-info">
          <strong><?= $formattedDate ?></strong><br />
          <?php if ($logged): ?>
            Steps logged: <?= number_format($stepsLogged) ?>
          <?php else: ?>
            No steps logged yet
          <?php endif; ?>
        </div>

        <?php if ($logged): ?>
          <form action="index.php?page=edit-step" method="post" class="inline">
            <input type="hidden" name="date" value="<?= htmlspecialchars($date) ?>" />
            <button type="submit" class="btn">Edit</button>
          </form>
        <?php elseif ($canLog): ?>
          <form action="index.php?page=log-step" method="post" class="inline">
            <input type="hidden" name="date" value="<?= htmlspecialchars($date) ?>" />
            <button type="submit" class="btn">Log Steps</button>
          </form>
        <?php else: ?>
          <em style="margin-left:10px;">You cannot log future steps.</em>
        <?php endif; ?>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>

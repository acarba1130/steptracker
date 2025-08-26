<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Step Tracker - Home</title>
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

    .leaderboard {
      margin-bottom: 30px;
    }

    .leaderboard h3 {
      margin-bottom: 10px;
      color: #333;
    }

    .leaderboard ul {
      list-style: none;
      padding: 0;
    }

    .leaderboard li {
      background: #f0f0f0;
      padding: 10px;
      margin-bottom: 5px;
      border-radius: 5px;
    }

    .log-steps-btn {
      display: inline-block;
      padding: 12px 20px;
      background-color: #4CAF50;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-size: 1rem;
      font-weight: bold;
      text-align: center;
    }

    .log-steps-btn:hover {
      background-color: #45a049;
    }

    .container {
      max-width: 600px;
      margin: 0 auto;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <h2>Step Tracker</h2>
      <div>
        <a href="index.php?page=home">Home</a>
        <a href="index.php?page=account">Account</a>
        <a href="index.php?page=logout">Logout</a>
      </div>
    </div>

    <div class="leaderboard">
      <h3>🏆 Team Leaderboard</h3>
      <ul>
        <?php foreach ($teamStepCount as $singleTeamCount): ?>
        <li><?= htmlspecialchars($singleTeamCount['teamname']) ?> – <?= number_format($singleTeamCount['stepcount']) ?> steps</li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div class="leaderboard">
      <h3>👟 Individual Leaderboard</h3>
      <ul>
        <?php foreach ($userStepCount as $singleUserCount): ?>
        <li><?= htmlspecialchars($singleUserCount['name']) ?> – <?= number_format($singleUserCount['stepcount']) ?> steps</li>
        <?php endforeach; ?>
      </ul>
    </div>

    <div style="text-align: center; margin-top: 30px;">
      <a href="index.php?page=log-steps" class="log-steps-btn">+ Log Steps</a>
    </div>
  </div>
</body>
</html>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Step Tracker - Account</title>
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

    .account-info {
      background: #fff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.05);
    }

    .account-info h3 {
      margin-bottom: 20px;
      color: #333;
      text-align: center;
    }

    .info-row {
      margin-bottom: 15px;
    }

    .info-row label {
      font-weight: bold;
      color: #333;
    }

    .info-row span {
      margin-left: 10px;
      color: #555;
    }

    .edit-buttons {
      display: flex;
      justify-content: space-between;
      margin-top: 20px;
    }

    .edit-buttons a {
      padding: 10px 16px;
      background-color: #007bff;
      color: white;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
    }

    .edit-buttons a:hover {
      background-color: #0056b3;
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

    <div class="account-info">
      <h3>👤 My Account</h3>

      <div class="info-row">
        <label>Team Name:</label>
        <span><?= htmlspecialchars($teamName) ?></span>
      </div>

      <div class="info-row">
        <label>Username:</label>
        <span><?= htmlspecialchars($username) ?></span>
      </div>

      <div class="info-row">
        <label>Nickname:</label>
        <span><?= htmlspecialchars($nickname) ?></span>
      </div>

      <div class="edit-buttons">
        <a href="index.php?page=edit-nickname">Edit Nickname</a>
        <a href="index.php?page=edit-password">Edit Password</a>
      </div>
    </div>
  </div>
</body>
</html>

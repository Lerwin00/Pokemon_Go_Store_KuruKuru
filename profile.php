<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("location: login.php");
  exit;
}

$servername = "localhost";
$username = "root";
$db_password = "";
$dbname = "storedb";

$conn = new mysqli($servername, $username, $db_password, $dbname);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("SELECT uname, email, playerID FROM accountstbl WHERE uname = ?");
$stmt->bind_param("s", $_SESSION["uname"]);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();
$stmt->close();
$conn->close();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $current_password = $_POST["password"];
  $new_password = $_POST["new_password"];
  $confirm_password = $_POST["confirm_password"];

  if ($current_password === $row["password"]) {
    if ($new_password === $confirm_password) {
      $conn = new mysqli($servername, $username, $db_password, $dbname);
      if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
      }
      $stmt = $conn->prepare("UPDATE accountstbl SET password = ? WHERE uname = ?");
      $stmt->bind_param("ss", $new_password, $_SESSION["uname"]);
      $stmt->execute();
      $stmt->close();
      $conn->close();

      session_destroy();

      header("location: login.php");
      exit;
    } else {
      $password_error = "New password and confirm password do not match.";
    }
  } else {
    $password_error = "Incorrect current password.";
  }
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Profile Page</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 20px;
      background-color: #f3f3f3;
    }

    .container {
      width: 100%;
      max-width: 500px;
      margin: 0 auto;
      border-radius: 5px;
      background-color: #ffffff;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
      padding: 20px;
    }

    h1 {
      text-align: center;
      color: #333333;
      margin-bottom: 20px;
    }

    label {
      display: block;
      margin-top: 10px;
      color: #555555;
    }

    .info-container {
      margin-top: 20px;
      border-top: 1px solid #cccccc;
      padding-top: 10px;
    }

    p {
      margin: 8px 0;
    }

    strong {
      font-weight: bold;
    }

    .password-container {
      margin-top: 20px;
      border-top: 1px solid #cccccc;
      padding-top: 10px;
    }

    .password-error {
      color: #dc3545;
      margin-top: 10px;
    }

    .delete-button {
      margin-top: 20px;
      text-align: center;
    }

    .delete-button button {
      padding: 8px 16px;
      background-color: #dc3545;
      color: #fff;
      border: none;
      border-radius: 4px;
      font-weight: bold;
      cursor: pointer;
    }

    .delete-button button:hover {
      background-color: #c82333;
    }

    .text-center {
      text-align: center;
    }

    .btn {
      padding: 10px 20px;
      font-weight: bold;
      cursor: pointer;
    }

    .btn-primary {
      background-color: #007bff;
      color: #fff;
      border: none;
      border-radius: 4px;
    }

    .btn-primary:hover {
      background-color: #0056b3;
    }

    .btn-danger {
      background-color: #dc3545;
      color: #fff;
      border: none;
      border-radius: 4px;
    }

    .btn-danger:hover {
      background-color: #c82333;
    }

    footer {
      text-align: center;
      margin-top: 20px;
    }

    @media (max-width: 600px) {
      .container {
        padding: 10px;
      }
    }
  </style>
</head>

<body>
  <?php include_once "navbar.php" ?>
  <div class="container">
    <h1>Welcome,
      <?php echo $row["uname"]; ?>!
    </h1>
    <div class="info-container">
      <p><strong>UserName:</strong>
        <?php echo $row["uname"]; ?>
      </p>
      <p><strong>Email:</strong>
        <?php echo $row["email"]; ?>
      </p>
      <p><strong>Player ID:</strong>
        <?php echo $row["playerID"]; ?>
      </p>
    </div>
    <div class="password-container">
      <h3>Change Password</h3>
      <?php if (isset($password_error)) { ?>
        <p class="password-error">
          <?php echo $password_error; ?>
        </p>
      <?php } ?>
      <form method="POST" action="">
        <div class="form-group">
          <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Current Password"required>
        </div>
        <br>
        <div class="form-group">
          <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Passowrd"required>
        </div>
        <br>
        <div class="form-group">
          <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm New Passowrd"required>
        </div>
        <br>
        <div class="text-center">
          <button type="submit" class="btn btn-primary">Change Password</button>
        </div>
      </form>
    </div>
    <div class="delete-button">
      <form method="POST" action="delete.php">
        <button type="submit" class="btn btn-danger">Delete Account</button>
      </form>

    </div>
  </div>
  <?php include_once "footer.php" ?>

</body>

</html>
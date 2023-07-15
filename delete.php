<?php
session_start();

if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
  header("location: login.php");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $uname = $_SESSION["uname"];

  $servername = "localhost";
  $username = "root";
  $db_password = "";
  $dbname = "storedb";

  $conn = new mysqli($servername, $username, $db_password, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  // Delete account record from the database
  $stmt = $conn->prepare("DELETE FROM accountstbl WHERE uname = ?");
  $stmt->bind_param("s", $uname);
  $stmt->execute();
  $stmt->close();

  // Destroy session and redirect to the login page
  session_destroy();
  header("location: login.php");
  exit;
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Delete Account</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      background-color: #f3f3f3;
    }
    .container {
      width: 400px;
      margin: 0 auto;
      padding: 20px;
      border-radius: 25px;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
      background-color: #ffffff;
    }
    h1 {
      text-align: center;
      color: #333333;
      margin-bottom: 20px;
    }
    .text-center {
      text-align: center;
    }
    .delete-button {
      padding: 10px 20px;
      font-size: 18px;
      background-color: #dc3545;
      color: #ffffff;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    .delete-button:hover {
      background-color: #c82333;
    }
  </style>
</head>
<body>
  <div class="container">
  <div class="text-center">
    <h1>Delete Account</h1>
    <p>Are you sure you want to delete your account?</p>

      <form method="POST" action="">
        <button type="submit" class="delete-button">Delete Account</button>
      </form>
    </div>
  </div>
</body>
</html>

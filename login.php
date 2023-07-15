<?php
session_start();

// Check if the user selected the "Remember Me" option
if (isset($_COOKIE['remember_me']) && $_COOKIE['remember_me'] === '1') {
  if (isset($_COOKIE['uname'])) {
    $uname = $_COOKIE['uname'];
  }
  if (isset($_COOKIE['password'])) {
    $password = $_COOKIE['password'];
  }
}

if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
  header("location: profile.php");
  exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $uname = $_POST["uname"];
  $password = $_POST["password"];
  $remember_me = isset($_POST["remember_me"]) ? $_POST["remember_me"] : "";

  $servername = "localhost";
  $username = "root";
  $db_password = "";
  $dbname = "storedb";

  $conn = new mysqli($servername, $username, $db_password, $dbname);

  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }

  $stmt = $conn->prepare("SELECT uname, password FROM accountstbl WHERE uname = ?");
  $stmt->bind_param("s", $uname);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();
    $stored_password = $row["password"];

    if ($password === $stored_password) {
      // Set session variables
      $_SESSION["loggedin"] = true;
      $_SESSION["uname"] = $uname;

      // Set cookies if "Remember Me" is selected
      if ($remember_me === "1") {
        setcookie('uname', $uname, time() + (30 * 24 * 60 * 60)); // Expires in 30 days
        setcookie('password', $password, time() + (30 * 24 * 60 * 60));
        setcookie('remember_me', '1', time() + (30 * 24 * 60 * 60));
      } else {
        // Clear any previously set cookies
        setcookie('uname', '', time() - 3600);
        setcookie('password', '', time() - 3600);
        setcookie('remember_me', '', time() - 3600);
      }

      // Redirect to the profile page
      header("location: store.php");
      exit;
    } else {
      $login_error = "Invalid username or password.";
    }
  } else {
    $login_error = "Invalid username or password.";
  }

  $stmt->close();
  $conn->close();
}
?>

<!DOCTYPE html>
<html>

<head>
  <title>Pokemon Go! Login Page</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      background-image: url("Login.png");
      background-repeat: no-repeat;
      background-size: 100% 100%;
      background-position: center top;
      display: flex;
      justify-content: center;
      height: 100vh;
      margin: 0;
    }

    footer {
      position: absolute;
      left: 0;
      bottom: 0;
      height: 70px;
      text-align: center;
    }

    .container {
      width: 400px;
      padding: 40px;
      margin-top: 100px;
      margin-left: auto;
      margin-right: auto;
    }

    h2 {
      font-size: 20px;
      font-weight: bold;
      margin-bottom: 20px;
      color: #000000;
      text-align: center;
    }

    label {
      display: block;
      margin-bottom: 10px;
      color: #1c1e21;
      font-weight: 600;
    }

    input[type="text"],
    input[type="password"] {
      width: 100%;
      padding: 10px;
      border: 1px solid #dddfe2;
      border-radius: 4px;
      margin-bottom: 20px;
    }

    input[type="submit"] {
      width: 30%;
      margin-left: 35%;
      padding: 5px;
      border: none;
      background-color: #1877f2;
      color: #fff;
      font-weight: 600;
      cursor: pointer;
    }

    input[type="submit"]:hover {
      background-color: #1365e6;
    }

    .error {
      color: red;
      margin-bottom: 10px;
    }

    .small-label {
      font-size: 14px;
    }

    .checkbox-container {
      display: flex;
      align-items: center;
    }

    .checkbox-container input[type="checkbox"] {
      margin-right: 5px;
    }

    @media (max-width: 768px) {
      body {
        background-size: 60% 30%;
      }

      .container {
        width: 80%;
        margin-top: 150px;
      }

      input[type="submit"] {
        width: 100%;
        margin-left: 0;
      }
    }

    @media (max-width: 576px) {
      .container {
        width: 90%;
        margin-top: 150px;
      }
    }

  </style>
</head>

<body>

  <div class="container">
    <h2>POKEMON TRAINER LOGIN</h2>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <div class="form-group">
        <input type="text" id="uname" name="uname" class="form-control" placeholder="Username" required
          value="<?php echo isset($uname) ? htmlspecialchars($uname) : ''; ?>">
      </div>
      <div class="form-group">
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required
          value="<?php echo isset($password) ? htmlspecialchars($password) : ''; ?>">
      </div>

      <?php if (isset($login_error)): ?>
      <div class="error">Invalid username or password. Please try again.</div>
      <?php endif; ?>

      <div class="form-group">
        <div class="checkbox-container">
          <input type="checkbox" id="remember_me" name="remember_me" value="1"
            <?php echo isset($remember_me) && $remember_me === "1" ? "checked" : ""; ?>>
          <label for="remember_me" class="small-label">Remember Me</label>
        </div>
        <input type="submit" value="Log In" class="btn btn-primary">
      </div>
      <p>No Trainer License yet? <a href="register.php">Register now</a></p>
    </form>
  </div>

  <?php include_once "footer.php" ?>
</body>

</html>

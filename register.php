<?php
// Database configuration
$dbHost = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "storedb";

// Create database connection
$conn = mysqli_connect($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Function to generate a random in-game player ID
function generatePlayerID()
{
    $playerID = '';
    for ($i = 0; $i < 12; $i++) {
        $playerID .= mt_rand(0, 9);
        if (($i + 1) % 4 === 0 && $i !== 11) {
            $playerID .= ' ';
        }
    }
    return $playerID;
}

// Function to validate email address
function validateEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to save user registration data
function saveRegistrationData($uname ,$email, $password, $playerID)
{
    global $conn;
    $sql = "INSERT INTO accountstbl (uname, email, password, playerID) VALUES ('$uname','$email', '$password', '$playerID')";
    if (mysqli_query($conn, $sql)) {
        return true;
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        return false;
    }
}

// Process registration form submission
$registrationSuccess = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uname = $_POST["uname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $confirmPassword = $_POST["confirm_password"];

    // Validate email
    if (!validateEmail($email)) {
    }
    // Check if password and confirm password match
    elseif ($password !== $confirmPassword) {
    } else {
        // Generate player ID
        $playerID = generatePlayerID();
        // Save registration data
        $registrationSuccess = saveRegistrationData($uname, $email, $password, $playerID);
    }
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Pokemon Go! Registration Page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url("Login.png");
            background-repeat: no-repeat;
            background-size: cover;
            background-position: center;
            display: flex;
            justify-content: flex-start;
            height: 100vh;
            margin: 0;
        }

        .container {
            max-width: 400px;
            padding: 10px;
            margin: 0 auto;
            margin-top: 100px;
        }

        footer {
            position: fixed;
            left: 0;
            bottom: 0;
            width: 100%;
            text-align: center;
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
            width: 100%;
            padding: 10px;
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

        .success {
            color: green;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>POKEMON TRAINER REGISTRATION FORM</h2>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <div class="form-group">
                <label for="uname">Username</label>
                <input type="text" id="uname" name="uname" class="form-control" required placeholder="Username">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" id="email" name="email" class="form-control" required placeholder="Email">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" class="form-control" required placeholder="Password">
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required
                    placeholder="Confirm Password">
            </div>
            <input type="submit" value="Register" class="btn btn-primary">

            <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && $password !== $confirmPassword) : ?>
            <div class="error">Passwords do not match!</div>
            <?php endif; ?>

            <?php if ($_SERVER["REQUEST_METHOD"] == "POST" && !$registrationSuccess) : ?>
            <div class="error">Registration failed. Please try again.</div>
            <?php endif; ?>
        </form>

        <?php if ($registrationSuccess) : ?>
        <div class="success">Registration successful!</div>
        <?php endif; ?>

        <p>Already have a license? <a href="login.php">Login here</a></p>
    </div>
</body>

</html>


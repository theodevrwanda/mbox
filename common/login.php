<?php
session_start();
if (isset($_SESSION['username']) || isset($_COOKIE['username'])){
      header('Location:../admin/dashboard.php');
      exit();
}

if (isset($_SESSION['user_id']) || isset($_COOKIE['user_id'])){
    header('Location:../index.php');
    exit();
}

// Database connection
require('../dbconf.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$message = ""; // Variable to store error or success messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login'])) {
        $username_input = $_POST['username'];
        $password_input = $_POST['password'];
    }

    $admin = "mbox";
    $adminpassword = "theo";
    if ($username_input == $admin && $password_input == $adminpassword) {
        $_SESSION['username'] = $admin;
        setcookie("username", $admin, time() + (86400 * 100), "/"); 
        header('Location:../admin/dashboard.php');
        exit();
    }

    // Check if both fields are filled
    if (!empty($username_input) && !empty($password_input)) {
        // Check if the input is email or username
        $checkQuery = "SELECT * FROM users WHERE email = ? OR name = ?";
        $stmt = $conn->prepare($checkQuery);
        $stmt->bind_param("ss", $username_input, $username_input); // Check by email or username
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify the password
            if (password_verify($password_input, $user['password'])) {
                // Password matches, start session and set session variables
               

                // Check if 'Remember me' checkbox is selected
                if (isset($_POST['remember'])) 
                {
                    setcookie("user_id", $user['id'], time() + (86400 * 100), "/"); // Store user_id in cookie for 100 days
                    $_SESSION['user_id'] = $_COOKIE['user_id'];
                }
                else
                {
                    $_SESSION['user_id'] = $user['id']; // Store user_id in session
                }

                header("Location:../index.php"); // Redirect to the home page
                exit();
            } else {
                $message = "Incorrect password!";
            }
        } else {
            $message = "User not found!";
        }

        $stmt->close();
    } else {
        $message = "Please enter username or email and password!";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mbox | Sign In</title>
    <link rel="stylesheet" href="../issets/styles/signin.css">
    <link rel="shortcut icon" href="../issets/img/mbox.svg" type="image/x-icon">
</head>
<body>
    <div class="background-overlay">

        <div class="login-container">
            <!-- logo here  -->
            <div class="logo">
             <h1>Mbox</h1>
            </div>

            <form class="login-form" method="post">
                <h2>Sign In</h2>

                <!-- Display error message if any -->
                <?php if (!empty($message)): ?>
                    <div class="error-message">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>

                <!-- User input fields -->
                <input type="text" placeholder="Email or User Name" name="username" required>
                <input type="password" placeholder="Password" name="password" required>

                <!-- Submit button -->
                <button type="submit" class="signin-btn" name="login">Sign In</button>

                <!-- Other options -->
                <div class="or-divider">
                    <span></span>OR<span></span>
                </div>
                <a href="create.php"><button type="button" class="signin-code-btn">Create Account</button></a>
                <a href="#" class="forgot-password">Forgot password?</a>

                <!-- Remember me checkbox -->
                <div class="remember-me">
                    <input type="checkbox" id="remember" name="remember" value="1">
                    <label for="remember">Remember me</label>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
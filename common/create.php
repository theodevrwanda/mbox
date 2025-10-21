<?php
// Start a session
session_start();
if (isset($_SESSION['username']) || isset($_COOKIE['username'])){
    header('Location:../admin/dashboard.php');
    exit();
}

if (isset($_SESSION['user_id']) || isset($_COOKIE['user_id'])){
  header('Location:../index.php');
  exit();
}
require('../dbconf.php');

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {
    // Get form data
    $user = $_POST['username'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $cpass = $_POST['cpassword'];

    // Check if password and confirm password match
    if ($pass !== $cpass) {
        $message = "Passwords do not match!";
    } else {
        // Check if email is already registered
        $checkEmailQuery = "SELECT * FROM users WHERE email = ?";
        $stmt = $conn->prepare($checkEmailQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message = "This email is already registered!";
        } else {
            // Hash the password
            $hashedPassword = password_hash($pass, PASSWORD_DEFAULT);

            // Insert plain text password into `myuser` table
            $insertPlainTextQuery = "INSERT INTO myusers (name, email, password) VALUES (?, ?, ?)";
            $stmtPlainText = $conn->prepare($insertPlainTextQuery);
            $stmtPlainText->bind_param("sss", $user, $email, $pass);

            // Insert hashed password into `users` table
            $insertQuery = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("sss", $user, $email, $hashedPassword);

            // Execute both queries
            if ($stmt->execute() && $stmtPlainText->execute()) {
                // Start session and set session variables
                $closepage = "SELECT * FROM myusers where email = '$email'";
                $closer = $conn->query($closepage);
                $closeid = $closer->fetch_assoc();
                setcookie("user_id", $closeid['id'], time() + (86400 * 100), "/"); 
                $_SESSION['user_id'] = $closeid['id'];
                // Redirect to avoid form resubmission
                header("Location:../index.php");
                exit();
            } else {
                $message = "Error: " . $stmt->error . " / " . $stmtPlainText->error;
            }

            // Close the prepared statements
            $stmtPlainText->close();
            $stmt->close();
        }
    }
}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mbox | Create Account</title>
    <link rel="shortcut icon" href="../issets/img/mbox.svg" type="image/x-icon">
    <link rel="stylesheet" href="../issets/styles/signin.css">
    <style>
        .error-message {
            color: white;
            font-size: 14px;
            margin:10px 0  15px 0;
        }
    </style>
</head>
<body>
    <div class="background-overlay">

        <div class="login-container">
            <!-- logo here  -->
            <div class="logo">
             <h1>Mbox</h1>
            </div>

            <!-- Sign Up Form -->
            <form class="login-form" method="post">
                <h2>Sign Up</h2>

                <!-- Display error or success message -->
    
                   <div class="error-message">
                    <?php if(!empty($message)){echo $message;}?>
                    </div>
            

                <!-- User input fields -->
                <input type="text" placeholder="Create Username" name="username"required >
                <input type="email" placeholder="Enter Email" name="email" required >
                <input type="password" placeholder="Create Password" name="password" required>
                <input type="password" placeholder="Confirm Password" name="cpassword" required >

                <!-- Submit button -->
                <button type="submit" class="signin-btn" name="register">Sign Up</button>

                <!-- Other options -->
                <div class="or-divider">
                    <span></span>OR<span></span>
                </div>
                <a href="login.php"><button type="button" class="signin-code-btn">Sign In</button></a>
            </form>
        </div>
    </div>
</body>
</html>

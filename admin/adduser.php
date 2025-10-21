<?php
// Start a session

require('../dbconf.php');
// Check connection
session_start();
if (isset($_SESSION['username'])|| isset($_COOKIE['username'])){
    $username = $_SESSION['username'];
}
else{
    header('Location:../common/home.php');
    exit();
}
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_user'])) {
    // Get form data
    $user = $_POST['user_name'];
    $email = $_POST['email'];
    $pass = $_POST['password'];
    $cpass = $_POST['cpassword'];

    // Basic validation
    if (empty($user) || empty($email)) {
        $message = 'Please fill in both Username and Email';
   
    }

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
            // Insert plain text password into `myusers` table
            $insertPlainTextQuery = "INSERT INTO myusers (name, email, password) VALUES (?, ?, ?)";
            $stmtPlainText = $conn->prepare($insertPlainTextQuery);
            $stmtPlainText->bind_param("sss", $user, $email, $pass);

            // Insert hashed password into `users` table
            $insertQuery = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($insertQuery);
            $stmt->bind_param("sss", $user, $email, $hashedPassword);

            // Execute both queries
            if ($stmt->execute() && $stmtPlainText->execute()) {
                $message = 'User added successfully.';
              
            } else {
                $message = "Error: " . $stmt->error . " / " . $stmtPlainText->error;
                exit($message);
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
    <title>Mbox | Add User</title>
    <link rel="shortcut icon" href="../issets/img/mbox.svg" type="image/x-icon">
    <link rel="stylesheet" href="../issets/styles/movieupdate.css">
</head>
<body>
    <div class="container">
        <!-- Section 1: Movie Image Display (30%) -->
        <div class="movie-image" style="
        background:linear-gradient(rgba(0, 0, 0, 0.39),black),
        url(https://media.gettyimages.com/id/90786576/photo/montage-of-a-group-of-people-smiling.jpg?s=612x612&w=0&k=20&c=9cDdA4we1B2gVs5YzxByiEzZsi2-LiI8vbh7F5-6Y9M=) no-repeat;
        ">
        </div>

        <!-- Section 2: Movie Add Form (70%) -->
        <div class="movie-form">
            <h2>Add User</h2>
            <form action="" method="POST">
            <div class="dis_mess">
                    <strong>
                    <?php
           if(!empty($message)){echo $message;}
                    ?>
                    </strong>
                </div> 
                <label for="user_name">Name:</label>
                <input type="text" name="user_name" placeholder="Create username" required>
        
                <label for="email">Email:</label>
                <input type="email" name="email" placeholder="Enter Email" required>
            
                <label for="password">Create password:</label>
                <input type="password" name="password" placeholder="Create password" required>

                <label for="cpassword">Confirm password:</label>
                <input type="password" name="cpassword" placeholder="Confirm password" required>

                <button type="submit" name="add_user">Add Now</button>
            </form>
        </div>
    </div>
</body>
</html>

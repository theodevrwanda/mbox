<?php
require('../dbconf.php');
session_start();
if (isset($_SESSION['username'])|| isset($_COOKIE['username'])){
    $username = $_SESSION['username'];
}
else{
    header('Location:../common/home.php');
    exit();
}
$key = $_GET['id'];
if(empty($key)) {
    header('Location:usercontroller.php');
    exit();
}
// display previews user data 
$userquery = "SELECT * FROM myusers WHERE id = $key";
$userR = $conn->query($userquery);
$display = $userR->fetch_assoc();
$message = "";
// update user data  first get data from html form 
if (isset($_POST['updater'])) {
  $user_name = $_POST['user_name'];
  $email = $_POST['email'];
  $pass = $_POST['password'];  

  // handle error if any variable is empty
$name = (!empty($user_name))? $user_name : $display['name'];
$eml = (!empty($email))? $email : $display['email'];
$pass_store = (!empty($pass))? $pass : $display['password']; 
// check if the are data change ....
if ($name == $display['name'] && $eml == $display['email'] && $pass_store == $display['password']) {
 $message = 'no any data be changed !!';
}
else{
// update data in myusers table
$updatef = "UPDATE `myusers` SET `name`='$name',`email`='$eml',`password`='$pass_store' WHERE id = $key";
$executef = $conn->query($updatef);
// update data in users table
$hashedPassword = password_hash($pass, PASSWORD_DEFAULT);
$updatef = "UPDATE `users` SET `name`='$name',`email`='$eml',`password`='$hashedPassword' WHERE id = $key";
$executef = $conn->query($updatef);
// chech update is saccessufully....
if ($executef == true) {
    $message = ' data update saccessufully..';
}
} 
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mbox | User Update</title>
    <link rel="shortcut icon" href="../issets/img/mbox.svg" type="image/x-icon">
    <link rel="stylesheet" href="../issets/styles/movieupdate.css">
</head>
<body>
    <div class="container">
        <!-- Section 1: User Image Display (30%) -->
        <div class="movie-image">
            <img src="../issets/avatar/avatar1.png" alt="User Avatar" style="border-radius: 20px; height: 60vh;">
        </div>

        <!-- Section 2: User Update Form (70%) -->
        <div class="movie-form">
            <h2>User Update</h2>
            <form action="" method="POST" >

            
    <div class="dis_mess"><strong><?php if(!empty($message)){echo $message;}?></strong></div>

                <label for="user_name">Name:</label>
                <input type="text" name="user_name" value="<?php echo $display['name']?>" >

                <label for="email">Email:</label>
                <input type="email" name="email" value="<?php echo $display['email']?>">
                            
                <label for="password">New Password(option):</label>
                <input type="text" name="password" placeholder="<?php echo $display['password']?>">
             
                <button type="submit" name="updater">Update</button>
            </form>
        </div>
    </div>
</body>
</html>

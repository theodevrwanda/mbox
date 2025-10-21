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

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $deletekey = $_GET['id'];
    if(empty($deletekey)){
        die('movie id you want to delete not found !!.');
    }
}
 $deleteu = "DELETE FROM users WHERE id = $deletekey";
 $execute = $conn->query($deleteu);

 $deletemy = "DELETE FROM myusers WHERE id = $deletekey";
 $executemy = $conn->query($deletemy);

 if($execute == true && $executemy == true){
    header('Location:usercontroller.php');
    exit();
 } 
?>
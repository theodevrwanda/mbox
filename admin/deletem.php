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
 $deletem = "DELETE FROM movies WHERE id = $deletekey";
 $execute = $conn->query($deletem);
 if($execute == true){
    header('Location:moviescontroller.php');
    exit();
 } 
?>
<?php
require('../dbconf.php');
$key = $_GET['key'];
$deletekey = $_GET['id'];
if(empty($key) && empty($deletekey)){
    header('Location:feedback.php');
    exit();
}
elseif($key == 1){
    $trucate = "TRUNCATE TABLE feedback";
    $execute = $conn->query($trucate);
    if($execute == true){
        header('Location:feedback.php');
        exit();
    }
   
}

$deletemy = "DELETE FROM feedback WHERE feed_id = $deletekey";
$executemy = $conn->query($deletemy);
if($executemy == true){
    header('Location:feedback.php');
    exit();
}
 
?>
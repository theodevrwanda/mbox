<?php
// Start session to handle user session if needed
session_start();
require('dbconf.php');
if(isset($_SESSION['user_id']) || isset($_COOKIE['user_id']))
{   $id = (empty($_SESSION['user_id']))? $_COOKIE['user_id'] : $_SESSION['user_id'] ;
    $emailquery = " SELECT  * FROM users WHERE id=$id";
    $result =$conn->query($emailquery);
    $final = $result->fetch_assoc();
    $email = $final['email'];
//    fetch data from feedback from pushed by user
    $feed = (empty($_POST['feed']))? 'Unknow' : $_POST['feed'] ;
//  insert feed data
    $insertfeed = "INSERT INTO feedback (`email`,`feedback`) VALUES ('$email','$feed')";
    $insertresult = $conn->query($insertfeed); 
    if(isset($_POST['sendfromindex']))
    {
        $lc = "index.php";
    }
    elseif(isset($_POST['sendfromviews']))
    {   
        $key = (isset($_POST['key'])) ? $_POST['key'] : $key = 0;
        $lc = "client/views.php?id=".$key;
    }
    elseif(isset($_POST['sendfromall']))
    {
        $lc = "client/allmovies.php";
    }
    elseif(isset($_POST['sendfrommore']))
    {
        $lc = "client/more.php";
    }
    else
    {
        $lc = "index.php";
    }
    if($insertresult == true){
        echo '<h1>Thanks fro you feedback share your teams. if you have more to share our teams click here
        <a href="#">click Me</a>  you can click <a href="'.$lc.'">back</a> to continue your activities.';
        echo 'your current page is '.$lc.'</h1>';
        echo 'Get it: this website developed by theo iradukunda and 
        it product of rwatek if you want our website call or whatsapp : 0724935532';
    }
}
else{
    die('<h1>hhhhhh you are not memember but can click her to<a href="common/create.php"> Sign Up</a></h1>');
}
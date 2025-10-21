<?php
// Database connection
$servername = "fdb1029.awardspace.net";
$username = "4569344_mbox";
$password = "0724935532_mbox";
$dbname = "4569344_mbox";
$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
die('wait database connection');
}
<?php 

// ************************ 
// Connect to Database 
// ************************ 

$database = 'calendarwashu';
$host = 'localhost';
$user = 'root';
$password = 'root';

$conn = new mysqli($host, $user, $password, $database);

if($conn->connect_error){
    die("Could not connect to database" . $conn->connect_error);
}

?>
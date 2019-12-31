<?php 
// HTTP-Only Cookies
ini_set("session.cookie_httponly", 1);
session_start();
require('config.php');
// ini_set("session.cookie_httponly", 1);

// Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
header("Content-Type: application/json"); 

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$titleEvent = $json_obj['titleEvent'];
$timeEvent = $json_obj['timeEvent'];
$priorityEvent = $json_obj['priorityEvent'];
$dateEvent = $json_obj['dateEvent'];
$usernameShare = $json_obj['usernameShare'];

// -----------------------------------------------------------------
// Query to Share Events with another user
// -----------------------------------------------------------------
if(isset($_SESSION['username']) && isset($_SESSION['id'])){
    $stmt = $conn->prepare('insert into events(username, title, date, priority, time) values (?, ?, ?, ?, ?)');

    // Bind Parameters
    $stmt->bind_param("sssss", $usernameShare, $titleEvent, $dateEvent, $priorityEvent, $timeEvent);
    
    if($stmt->execute()){
        // Send response from server side
        echo json_encode(array(
            "success" => true,
            "usernameShare" => htmlentities($usernameShare)
        ));
        exit;
    }else{
        // Send response from server side
        echo json_encode(array(
            "success" => false,
            "message" => "Incorrect Username or Password"
        ));
        exit;
    }
}

?>
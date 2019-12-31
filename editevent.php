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

// Variables can be accessed as such:
$titleEvent = $json_obj['titleEvent'];
$timeEvent = $json_obj['timeEvent'];
$priorityEvent = $json_obj['priorityEvent'];
$dateEvent = $json_obj['dateEvent'];
$eventID = $json_obj['idEvent'];
// CSRF tokens are passed when
$tokenID = $json_obj['tokenID'];

// -----------------------------------------------------------------
// Query to Edit Events 
// -----------------------------------------------------------------
if(isset($_SESSION['username']) && isset($_SESSION['id'])){

    // test for validity of the CSRF token on the server side
    if(!hash_equals($_SESSION['token'], $tokenID)){
        die("Request forgery detected");
    }

    $userEvent = $_SESSION['username'];
    $stmt = $conn->prepare('update events set title = ?, priority = ?, time = ? where id = ?');
    
    // Bind Parameters
    $stmt->bind_param("ssss", $titleEvent, $priorityEvent, $timeEvent, $eventID);
    
    if($stmt->execute()){
        // Send response from server side
        echo json_encode(array(
            "success" => true,
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
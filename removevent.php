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

// //Variables can be accessed as such:
$eventID = $json_obj['idEvent'];
// CSRF tokens are passed when
$tokenID = $json_obj['tokenID'];

// -----------------------------------------------------------------
// Query to Remove Events 
// -----------------------------------------------------------------
if(isset($_SESSION['username']) && isset($_SESSION['id'])){
    // test for validity of the CSRF token on the server side
    if(!hash_equals($_SESSION['token'], $tokenID)){
        die("Request forgery detected");
    }

    $userEvent = $_SESSION['username'];
    $stmt = $conn->prepare('delete from events where id=? and username=?');

    // Bind Parameters
    $stmt->bind_param("ss", $eventID, $userEvent);
    
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
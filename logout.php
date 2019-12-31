<?php 
// HTTP-Only Cookies
ini_set("session.cookie_httponly", 1);
session_start();

// Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
header("Content-Type: application/json"); 

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$username = $json_obj['username'];

// -----------------------------------------------------------------
// Query to Login 
// -----------------------------------------------------------------
if(isset($_SESSION['username']) && $username == 'logout'){
    unset($_SESSION['username']);
    unset($_SESSION['id']);
    session_destroy();

    echo json_encode(array(
        // Send response from server side
        "success" => true
    ));
    exit;
}

?>
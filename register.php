<?php 
require('config.php');

// Since we are sending a JSON response here (not an HTML document), set the MIME Type to application/json
header("Content-Type: application/json"); 

//Because you are posting the data via fetch(), php has to retrieve it elsewhere.
$json_str = file_get_contents('php://input');
//This will store the data into an associative array
$json_obj = json_decode($json_str, true);

//Variables can be accessed as such:
$username = $json_obj['username'];
$password = $json_obj['password'];

// -----------------------------------------------------------------
// Query to Register
// -----------------------------------------------------------------
if(isset($username) && isset($password)){

    // Generate salt
    $options = array("cost" => 4);

    //Encrypt password in new PHP7 way
    $hashingPassword = password_hash($password, PASSWORD_BCRYPT, $options);

    $stmt = $conn->prepare('insert into users (username, password) values (?, ?)');

    // Bind Parameters
    $stmt->bind_param("ss", $username, $hashingPassword);

    // If query is true then generate simple toast successful notification
    if($stmt->execute()){
        // Send response from server side
        echo json_encode(array(
            "success" => true
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
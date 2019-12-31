<?php 
require('config.php');
// ini_set("session.cookie_httponly", 1);

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
// Query to Login 
// -----------------------------------------------------------------
if(isset($username) && isset($password)){
    $stmt = $conn->prepare('select * from users where username=?');

    // Bind Parameters
    $stmt->bind_param('s', $username);
    $stmt->execute();

    // Bind Results
    $stmt->bind_result($user_id, $user_name, $pwd_hashpassword);
    $stmt->fetch();

    // Check whether entered password is the same as registered. If true then create user session along with token.
    // After that redirect to main index.php page
    if(password_verify($password, $pwd_hashpassword)){
        // HTTP-Only Cookies
        ini_set("session.cookie_httponly", 1);
        session_start();
        $_SESSION['username'] = $user_name;
        $_SESSION['id'] = $user_id;
        $_SESSION['token'] = bin2hex(openssl_random_pseudo_bytes(32));

        echo json_encode(array(
            // Send response from server side
            "success" => true,
            "token" => htmlentities($_SESSION['token']),
            "username" => htmlentities($_SESSION['username'])
        ));
        exit;
    }else{
        echo json_encode(array(
            // Send response from server side
            "success" => false,
            "message" => "Incorrect Username or Password"
        ));
        exit;
    }
}
?>
<?php
    // HTTP-Only Cookies
    ini_set("session.cookie_httponly", 1);
    session_start();
    require('config.php');

    // -----------------------------------------------------------------
    // Query to Get Events 
    // -----------------------------------------------------------------
    if(isset($_SESSION['username']) && isset($_SESSION['id'])){
        $username = $_SESSION['username'];
        $output = array();

        // -------- Get all events for associated user with sorting by priority ---------------
        $stmt = $conn->prepare("select id, title, date, priority, time from events where username = ? order by FIELD(priority, 'high', 'low'), time DESC");
        // If query is true then continue. Otherwise, return error
        if(!$stmt){
            echo($conn->error);
        }

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->bind_result($event_id, $title, $date, $priority, $time);
        while($stmt->fetch()){
            // Return data as json format
            $output[] = array(
                "title" => htmlentities($title),
                "date" => htmlentities($date),
                "priority" => htmlentities($priority),
                "time" => htmlentities($time),
                "event_id" => htmlentities($event_id)
            );
        }
        $json_object = json_encode($output);
        // Send response from server side
        echo $json_object;
    }
    
?>
<?php

/*
    user log in. Send token with success message.

    API call is made using POST to this script.
*/
    session_start();

    // Required headers
    // First line allows API calls from any address
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");

    // Include database and object files
    include_once "../Config/Database.php";
    include_once "../Objects/User.php";
    
    // Instantiate database
    $database = new Database();
    $db = $database->getConnection();
    
    // Initialize user object
    $user = new User($db);

    // Get data sent from front-end
    // php://input includes body data sent using POST
    // file_get_contents reads parameter into one string
    // json_decode converts json string to php variable
    $data = json_decode(file_get_contents("php://input"));

    // Check that data is not missing any info
    if (!empty($data->email) &&
        !empty($data->password)) {

        // Set values in user.php
        $user->t_email     = $data->email;
        $user->t_password  = $data->password;

        // Successful login returns true
        if ($user->login()) {

            // Create success array
            $successArray["Success"] = array();
            
            // Fetch newly created user's id and send created token as well (replace TBA)
            $userID = $user->fetchID();

            array_push($successArray["Success"], array("User ID" => $userID, "Token" => "TBA"));

            // Session variable
            $_SESSION["loggedIn"] = true;
            $_SESSION["who"] = 'user';

            // HTTP status code - 200 OK
            http_response_code(200);

            echo json_encode($successArray);
        }
        // Request failed
        else {

            // HTTP status code - 401 Unauthorized
            http_response_code(401);

            echo json_encode(array("Message" => "Wrong Email Or Password"));
        }
    }
    // Data missing
    else {

        // HTTP status code - 400 Bad Request
        http_response_code(400);

        echo json_encode(array("Message" => "Bad Request. Incomplete Data"));
    }
?>
<?php

/*
    Driver log in. Send token with success message.

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
    include_once "../Objects/Driver.php";
    
    // Instantiate database
    $database = new Database();
    $db = $database->getConnection();
    
    // Initialize driver object
    $driver = new Driver($db);

    // Get data sent from front-end
    // php://input includes body data sent using POST
    // file_get_contents reads parameter into one string
    // json_decode converts json string to php variable
    $data = json_decode(file_get_contents("php://input"));
    
    // Check that data is not missing any info
    if (!empty($data->email) &&
        !empty($data->password)) {

        // Set values in driver.php
        $driver->u_email        = $data->email;
        $driver->u_password     = $data->password;

        // Successful login returns true
        if ($driver->login($data->email,$data->password)) {
            // Create success array
            $successArray["Success"] = array();
            
            // Fetch newly created driver's id and send created token as well (replace TBA)
            $driverID = $driver->fetchID();

            array_push($successArray["Success"], array("Driver ID" => $driverID, "Token" => "TBA"));

            // Session variable
            $_SESSION["loggedIn"] = true;
            $_SESSION["who"] = 'driver';

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
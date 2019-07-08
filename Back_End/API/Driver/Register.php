<?php

/*
   driver log in. Send token with success message.

    API call is made using POST to this script.
*/

    // Required headers
    // First line allows API calls from any address
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    
    // Include database and object files
    include_once "../Config/database.php";
    include_once "../Objects/Driver.php";
    
    // Instantiate database
    $database = new Database();
    $db = $database->getConnection();
    
    // Initialize driver object
    $driver = new driver($db);
    

    // Get data sent from front-end
    // php://input includes body data sent using POST
    // file_get_contents reads parameter into one string
    // json_decode converts json string to php variable
    $data = json_decode(file_get_contents('php://input'));
    // Check that data is not missing any info
    if (!empty($data->email) &&
        !empty($data->name) &&
        !empty($data->phonenumber) &&
        !empty($data->status) &&
        !empty($data->password) &&
        !empty($data->fbtoken)) {
        
        // Set values in driver.php
        $driver->d_email       = $data->email;
        $driver->d_name        = $data->name;
        $driver->d_phonenumber = $data->phonenumber;
        $driver->d_status      = $data->status;
        $driver->d_password    = $data->password;
        $driver->d_fb_token    = $data->fbtoken;
       
        // Successful registration returns true
        if ($driver->register()) {

            // HTTP status code - 200 OK
            http_response_code(200);

            echo json_encode(array("Success" => "User Created"));
        }
        // Request failed
        else {

            // HTTP status code - 400 Bad Request
            http_response_code(400);

            echo json_encode(array("Message" => "User Already Exists"));
        }
    }
    // Data missing
    else {

        // HTTP status code - 400 Bad Request
        http_response_code(400);
        echo json_encode(array("Message" => "Bad Request. Incomplete Data"));
    }
?>
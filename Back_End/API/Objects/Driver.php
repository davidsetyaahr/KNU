<?php
/*
    Driver class. Includes properties and CRUD methods.
    Actual CRUD operations are made in separate scripts
    which initialize an object from this class.
*/
    // Script for validating any Driver input
    include_once "../Shared/testInput.php";
    
    class Driver {
    
        // Database connection and table name
        private $conn;
        private $tableName = "ambulance";
        // Tutor properties
        public $d_id;
        public $d_email;
        public $d_name;      
        public $d_phonenumber;
        public $d_status;
        public $d_password;
       

    
        // Constructor with $db as database connection
        public function __construct($db) {
            $this->conn = $db;
        }

        // Fetch all tutors
        function fetchAll() {
        
            // Select all 
            $query = "SELECT * FROM ".$this->tableName;
        
            // Prepare query statement
            $drivers = $this->conn->prepare($query);
        
            // Execute query
            $drivers->execute();
        
            return $drivers;
        }
        
        // Register

    
       
        function register() {

            // Validate driver input
            $this->d_email       = testInput($this->d_email);
            $this->d_name        = testInput($this->d_name);
            $this->d_phonenumber = testInput($this->d_phonenumber);
            $this->d_status      = testInput($this->d_status);
            $this->d_password    = testInput($this->d_password);

            // Check that tutor is not registered already
            $result = $this->conn->query("SELECT * FROM $this->tableName WHERE Email LIKE '$this->d_email'");

            if ($result->rowCount() === 0) {

                // Insert query
                $query = "INSERT INTO $this->tableName 
                          (Email, Driver_name, driver_cell_phone, status, Password)
                          VALUES
                          (:email, :drivername, :phonenumber, :status, :hashedPassword)";

                // Prepare insert statement
                $insert = $this->conn->prepare($query);

                $insert->bindParam(":email", $this->d_email);
                $insert->bindParam(":drivername", $this->d_name);
                $insert->bindParam(":phonenumber", $this->d_phonenumber);
                $insert->bindParam(":status", $this->d_status);
                // Hash password before storing it
                $this->d_password = password_hash($this->d_password, PASSWORD_DEFAULT);
                $insert->bindParam(":hashedPassword", $this->d_password);

                // Send new driver to DB
                try {
                    $insert->execute();
                    return true;
                }
                catch(PDOException $e) {
                    echo $e;
                    return false;
                }          
            }
            else {
                return false;
            }
        }

        // Same like in registration, expect data in body
        function login($email, $password) {
            // Validate driver input
            $this->d_email      = testInput($email);
            $this->d_password   = testInput($password);

            // Fetch data from db with given email
            $result = $this->conn->query("SELECT * FROM $this->tableName WHERE Email LIKE '$this->d_email'");

            // Check that tutor exists in database
            if ($result->rowCount() === 1) {

                // Fetch driver record from result
                $user = $result->fetch();
                // Check password match
                if (password_verify($this->d_password, $user["Password"])) {
                    return true;
                }
                // Passwords do not match
                else {
                    return false;
                }
            }
            // User with given email not in database
            else {
                return false;
            }
        }

        // Fetch user ID
        function fetchID() {
            echo ($this->d_id);
            // FIXME For some reason d_email = $this->d_email results in SQL error
            // Using conn->query also makes this vulnerable to SQL injection
            // But prepare and execute refused to work for me
            $query = "SELECT idambulance FROM $this->tableName WHERE Email LIKE '$this->d_email'";

            try {
                $result = $this->conn->query($query);
            }
            catch (PDOException $e) {
                echo $e;
            }

            // Fetch tutor ID as integer
            $userID = (int)$result->fetchColumn();

            return $userID;
        }

         
    }
?>
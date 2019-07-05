<?php
/*
    User class. Includes properties and CRUD methods.
    Actual CRUD operations are made in separate scripts
    which initialize an object from this class.
*/
    // Script for validating any user input
    include_once "../Shared/testInput.php";
    
    class User {
    
        // Database connection and table name
        private $conn;
        private $tableName = "User";
        // Tutor properties
        public $u_id;
        public $u_email;
        public $u_name;      
        public $u_phonenumber;
        public $u_address;
        public $u_password;
       

    
        // Constructor with $db as database connection
        public function __construct($db) {
            $this->conn = $db;
        }

        // Fetch all tutors
        function fetchAll() {
        
            // Select all 
            $query = "SELECT * FROM ".$this->tableName;
        
            // Prepare query statement
            $users = $this->conn->prepare($query);
        
            // Execute query
            $users->execute();
        
            return $users;
        }
        
        // Register
        function register() {

            // Validate user input
            $this->u_email       = testInput($this->u_email);
            $this->u_name        = testInput($this->u_name);
            $this->u_phonenumber = testInput($this->u_phonenumber);
            $this->u_address     = testInput($this->u_address);
            $this->u_password    = testInput($this->u_password);

            // Check that tutor is not registered already
            $result = $this->conn->query("SELECT * FROM $this->tableName WHERE Email LIKE '$this->u_email'");

            if ($result->rowCount() === 0) {

                // Insert query
                $query = "INSERT INTO $this->tableName 
                          (Email, Name, Phone_number, address, Password)
                          VALUES
                          (:email, :username, :phonenumber, :address, :hashedPassword)";

                // Prepare insert statement
                $insert = $this->conn->prepare($query);

                $insert->bindParam(":email", $this->u_email);
                $insert->bindParam(":username", $this->u_name);
                $insert->bindParam(":phonenumber", $this->u_phonenumber);
                $insert->bindParam(":address", $this->u_address);
                // Hash password before storing it
                $this->u_password = password_hash($this->u_password, PASSWORD_DEFAULT);
                $insert->bindParam(":hashedPassword", $this->u_password);

                // Send new user to DB
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
            // Validate user input
            $this->u_email      = testInput($email);
            $this->u_password   = testInput($password);

            // Fetch data from db with given email
            $result = $this->conn->query("SELECT * FROM $this->tableName WHERE Email LIKE '$this->u_email'");

            // Check that tutor exists in database
            if ($result->rowCount() === 1) {

                // Fetch user record from result
                $user = $result->fetch();
                // Check password match
                if (password_verify($this->u_password, $user["Password"])) {
                    return true;
                }
                // Passwords do not match
                else {
                    return false;
                }
            }
            // User with given email not in database
            else {
                echo "false 2";
                return false;
            }
        }

        // Fetch user ID
        function fetchID() {
            echo ($this->u_id);
            // FIXME For some reason u_email = $this->u_email results in SQL error
            // Using conn->query also makes this vulnerable to SQL injection
            // But prepare and execute refused to work for me
            $query = "SELECT idUser FROM $this->tableName WHERE idUser LIKE '$this->u_id'";

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
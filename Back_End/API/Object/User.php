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
        private $tableName = "User_table";
        // Tutor properties
        public $u_num;
        public $u_fname;
        public $u_lname;
        public $u_bdate;
        public $u_sex;
        public $u_email;
        public $u_password;     // TODO: Remove. Only for developing purposes
        public $u_address;
       
        //Subject_table items
    
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
            $this->u_fname       = testInput($this->u_fname);
            $this->u_lname       = testInput($this->u_lname);
            $this->u_bdate       = testInput($this->u_bdate);
            $this->u_sex         = testInput($this->u_sex);
            $this->u_email       = testInput($this->u_email);
            $this->u_password    = testInput($this->u_password);
            $this->u_address     = testInput($this->u_address);

            // Check that tutor is not registered already
            $result = $this->conn->query("SELECT * FROM $this->tableName WHERE t_email LIKE '$this->t_email'");

            if ($result->rowCount() === 0) {

                // Insert query
                $query = "INSERT INTO $this->tableName 
                          (t_fname, t_lname, t_bdate, t_sex, t_email, t_password, t_address)
                          VALUES
                          (:firstName, :lastName, :birthday, :sex, :email, :hashedPassword, :street)";

                // Prepare insert statement
                $insert = $this->conn->prepare($query);

                $insert->bindParam(":firstName", $this->u_fname);
                $insert->bindParam(":lastName", $this->u_lname);
                $insert->bindParam(":birthday", $this->u_bdate);
                $insert->bindParam(":sex", $this->u_sex);
                $insert->bindParam(":email", $this->u_email);
                $insert->bindParam(":street", $this->u_address);
                // Hash password before storing it
                $this->t_password = password_hash($this->u_password, PASSWORD_DEFAULT);
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
        function login() {

            // Validate user input
            $this->u_email      = testInput($this->u_email);
            $this->u_password   = testInput($this->u_password);

            // Fetch data from db with given email
            $result = $this->conn->query("SELECT * FROM $this->tableName WHERE t_email LIKE '$this->t_email'");

            // Check that tutor exists in database
            if ($result->rowCount() === 1) {

                // Fetch user record from result
                $user = $result->fetch();

                // Check password match
                if (password_verify($this->t_password, $user["t_password"])) {
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

            // FIXME For some reason t_email = $this->t_email results in SQL error
            // Using conn->query also makes this vulnerable to SQL injection
            // But prepare and execute refused to work for me
            $query = "SELECT t_num FROM $this->tableName WHERE t_email LIKE '$this->t_email'";

            try {
                $result = $this->conn->query($query);
            }
            catch (PDOException $e) {
                echo $e;
            }

            // Fetch tutor ID as integer
            $tutorID = (int)$result->fetchColumn();

            return $tutorID;
        }

         
    }
?>
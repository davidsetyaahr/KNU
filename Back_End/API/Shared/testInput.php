<?php
/*
    Include this script when you want to validate
    user input.
*/
    function testInput($data) {
        $data = trim($data);              // Trims whitespace around
        $data = stripslashes($data);      // Removes / and \
        $data = htmlspecialchars($data);  // Disables code injections
        return $data;
    }

?>
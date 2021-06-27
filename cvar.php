<?php
    $dbhost = "localhost";
    $dbuser = "root";
    $dbpass = "mysql";
    $dbname = "foods"; 
    $conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
    if ($conn->connect_error) {
        echo "Connection failed<br/>"; die("Connection failed: " . $conn->connect_error);
    }
?>

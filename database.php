<?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "osp_web-application";

    try {
        $conn = new mysqli($servername, $username, $password, $dbname);
        
        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

    } catch (Exception $e) {
        echo "Connection Error: " . $e->getMessage() . "<br>";
    }
?>

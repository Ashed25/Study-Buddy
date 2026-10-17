<?php
try {
    // Database credentials
    $host = "localhost";
    $dbname = "finalproj_sec541_group2";
    $username = "root";
    $password = "";

    // Create a PDO instance
    $con = new PDO("mysql:host=$host; dbname=$dbname", $username, $password);

    // Set PDO attributes
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Enable exceptions for errors
    $con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Set default fetch mode to associative array
    $con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // Disable emulated prepared statements

} catch (PDOException $e) {
    // Handle connection errors
    die("Database connection failed: " . $e->getMessage());
}
?>
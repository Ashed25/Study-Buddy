<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "finalproj_sec541_group2";

// Create connection
$conn = new mysqli($host, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
} else {
    echo "Database connected successfully!<br>";

    // Test query: Fetch data from the Users table
    $query = "SELECT * FROM Users LIMIT 1";
    $result = $conn->query($query);

    if ($result) {
        if ($result->num_rows > 0) {
            echo "Database schema is working correctly. Found " . $result->num_rows . " user(s).";
        } else {
            echo "No users found in the database.";
        }
    } else {
        echo "Error executing query: " . $conn->error;
    }
}

// Close connection
$conn->close();
?>
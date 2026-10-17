<?php
session_start();

// Redirect to login if not logged in or not an admin
if (!isset($_SESSION["loggedIn"]) || $_SESSION["UserType"] != "Admin") { // Changed "admin" to "Admin"
    header('Location: ../login.php');
    exit(); // Ensure script stops execution after redirect
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>StudyBuddy | Admin</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="font-awesome/css/font-awesome.min.css">
</head>
<body>
    <?php 
    // Include header
    if (file_exists("inc/header.php")) {
        include("inc/header.php");
    } else {
        die("Error: Header file not found!");
    }
    ?>

<div class="admin-container">
    <div class="sidebar">
        <?php include("inc/leftmargin.php"); ?>
    </div>
    <div class="main-content">
        <?php include("inc/bodyright.php"); ?>
    </div>
</div>
</html>
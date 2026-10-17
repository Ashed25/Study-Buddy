<?php
// Ensure the database and function files are included
include_once("../admin/inc/db.php");
include_once("../admin/inc/function.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["CID"]) && isset($_POST["rejection_reason"])) {
    $CID = intval($_POST["CID"]); // Ensure it's an integer
    $reason = trim($_POST["rejection_reason"]); // Match the JavaScript POST field name

    if (!empty($CID) && !empty($reason)) {
        if (reject_course($CID, $reason)) {
            echo "success";
        } else {
            echo "Error rejecting course.";
        }
    } else {
        echo "Invalid request: Course ID or reason missing.";
    }
} else {
    echo "Invalid request.";
}
?>
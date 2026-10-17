<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include("../admin/inc/db.php");
 
if (!isset($_SESSION["UserID"])) {
    die("Error: User not logged in.");
}
 
if (!isset($_GET['reward_id']) || !is_numeric($_GET['reward_id'])) {
    die("Error: Invalid reward ID.");
}
 
$UID = $_SESSION["UserID"];
$reward_id = intval($_GET['reward_id']); // Ensure it's a number
 
// Fetch reward details
$query = $con->prepare("SELECT r.reward_id, r.points_required, c.CourseID FROM rewards r JOIN courses c ON r.CourseID = c.CourseID WHERE r.reward_id = ?");
$query->execute([$reward_id]);
$reward = $query->fetch(PDO::FETCH_ASSOC);
 
if (!$reward) {
    die("Error: Reward not found.");
}
 
// Check if the user has enough points
$user_query = $con->prepare("SELECT points FROM users WHERE UserID = ?");
$user_query->execute([$UID]);
$user = $user_query->fetch(PDO::FETCH_ASSOC);
 
if (!$user) {
    die("Error: User not found.");
}
 
if ($user['points'] < $reward['points_required']) {
    die("Error: Not enough points to redeem this reward.");
}
 
// Deduct points
$update_points = $con->prepare("UPDATE users SET points = points - ? WHERE UserID = ?");
$update_points->execute([$reward['points_required'], $UID]);
 
// Check if the user is already enrolled in this course
$check_enrollment = $con->prepare("SELECT * FROM enrollments WHERE UserID = ? AND CourseID = ?");
$check_enrollment->execute([$UID, $reward['CourseID']]);
 
if ($check_enrollment->rowCount() == 0) {
    // Enroll the user in the course only if they are not already enrolled
    $enroll = $con->prepare("INSERT INTO enrollments (UserID, CourseID) VALUES (?, ?)");
    $enroll->execute([$UID, $reward['CourseID']]);
} else {
    die("Error: You have already redeemed this reward.");
}
 
// Redirect to mycourses.php after successful redemption
header("Location: mycourses.php?success=1");
exit();
 
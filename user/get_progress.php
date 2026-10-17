<?php
session_start();
include("../admin/inc/db.php");
 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $CID = $_POST['CourseID'];  // Course ID
    $UID = $_POST['UserID'];  // User ID
 
    // Count total videos in the course
    $stmt = $con->prepare("SELECT COUNT(*) FROM tasks WHERE CourseID = :CID");
    $stmt->bindParam(':CID', $CID);
    $stmt->execute();
    $totalVideos = $stmt->fetchColumn();
 
    // Count completed videos by the user
    $stmt = $con->prepare("SELECT COUNT(*) FROM tasks WHERE CourseID = :CID AND UserID = :UID");
    $stmt->bindParam(':CID', $CID);
    $stmt->bindParam(':UID', $UID);
    $stmt->execute();
    $completedVideos = $stmt->fetchColumn();
 
    // Calculate completion percentage
    $completionPercentage = ($completedVideos / $totalVideos) * 100;
 
    echo json_encode(['percentage' => $completionPercentage]);
}
?>
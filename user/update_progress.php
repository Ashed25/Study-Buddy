<?php
session_start();
include("../admin/inc/db.php");
 
if (isset($_POST['TaskID']) && isset($_POST['CourseID']) && isset($_SESSION['UserID'])) {
    $video_id = $_POST['TaskID'];
    $cid = $_POST['CourseID'];
    $uid = $_SESSION['UserID'];
 
    // Check if progress already exists
    $check_progress = $con->prepare("SELECT * FROM user_task WHERE TaskID = ? AND CourseID = ? AND UserID = ?");
    $check_progress->execute([$video_id, $cid, $uid]);
 
    if ($check_progress->rowCount() == 0) {
        // Insert new progress record
        $insert_progress = $con->prepare("INSERT INTO user_task (TaskID, CourseID, UserID, completed) VALUES (?, ?, ?, 1)");
        $insert_progress->execute([$video_id, $cid, $uid]);
 
        // Award points for completing a lecture
        award_points_on_completion($uid);
    } else {
        // Update progress if it already exists
        $update_progress = $con->prepare("UPDATE user_task SET completed = 1 WHERE TaskID = ? AND CourseID = ? AND UserID = ?");
        $update_progress->execute([$video_id, $cid, $uid]);
       
        // Award points for completing a lecture
        award_points_on_completion($uid);
    }
    echo json_encode(["status" => "success", "message" => "Lecture marked as completed"]);
} else {
    echo json_encode(["status" => "error", "message" => "Invalid request"]);
}
?>
 
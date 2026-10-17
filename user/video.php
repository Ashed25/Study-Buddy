<?php  
session_start();
require_once "inc/function.php";
// Redirect if not logged in or not a student
if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] != 1) {
    header('Location: login.php');
    exit();
  }
  if ($_SESSION["UserType"] != "Student") {  
    header('Location: login.php');
    exit();
  }
// Include the database connection
include("../admin/inc/db.php");
 
// Check if the student is logged in, otherwise redirect them to the login page
if (!isset($_SESSION['UserID'])) {
    header("Location: login.php");
    exit();
}
 
// Get the Course ID (CID) from the URL
if (isset($_GET["id"])) {
    $cid = $_GET["id"];
    $UID = $_SESSION['UserID'];
 
    // Get total lectures
    $get_lecture = $con->prepare("SELECT COUNT(*) AS total FROM tasks WHERE CourseID = ?");
    $get_lecture->execute([$cid]);
    $total_lectures = $get_lecture->fetch(PDO::FETCH_ASSOC)['total'];
 
    // Get completed lectures
    $get_completed = $con->prepare("SELECT COUNT(*) AS completed FROM user_task WHERE CourseID = ? AND UserID = ? AND completed = 1");
    $get_completed->execute([$cid, $UID]);
    $completed_lectures = $get_completed->fetch(PDO::FETCH_ASSOC)['completed'];
 
    // Calculate completion percentage
    $completion_percentage = $total_lectures > 0 ? ($completed_lectures / $total_lectures) * 100 : 0;
}
 
// Handle completion of a lecture
if (isset($_POST['complete_video'])) {
    $video_id = $_POST['video_id'];
 
    // Update completion status
    $update_progress = $con->prepare("INSERT INTO user_task (TaskID, CourseID, UserID, completed) VALUES (?, ?, ?, 1)
                                      ON DUPLICATE KEY UPDATE completed = 1");
    $update_progress->execute([$video_id, $cid, $UID]);
 
    // Award points for completing the lecture
    award_points_on_completion($UID);
 
    header("Location: " . $_SERVER['REQUEST_URI']);
    exit();
}
?>
 
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
 
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
 
    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
 
    <link rel="stylesheet" href="design/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="design/bootstrap-social/bootstrap-social.css">
    <link rel="stylesheet" href="../css/animate.min.css">
    <title>LearnEd - The better way to learn</title>
  </head>
 
  <body>
    <?php include 'inc/header.php'; ?>
    <div class="flex-box">
        <?php
        include("inc/leftmargin.php");
        include("inc/bodyright.php");
        ?>
    </div>
   
    <div class="progress-container text-center mt-3">
        <h5>Progress: <?php echo round($completion_percentage, 2); ?>%</h5>
        <div class="progress" style="height: 20px;">
            <div class="progress-bar" role="progressbar" style="width: <?php echo $completion_percentage; ?>%;" aria-valuenow="<?php echo $completion_percentage; ?>" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>
 
    <!---Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
 
  </body>
</html>
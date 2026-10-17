<?php
session_start();
include("../admin/inc/db.php"); // Ensure the database connection is properly included
 
// Ensure UID is set in the session
if (isset($_SESSION["UserID"])) {
    $UID = $_SESSION["UserID"];
 
    // Fetch student points from signup_user
    $get_user = $con->prepare("SELECT points FROM users WHERE UserID = ? AND UserType = 'student'");
    $get_user->execute([$UID]);
    $user = $get_user->fetch(PDO::FETCH_ASSOC);
 
    // Check if the user exists and has points; otherwise, set default
    $_SESSION['points'] = isset($user['points']) ? $user['points'] : 0;
} else {
    $_SESSION['points'] = 0; // Default if no UID found
}
 
// Ensure required functions are available
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
  
 
// Check if a course ID (cid) is passed for redemption
if (isset($_GET['CourseID']) && is_numeric($_GET['CourseID'])) {
    $CID = intval($_GET['CourseID']);  // Sanitize input
    redeem_course($_SESSION['UserID'], $CID);  // Call redeem_course function
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
 
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" href="design/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="design/bootstrap-social/bootstrap-social.css">
    <link rel="stylesheet" href="../css/animate.min.css">
    <title>StudyBuddy - The better way to learn</title>
  </head>
 
  <body id="mycourse">
    <?php include 'inc/header.php'; ?><br><br><br>
 
    <div class="container">
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['fname']); ?></h1>
 
        <div>
            <h1>My Courses</h1>
        </div>
 
        <div class="course-holder">
            <?php echo view_mycourse(); ?>
        </div>
    </div>
 
   
 
    <script type="text/javascript" src="../js/wow.min.js"></script>
    <script>
        new WOW().init();
    </script>
 
    <!-- jQuery, Popper.js, and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
 
  </body>
</html>
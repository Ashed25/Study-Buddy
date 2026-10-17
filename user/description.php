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
<link
    rel="stylesheet"
    href="../css/animate.min.css">
    <title>LearnEd - The better way to learn</title>
  </head>
 
  <body>
    <?php include 'inc/header.php'; ?><br><br><br>
    <div>
      <div>
      <h3><!--Recommended for you---></h3>
    </div>
           <div class="container">
              <?php echo view_course_details();?>
           </div>
    </div>
  </body>
</html>
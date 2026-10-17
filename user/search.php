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

// Fetch the logged-in student's ID
$studentID = $_SESSION["UserID"];

// Get the search term from the query string
$searchTerm = isset($_GET["s"]) ? htmlspecialchars($_GET["s"]) : '';

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
    <title>StudyBuddy - The better way to learn</title>
  </head>

  <body id="mycourse">
    <?php include 'inc/header.php'; ?><br><br><br>

    <div class="container">
        <div>
            <h2>Search Results for "<?php echo htmlspecialchars($searchTerm); ?>"</h2>
        </div>
        <div class="course-holder">
            <?php
            $searchResults = view_search_course($searchTerm);
            if (!$searchResults) { 
            ?>
                <style type="text/css">
                    body {
                        background-image: url("../img/search.webp");
                        overflow: hidden;
                        background-size: auto;
                        background-attachment: fixed;
                        background-position: right;
                        background-repeat: no-repeat;
                    }
                </style>
                <div class="error404">
                    <h2>Sorry, we couldn't find any results for "<?php echo htmlspecialchars($searchTerm); ?>"</h2><br>
                    <h4>Try adjusting your search. Here are some ideas:</h4>
                    <p><i class="fa fa-hand-o-right" aria-hidden="true"></i> Make sure all words are spelled correctly.</p>
                    <p><i class="fa fa-hand-o-right" aria-hidden="true"></i> Try different search terms.</p>
                    <p><i class="fa fa-hand-o-right" aria-hidden="true"></i> Try more general search terms.</p>
                </div>
            <?php } ?>
        </div>
    </div>

    <script type="text/javascript" src="../js/wow.min.js"></script>
    <script>
        new WOW().init();
    </script>
    <!---Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>
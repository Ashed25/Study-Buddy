<?php
session_start();  // Ensure session is started before accessing session variables
?>
<?php require_once "function.php";  ?>
<?php echo add_course(); 
echo submit_course();

// Redirect if not logged in or not a student
if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] != 1) {
  header('Location: ../login.php');
  exit();
}
if ($_SESSION["UserType"] != "Tutor") {  
  header('Location: ../login.php');
  exit();
}

?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" 
          integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="index.css">
    <link rel="stylesheet" href="../design/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../design/bootstrap-social/bootstrap-social.css">
    <title>StudyBuddy - The better way to learn</title>
  </head>
  <body>
    <div class="navigation">
      <nav class="navbar navbar-expand-lg navbar-light " style="background-color:#34ace0; width: 100%;" >
        <a class="navbar-brand" href="index.php">
            <img src="../img/lo1.png" width="150px" height="45px"  alt="LearnEd" style="padding-left: 25px;padding-bottom: 7px;">
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" 
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" 
                 aria-haspopup="true" aria-expanded="false" style="color: white;padding-right: 95px;font-size: 20px;">
                 <i class="fa fa-user-circle-o" aria-hidden="true">  <?php echo $_SESSION["fname"]." ".$_SESSION["lname"]?></i>
              </a>
              <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="fees.php"><i class="fa fa-usd" aria-hidden="true"></i> Earnings</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="logout.php"><i class="fa fa-power-off" aria-hidden="true"></i> Log out</a>
              </div>
            </li>              
          </ul>
        </div>
      </nav>
    </div>

<div class="fees">
  <h2 style="font-family: roboto;">
    <img src="../img/tlogo2.png" style="width: 70px;height: 55px;"> Welcome to Dashboard 
    <img src="../img/tlogo2.png" style="width: 70px;height: 55px;">
  </h2>
  <h5>To teach is to learn twice over.</h5>
</div>

<div id="add">
  <details>
    <summary>Add Course</summary>
    <form method="post" enctype="multipart/form-data">
      <input type="text" name="title" placeholder="Enter Course Title" required="required" />
      <select name="sub_cat_id" required="required">
          <option value="">Select Category</option>
          <?php echo select_sub_cat(); ?>
      </select> 
      <textarea class="form-control" id="exampleFormControlTextarea1" placeholder="Enter Course Description (more than 100 words recommended)" 
                rows="5" name="decription" required="required"></textarea>

      <input type="text" name="price" placeholder="Enter Course Price" required="required" />
      <div class="row">
  <legend class="col-form-label col-sm-2 pt-0">Level</legend>
  <div class="col-sm-10">
    <div class="form-check">
      <input class="form-check-input" value="Beginner" type="radio" name="level" id="gridRadios1" required="required" checked>
      <label class="form-check-label" for="gridRadios1"> Beginner </label>
    </div>
    <div class="form-check">
      <input class="form-check-input" value="Intermediate" type="radio" name="level" id="gridRadios2">
      <label class="form-check-label" for="gridRadios2"> Intermediate </label>
    </div>
    <div class="form-check">
      <input class="form-check-input" value="Advance" type="radio" name="level" id="gridRadios3">
      <label class="form-check-label" for="gridRadios3" style="padding-bottom: 15px;"> Advance </label>
    </div>
  </div>
</div>

      <div class="form-group">
        <label for="exampleFormControlFile1">Upload Course Image:</label>
        <input type="file" name="thumbnail" class="form-control-file" id="exampleFormControlFile1">
      </div>
      <center><button>Add Course</button></center>
    </form>
  </details>
  
  <table>
    <tr>
      <th>Sr No.</th>
      <th></th>
      <th>Course</th>
      <th>Category</th>
      <th>Price</th>
      <th>Status</th>
      <th>Add lectures</th>
      <th>Submit</th>
    </tr>
    <?php echo view_course(); ?>
  </table>
</div>

    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

</body>
</html>

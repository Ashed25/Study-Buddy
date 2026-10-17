<?php session_start();
require_once "function.php"; 
echo add_lectures(); 
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

<!DOCTYPE html>
<html>
<head>
  
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="index.css">

    <link rel="stylesheet" href="../design/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../design/bootstrap-social/bootstrap-social.css">

    <title>StudyBuddy - The better way to learn</title>


</head>
<body>

  <div class="navigation">
          <nav class="navbar navbar-expand-lg navbar-light " style="background-color:#34ace0; width: 100%;" >
            <a class="navbar-brand"  href="index.php">
                <img src="../img/lo1.png" width="150px" height="45px"  alt="LearnEd" style="padding-left: 25px;padding-bottom: 7px;"></a>

                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>


            <div class="navbar-collapse collapse" id="navbarSupportedContent">
              <ul class="navbar-nav ml-auto" >

                  <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: white;padding-right: 95px;font-size: 20px;"><i class="fa fa-user-circle-o" aria-hidden="true">  <?php echo $_SESSION["fname"]." ".$_SESSION["lname"]?></i></a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="logout.php"><i class="fa fa-power-off" aria-hidden="true"></i> Log out</a>
                  </div>
                  </li>              
                </ul>
              </div>
           </nav>
        </div>


<div class="addlec">
  <div class="flex">
        <a class="btn btn-warning" href="index.php" style="margin-left: 70px; margin-top: 30px; border: 2px solid #000;" role="button"><i class="fa fa-long-arrow-left" aria-hidden="true"></i> Back</a>
          <h1 style="text-align:center;">Add Lectures</h1>
  </div>
  
        	<div id="add">
          <details>
            <summary>Click to add lectures</summary>
            <form method="post" enctype="multipart/form-data">
              <input type="text" name="title" placeholder="Enter Lecture title" required="required" /> 
              <input type="text" name="lec_no" placeholder="Enter Lecture number" required="required" />
              <div class="form-group">
                <label for="exampleFormControlFile1">Upload Lecture Video:</label>
                <input type="file" name="video" class="form-control-file" id="exampleFormControlFile1">
              </div>
              <center><button>Add Lecture</button></center>
            </form>
          </details>

        <table>
              <tr>
                <th>Sr No.</th>
                <th>Lecture Name</th>
                <th>Video</th>
                <th>Lecture Number</th>
              </tr>
                    <?php echo view_lectures(); ?>

            </table>
          </div>

  </div>

</body>
</html>




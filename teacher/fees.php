<?php 
session_start(); 
require_once "../admin/inc/db.php"; // Add this line to include the database connection
require_once "function.php"; 

echo add_course(); 
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
$UID = $_SESSION["UserID"];

// Fetch the updated balance after withdrawal
$get_balance = $con->prepare("SELECT balance FROM users WHERE UserID = ? AND UserType = 'Tutor'");
$get_balance->execute([$UID]);
$row = $get_balance->fetch(PDO::FETCH_ASSOC);
$total_earned = $row["balance"]; // Store updated balance

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
    <link rel="stylesheet" type="text/css" href="index.css">

    <link rel="stylesheet" href="../design/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="../design/bootstrap-social/bootstrap-social.css">

    <title>StudyBuddy - The better way to learn</title>
</head>

<div class="navigation">
    <nav class="navbar navbar-expand-lg navbar-light " style="background-color:#34ace0; width: 100%;" >
        <a class="navbar-brand"  href="index.php">
            <img src="../img/lo1.png" width="150px" height="45px"  alt="LearnEd" style="padding-left: 25px;padding-bottom: 7px;">
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="navbar-collapse collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: white;padding-right: 95px;font-size: 20px;">
                        <i class="fa fa-user-circle-o" aria-hidden="true"> <?php echo $_SESSION["fname"]." ".$_SESSION["lname"]?></i>
                    </a>

                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#withdrawModal">
                            <i class="fa fa-money" aria-hidden="true"></i> Withdraw Earnings
                        </a>

                        <a class="dropdown-item" href="logout.php"><i class="fa fa-power-off" aria-hidden="true"></i> Log out</a>
                    </div>
                </li>              
            </ul>
        </div>
    </nav>
</div>

<div class="fees">
   <h2>$ <?php echo view_total_fees(); ?></h2>
   <p>Total Earned</p>
</div>

<div id="add">
<table class="table table-striped">
    <thead>
        <tr>
            <th>Sr. No.</th>
            <th></th>
            <th>Course</th>
            <th>Course Price</th>
            <th>Enrolled</th>
            <th>Pay per Enroll</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        <?php echo view_fees(); ?> <!-- Ensure this function is only called ONCE -->
    </tbody>
</table>

</div>

<!-- Withdraw Modal -->
<div class="modal fade" id="withdrawModal" tabindex="-1" role="dialog" aria-labelledby="withdrawModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="withdrawModalLabel">Enter Payment Details</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form action="withdraw.php" method="POST">
          <input type="hidden" name="teacher_id" value="<?php echo $_SESSION['UID']; ?>">
          <input type="hidden" name="total_balance" value="<?php echo view_total_fees(); ?>">

          <div class="form-group">
            <label for="withdraw_amount">Withdraw Amount</label>
            <input type="number" class="form-control" name="withdraw_amount" min="1" max="<?php echo view_total_fees(); ?>" required>
          </div>

          <div class="form-group">
            <label for="name">Full Name</label>
            <input type="text" class="form-control" name="name" required>
          </div>

          <div class="form-group">
            <label for="bank_name">Bank Name</label>
            <input type="text" class="form-control" name="bank_name" required>
          </div>

          <div class="form-group">
            <label for="bank_acc">Bank Account Number</label>
            <input type="text" class="form-control" name="bank_acc" required>
          </div>

          <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Confirm Withdrawal</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>



</div>

<body>
    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>
</html>

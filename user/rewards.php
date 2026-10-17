<?php
session_start();
include("../admin/inc/db.php"); // Ensure database connection
 
// Ensure UID is set in the session
if (isset($_SESSION["UserID"])) {
    $UID = $_SESSION["UserID"];
 
    // Fetch student points from signup_user
    $get_user = $con->prepare("SELECT points FROM users WHERE UserID = ? AND UserType = 'Student'");
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
 
// Fetch rewards from the database
$get_rewards = $con->prepare("
    SELECT r.reward_id, c.name AS courses_name, c.thumbnail, r.points_required, r.CourseID
    FROM rewards r
    JOIN courses c ON r.CourseID = c.CourseID
");
 
$get_rewards->execute();
$rewards = $get_rewards->fetchAll(PDO::FETCH_ASSOC);
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
    <title>StudyBuddy - Rewards</title>
</head>
 
<body id="rewards">
    <?php include 'inc/header.php'; ?><br><br><br>
 
    <div class="container">
        <h1>Available Rewards</h1>
        <p>Your Current Points: <strong><?php echo $_SESSION['points']; ?></strong></p>
 
        <div class="course-holder">
            <?php if (!empty($rewards)) { ?>
                <?php foreach ($rewards as $reward) { ?>
                    <div class="card">
                        <img class="card-img-top" src="../img/thumbnails/<?php echo htmlspecialchars($reward['thumbnail']); ?>" alt="<?php echo htmlspecialchars($reward['courses_name']); ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($reward['courses_name']); ?></h5>
                            <p>Required Points: <strong><?php echo $reward['points_required']; ?></strong></p>
 
                            <?php
                            // Ensure 'CID' exists before proceeding
                            if (!isset($reward['CourseID']) || empty($reward['CourseID'])) {
                                echo '<button class="btn btn-secondary" disabled>Invalid Reward</button>';
                            } else {
                                // Check if the student has already redeemed this reward
                                $check_redemption = $con->prepare("SELECT * FROM enrollments WHERE CourseID = ? AND UserID = ?");
                                $check_redemption->execute([$reward['CourseID'], $UID]);
                                $is_redeemed = $check_redemption->rowCount() > 0;
 
                                if ($is_redeemed) {
                                    echo '<button class="btn btn-secondary" disabled>Already Redeemed</button>';
                                } elseif ($_SESSION['points'] >= $reward['points_required']) {
                                    echo '<a href="redeem.php?reward_id=' . $reward['reward_id'] . '" class="btn btn-success">Redeem</a>';
                                } else {
                                    echo '<button class="btn btn-secondary" disabled>Not Enough Points</button>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                <?php } ?>
            <?php } else { ?>
                <p class="text-center">No rewards available at the moment.</p>
            <?php } ?>
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
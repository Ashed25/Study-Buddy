<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("../admin/inc/db.php");

// Check if session 'points' is set; otherwise, initialize it
if (!isset($_SESSION['points'])) {
    $_SESSION['points'] = 0; // Default value to avoid warnings
}

// Ensure user is logged in before fetching points
if (isset($_SESSION["UserID"])) {
    $UserID = $_SESSION["UserID"];
    $get_user = $con->prepare("SELECT points FROM users WHERE UserID = ? AND UserType = 'Student'");
    $get_user->execute([$UserID]);
    $user = $get_user->fetch(PDO::FETCH_ASSOC);

    // Store points in session
    if ($user) {
        $_SESSION['points'] = $user['points'];
    }
}
?>

<div class="navigation">
    <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #34ace0; width: 100%;">
        <a class="navbar-brand" href="index.php">
            <img src="img/lo1.png" width="135px" height="43px" alt="LearnEd" style="padding-left: 25px;padding-bottom: 7px;">
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="navbar-collapse collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color: white;padding-right: 25px;padding-left:25px;">
                        Categories
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <?php echo view_cat(); ?>
                    </div>
                </li>

                <form class="form-inline my-2 my-lg-0" action="search.php" method="get">
                    <input class="form-control mr-sm-2" type="search" name="s" placeholder="Search" aria-label="Search" style="width: 490px;" value="<?php if(isset($_GET["s"])){echo $_GET["s"]; }?>">
                    <button class="btn btn-outline-light my-2 my-sm-0" type="submit" style="padding-left: 30px; padding-right: 30px;">Search</button>
                </form>

                <li class="nav-item">
                    <a class="nav-link" href="mycourses.php" style="color: white;padding-left: 20px; padding-right: 20px;">My Courses</a>
                    
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="rewards.php" style="color: white;padding-left: 20px; padding-right: 20px;">Rewards</a>
                </li>
            </ul>
            <ul class="navbar-nav mr-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdown" role="button" data-toggle="dropdown">
                        <i class="fa fa-user"></i> <?php echo $_SESSION['fname']; ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <a class="dropdown-item disabled">Points: <strong><?php echo isset($_SESSION['points']) ? $_SESSION['points'] : 0; ?></strong></a>
                        <a class="dropdown-item" href="logout.php">Log out</a>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
</div>
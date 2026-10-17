<?php
include_once("inc/header.php");
include_once("inc/db.php");

// Process reward addition
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_reward'])) {
    $courseID = $_POST['CourseID'];
    $points_required = $_POST['points_required'];

    if (!empty($courseID) && !empty($points_required)) {
        $stmt = $con->prepare("INSERT INTO rewards (CourseID, points_required) VALUES (?, ?)");
        if ($stmt->execute([$courseID, $points_required])) {
            $_SESSION['message'] = "Reward added successfully!";
        } else {
            $_SESSION['error'] = "Failed to add reward.";
        }
    } else {
        $_SESSION['error'] = "Please fill in all fields.";
    }
}

// Process reward deletion
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_reward'])) {
    $rewardID = $_POST['reward_id'];

    $stmt = $con->prepare("DELETE FROM rewards WHERE reward_id = ?");
    if ($stmt->execute([$rewardID])) {
        $_SESSION['message'] = "Reward deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete reward.";
    }
}

// Display success or error messages
if (isset($_SESSION['message'])) {
    echo "<div class='success'>" . $_SESSION['message'] . "</div>";
    unset($_SESSION['message']);
}

if (isset($_SESSION['error'])) {
    echo "<div class='error'>" . $_SESSION['error'] . "</div>";
    unset($_SESSION['error']);
}
?>

<div id="bodyright">
    <h3>Rewards Management</h3>
    <div id="add">
        <table cellspacing="0">
            <tr>
                <th>Reward Name</th>
                <th>Points Required</th>
                <th>Actions</th>
            </tr>
            <?php echo view_rewards(); ?>
        </table>
    </div>
</div>

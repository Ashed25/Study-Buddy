<?php
include("inc/db.php");

if (isset($_GET['reward_id'])) {
    $reward_id = $_GET['reward_id'];
    $query = "SELECT * FROM rewards WHERE reward_id = :reward_id";
    $stmt = $con->prepare($query);
    $stmt->bindParam(':reward_id', $reward_id);
    $stmt->execute();
    $reward = $stmt->fetch(PDO::FETCH_ASSOC);
}

if (isset($_POST['update_reward'])) {
    $reward_id = $_POST['reward_id'];
    $reward_name = $_POST['reward_name'];
    $points_required = $_POST['points_required'];
    
    try {
        // Handle Image Upload
        if (!empty($_FILES["reward_image"]["name"])) {
            $target_dir = "../uploads/";
            $reward_image = basename($_FILES["reward_image"]["name"]);
            $target_file = $target_dir . $reward_image;
            move_uploaded_file($_FILES["reward_image"]["tmp_name"], $target_file);
        } else {
            $reward_image = $reward['reward_image'];
        }

        $query = "UPDATE rewards SET reward_name = :reward_name, points_required = :points_required, reward_image = :reward_image WHERE reward_id = :reward_id";
        $stmt = $con->prepare($query);
        $stmt->bindParam(':reward_name', $reward_name);
        $stmt->bindParam(':points_required', $points_required);
        $stmt->bindParam(':reward_image', $reward_image);
        $stmt->bindParam(':reward_id', $reward_id);
        
        if ($stmt->execute()) {
            echo "<script>alert('Reward updated successfully!'); window.location.href='index.php?rewards';</script>";
            exit();
        } else {
            echo "<script>alert('Error updating reward.'); window.location.href='index.php?rewards';</script>";
        }
    } catch (PDOException $e) {
        // Handle database errors
        echo "<script>alert('Error: " . $e->getMessage() . "'); window.location.href='index.php?rewards';</script>";
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <input type="hidden" name="reward_id" value="<?php echo $reward['reward_id']; ?>">
    <label>Reward Name:</label>
    <input type="text" name="reward_name" value="<?php echo $reward['reward_name']; ?>" required><br>

    <label>Points Required:</label>
    <input type="number" name="points_required" value="<?php echo $reward['points_required']; ?>" required><br>

    <label>Reward Image:</label>
    <input type="file" name="reward_image">
    <br>
    <img src="../uploads/<?php echo $reward['reward_image']; ?>" width="100" height="100"><br>

    <button type="submit" name="update_reward">Update Reward</button>
</form>
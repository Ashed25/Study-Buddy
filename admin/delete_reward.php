<?php
session_start();
include_once("inc/db.php");

if (isset($_POST['delete_reward'])) {
    $reward_id = $_POST['reward_id'];

    try {
        $query = "DELETE FROM rewards WHERE reward_id = :reward_id";
        $stmt = $con->prepare($query);
        $stmt->bindParam(":reward_id", $reward_id, PDO::PARAM_INT);
        $stmt->execute();
        $_SESSION['message'] = "Reward deleted successfully!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }

    header("Location: rewards.php");
    exit();
}
?>
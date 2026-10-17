<?php
session_start();
include '../admin/inc/db.php';  // Ensure correct database connection
include 'function.php';

// Redirect if not logged in or not a student
if (!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] != 1) {
    header('Location: ../login.php');
    exit();
  }
  if ($_SESSION["UserType"] != "Tutor") {  
    header('Location: ../login.php');
    exit();
  }
$teacher_id = $_SESSION['UserID']; // Securely get logged-in teacher's ID
$withdraw_amount = $_POST['withdraw_amount'];
$name = htmlspecialchars($_POST['name']);
$bank_name = htmlspecialchars($_POST['bank_name']);
$bank_acc = htmlspecialchars($_POST['bank_acc']);

// Ensure database connection is available
global $con;
if (!$con) {
    die("Database connection failed.");
}

// Get the current balance
$query = "SELECT balance FROM users WHERE UID = :teacher_id";
$stmt = $con->prepare($query);
$stmt->bindParam(":teacher_id", $teacher_id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$current_balance = $row['balance'];

if (!$row) {
    echo "<script>alert('Error retrieving balance.'); window.location.href='fees.php';</script>";
    exit();
}

// Validate withdrawal amount
if ($withdraw_amount <= 0 || $withdraw_amount > $current_balance) {
    echo "<script>alert('Invalid withdrawal amount.'); window.location.href='fees.php';</script>";
    exit();
}

// Deduct balance
$updateQuery = "UPDATE users SET balance = balance - :withdraw_amount WHERE UserID = :teacher_id";
$stmt = $con->prepare($updateQuery);
$stmt->bindParam(":withdraw_amount", $withdraw_amount, PDO::PARAM_STR);
$stmt->bindParam(":teacher_id", $teacher_id, PDO::PARAM_INT);

if ($stmt->execute()) {
    // Store withdrawal request
    $paymentQuery = "INSERT INTO withdrawals (teacher_id, name, bank_name, bank_acc, amount, status) 
                     VALUES (:teacher_id, :name, :bank_name, :bank_acc, :amount, 'Pending')";
    $stmt = $con->prepare($paymentQuery);
    $stmt->bindParam(":teacher_id", $teacher_id, PDO::PARAM_INT);
    $stmt->bindParam(":name", $name, PDO::PARAM_STR);
    $stmt->bindParam(":bank_name", $bank_name, PDO::PARAM_STR);
    $stmt->bindParam(":bank_acc", $bank_acc, PDO::PARAM_STR);
    $stmt->bindParam(":amount", $withdraw_amount, PDO::PARAM_STR);
    $stmt->execute();

    echo "<script>alert('Withdrawal request submitted successfully!'); window.location.href='fees.php';</script>";

} else {
    echo "<script>alert('Error processing withdrawal!'); window.location.href='fees.php';</script>";
}
?>

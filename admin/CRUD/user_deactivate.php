<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: ../../login.php'); 
    exit;
}

if ($_SESSION['user_status'] == 1) {
    header('location: ../../index.php'); 
    exit;
}

require_once('../../config.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = "Invalid request: No user ID specified.";
    header('Location: ../userManagement.php');
    exit;
}

$user_id_to_deactivate = $_GET['id'];
$reason = $_GET['reason'];

$stmt = $mysqli->prepare("UPDATE users SET account_status = 'Inactive' WHERE id = ?");
$stmt->bind_param("i", $user_id_to_deactivate);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $_SESSION['success_message'] = "User has been successfully deactivated.";

        $action_taken = "Deactivate User";
        $user_id = $_SESSION['user_id'];

        $log_stmt = $mysqli->prepare("INSERT INTO user_logs(admin_id, action, affected_user, reason) VALUES (?, ?, ?, ?)");
        $log_stmt->bind_param("isis", $user_id, $action_taken, $user_id_to_deactivate, $reason);
        $log_stmt->execute();
        $log_stmt->close();
    } else {
        $_SESSION['error_message'] = "No user found with that ID, or deactivation failed.";
    }
} else {
    $_SESSION['error_message'] = "Error deactivate record: " . $mysqli->error;
}

$stmt->close();
$mysqli->close();

header('Location: ../userManagement.php'); 
exit;

?>
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

$user_id_to_delete = $_GET['id'];

$user_info_stmt = $mysqli->prepare("SELECT email FROM users WHERE id = ?");
$user_info_stmt->bind_param("i", $user_id_to_delete);
$user_info_stmt->execute();
$result = $user_info_stmt->get_result();

if ($result->num_rows === 1) {
    $user_to_delete = $result->fetch_assoc();
    $affected_user_email = $user_to_delete['email'];
} else {
    $_SESSION['error_message'] = "Cannot delete: User not found.";
    header('Location: ../userManagement.php');
    exit;
}
$user_info_stmt->close();

$user_id = $_SESSION['user_id'];

$stmt = $mysqli->prepare("DELETE FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id_to_delete);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $_SESSION['success_message'] = "User has been successfully deleted.";

        $action_taken = "Deleted User";
        $admin_username = $_SESSION['username']; 

        $log_stmt = $mysqli->prepare("INSERT INTO user_logs(admin_uname, action, affected_user) VALUES (?, ?, ?)");
        $log_stmt->bind_param("sss", $admin_username, $action_taken, $affected_user_email);
        $log_stmt->execute();
        $log_stmt->close();
    } else {
        $_SESSION['error_message'] = "No user found with that ID, or deletion failed.";
    }
} else {
    $_SESSION['error_message'] = "Error deleting record: " . $mysqli->error;
}

$stmt->close();
$mysqli->close();

header('Location: ../userManagement.php'); 
exit;

?>
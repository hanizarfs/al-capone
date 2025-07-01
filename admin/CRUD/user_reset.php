<?php
session_start();

// --- Security Guard Clauses ---
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: ../../login.php');
    exit;
}
if ($_SESSION['user_status'] == 1) { // Assuming status 1 is a regular user
    header('location: ../../index.php');
    exit;
}

// --- 1. VALIDATE THE INCOMING GET REQUEST ---
// Check if an ID was passed in the URL and if it's a number
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = "Invalid request.";
    header('Location: ../userManagement.php');
    exit;
}

include_once("../../config.php");

$user_id_to_reset = $_GET['id'];
$default_password = "123"; // The new default password

// --- 2. FETCH THE AFFECTED USER'S EMAIL FOR LOGGING ---
// We must do this BEFORE we change anything.
$user_info_stmt = $mysqli->prepare("SELECT email FROM users WHERE id = ?");
$user_info_stmt->bind_param("i", $user_id_to_reset);
$user_info_stmt->execute();
$result = $user_info_stmt->get_result();
if ($result->num_rows === 1) {
    $user_to_reset = $result->fetch_assoc();
    $affected_user_email = $user_to_reset['email']; // Now we have the email for logging
} else {
    $_SESSION['error_message'] = "Failed to reset: User not found.";
    header('Location: ../userManagement.php');
    exit;
}
$user_info_stmt->close();


// --- 3. PREPARE AND EXECUTE THE PASSWORD UPDATE ---
// Hash the new default password
$password_hash = password_hash($default_password, PASSWORD_DEFAULT);

// The CORRECT SQL UPDATE statement with two placeholders
$sql = "UPDATE users SET password_hash = ?, updated_at = NOW() WHERE id = ?";
$update_stmt = $mysqli->prepare($sql);

// Bind the two parameters: the new password hash (string) and the user ID (integer)
$update_stmt->bind_param("si", $password_hash, $user_id_to_reset);

if ($update_stmt->execute()) {
    $_SESSION['success_message'] = 'Password for ' . htmlspecialchars($affected_user_email) . ' has been reset successfully!';

    // --- 4. LOG THE ACTION ---
    $action_taken = "Reset User Password";
    $admin_username = $_SESSION['username']; // Get admin username from session

    $log_stmt = $mysqli->prepare("INSERT INTO user_logs(admin_uname, action, affected_user) VALUES (?, ?, ?)");
    // Use the $affected_user_email variable we fetched earlier
    $log_stmt->bind_param("sss", $admin_username, $action_taken, $affected_user_email);
    $log_stmt->execute();
    $log_stmt->close();

} else {
    $_SESSION['error_message'] = 'Failed to reset user password. Please try again.';
}

$update_stmt->close();
$mysqli->close();

// Redirect back to the user list page
header('Location: ../userManagement.php');
exit();

?>
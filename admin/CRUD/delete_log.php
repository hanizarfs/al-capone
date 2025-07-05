<?php
session_start();

// --- Security Guard Clauses ---
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: ../../login.php');
    exit;
}
if ($_SESSION['user_status'] == 1) { // Assuming 0 is the admin status
    header('location: ../../index.php');
    exit;
}

// Check if the log_type parameter was sent
if (!isset($_GET['log_type'])) {
    $_SESSION['error_message'] = "Invalid request.";
    header('Location: ../logs/index.php'); // Redirect back to the logs page
    exit;
}

require_once('../../config.php');

$log_type = $_GET['log_type'];
$table_to_clear = '';

// Determine which table to truncate based on the parameter
switch ($log_type) {
    case 'user':
        $table_to_clear = 'user_logs';
        $_SESSION['success_message'] = 'All user logs have been cleared successfully.';
        break;
    case 'cancellation':
        $table_to_clear = 'cancellation_logs';
        $_SESSION['success_message'] = 'All cancellation logs have been cleared successfully.';
        break;
    default:
        // If the type is unknown, set an error and exit
        $_SESSION['error_message'] = "Unknown log type specified.";
        header('Location: ../logs/index.php');
        exit;
}

// Use TRUNCATE TABLE to reset the table completely. It's faster than DELETE.
$sql = "TRUNCATE TABLE " . $table_to_clear;

if ($mysqli->query($sql)) {
    // The success message was already set above
} else {
    // If the query fails, set an error message
    $_SESSION['error_message'] = "Failed to clear logs: " . $mysqli->error;
}

$mysqli->close();

// Redirect back to the logs page
header('Location: ../logs/index.php');
exit;
?>

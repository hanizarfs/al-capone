<?php

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: ../../login.php'); 
    exit;
}

require_once('../../config.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = "Invalid request: No booking ID specified.";
    header('Location: ../dashboard.php');
    exit;
}

$booking_id_to_appealed = $_GET['id'];
$reason = $_GET['reason'];

$stmt = $mysqli->prepare("UPDATE bookings SET appeal_reason = ? WHERE id = ?");
$stmt->bind_param("si", $reason, $booking_id_to_appealed);

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {
        $_SESSION['success_message'] = "Bookings Cancel has been successfully appealed.";

        $action_taken = "Deactivate User";
        $user_id = $_SESSION['user_id'];

        $log_stmt = $mysqli->prepare("INSERT INTO booking_cancellations(booking_id, user_id, reason) VALUES (?, ?, ?)");
        $log_stmt->bind_param("iis", $booking_id_to_appealed, $user_id, $reason);
        $log_stmt->execute();
        $log_stmt->close();
    } else {
        $_SESSION['error_message'] = "No booking found with that ID, or appeal failed.";
    }
} else {
    $_SESSION['error_message'] = "Error appeal cancel record: " . $mysqli->error;
}

$stmt->close();
$mysqli->close();

header('Location: ../dashboard.php'); 
exit;


?>
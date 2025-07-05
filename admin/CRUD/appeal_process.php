<?php

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: ../../login.php');
    exit;
}

require_once('../../config.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error_message'] = "Invalid request: No booking ID specified.";
    header('Location: ../cancellation-requests/index.php');
    exit;
}

$booking_id_to_processed = $_GET['id'];
$appeal_id_to_processed = $_GET['appealId'];
$reason = $_GET['reason'];
$type = $_GET['type'];

if ($type == "rejected") {
    $stmt = $mysqli->prepare("UPDATE bookings SET rejected_reason = ? WHERE id = ?");
    $stmt->bind_param("si", $reason, $booking_id_to_processed);
} else {
    $stmt = $mysqli->prepare("UPDATE bookings SET status = 'Inactive' WHERE id = ?");
    $stmt->bind_param("i", $booking_id_to_processed);
}

if ($stmt->execute()) {
    if ($stmt->affected_rows > 0) {

        if ($type == "rejected") {
            $action_taken = "Rejected";
            $appeal_stmt = $mysqli->prepare("UPDATE booking_cancellations SET status = 'Rejected' WHERE id = ?");
            $appeal_stmt->bind_param("i", $appeal_id_to_processed);
            $appeal_stmt->execute();
            $appeal_stmt->close();
        } else {
            $action_taken = "Approved";
            $appeal_stmt = $mysqli->prepare("UPDATE booking_cancellations SET status = 'Approved' WHERE id = ?");
            $appeal_stmt->bind_param("i", $appeal_id_to_processed);
            $appeal_stmt->execute();
            $appeal_stmt->close();
        }
        $admin_id = $_SESSION['user_id'];

        $log_stmt = $mysqli->prepare("INSERT INTO cancellation_logs(admin_id, action, affected_booking, reason, cancel_id) VALUES (?, ?, ?, ?, ?)");
        $log_stmt->bind_param("isisi", $admin_id, $action_taken, $booking_id_to_processed, $reason, $appeal_id_to_processed);
        $log_stmt->execute();
        $log_stmt->close();
        $_SESSION['success_message'] = "Bookings Process has been successfully done.";
    } else {
        $_SESSION['error_message'] = "No booking found with that ID, or appeal failed.";
    }
} else {
    $_SESSION['error_message'] = "Error appeal cancel record: " . $mysqli->error;
}

$stmt->close();
$mysqli->close();

header('Location: ../cancellation-requests/index.php');
exit;

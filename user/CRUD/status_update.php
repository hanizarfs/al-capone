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

// Assume config.php is included and security checks are done above this.
require_once('../../config.php'); 

$booking_id_to_update = $_GET['id'];
$type = $_GET['type'];

if ($type == "checkin") {
    $checkin_date = $_GET['checkin'];
    
    // --- THE FIX: Use PHP's date() function to get today's date ---
    $today = date('Y-m-d');

    // Now, compare the two date strings. This works correctly.
    if ($today < $checkin_date) {
        // It's not the check-in day yet.
        $_SESSION['error_message'] = "It's not check-in time yet. Please wait until " . date('d M Y', strtotime($checkin_date));
        header('Location: ../dashboard.php');
        exit();
    } else {
        // It's the check-in day or later, proceed with the update.
        $stmt = $mysqli->prepare("UPDATE bookings SET status = 'Ongoing' WHERE id = ?");
        $stmt->bind_param("i", $booking_id_to_update);
        $stmt->execute();
        $stmt->close();
        
        $_SESSION['success_message'] = "Successfully Checked-in! Enjoy your stay.";
        header('Location: ../dashboard.php');
        exit();
    }
} else {
    // This part handles checkout. We'll set the status to 'Completed'.
    // Using 'Completed' is better for historical records than 'Inactive'.
    $stmt = $mysqli->prepare("UPDATE bookings SET status = 'Completed' WHERE id = ?");
    $stmt->bind_param("i", $booking_id_to_update);
    $stmt->execute();
    $stmt->close();
    
    $_SESSION['success_message'] = "Successfully Checked-out! We hope to see you again.";
    header('Location: ../dashboard.php');
    exit();
}
?>

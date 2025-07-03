<?php
session_start();

// Security: Ensure user is logged in and form was submitted
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SERVER["REQUEST_METHOD"] !== "POST") {
    header('location: ../login.php'); 
    exit;
}

include_once("../config.php");

// Get all data from the invoices.php form
$invoice_id = trim($_POST['invoice_id']);
$user_id = (int)$_POST['user_id'];
$room_type = trim($_POST['room_type']);
$checkin_date = trim($_POST['checkin_date']);
$checkout_date = trim($_POST['checkout_date']);
$subtotal = (float)$_POST['subtotal']; // Added subtotal from the form
$grand_total = (float)$_POST['grand_total'];
$payment_method = trim($_POST['payment_method']);

// The INSERT statement now includes the `subtotal` column to match your table structure
$insert_stmt = $mysqli->prepare(
    "INSERT INTO booking_logs(invoice_id, user_id, room_type, checkin_date, checkout_date, subtotal, grand_total, payment_method) 
     VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
);

// The bind_param string is now "sisssdds" for the 8 columns
$insert_stmt->bind_param("sisssdds", $invoice_id, $user_id, $room_type, $checkin_date, $checkout_date, $subtotal, $grand_total, $payment_method);

if ($insert_stmt->execute()) {
    // --- THIS IS THE NEW LOGIC ---
    // 1. Set the success message for the SweetAlert
    $_SESSION['success_message'] = 'Payment Success! Your booking is confirmed.';
    
    // 2. Store all the original POST data so the invoice page can redisplay it
    $_SESSION['invoice_data'] = $_POST;
    
    // 3. Redirect back to the invoice page
    header('Location: ../invoice.php');
    exit();

} else {
    // On failure, redirect back to the booking page with an error
    $_SESSION['error_message'] = 'Booking Failed! Please try again.';
    // Also store the data on failure so the form can be re-populated if needed
    $_SESSION['invoice_data'] = $_POST;
    header('Location: ../invoice.php');
    exit();
}

$insert_stmt->close();
$mysqli->close();
?>

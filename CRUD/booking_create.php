<?php 
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: login.php'); 
    exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    // If accessed directly, redirect to the rooms page
    header('Location: rooms.php');
    exit;
}

include_once("../config.php");


$invoice_id = trim($_POST['invoice_id']);
$user_id = trim($_POST['user_id']);
$room_id = trim($_POST['room_id']);
$checkin_date = trim($_POST['checkin_date']);
$checkout_date = trim($_POST['checkout_date']);
$subtotal = trim($_POST['subtotal']);
$grand_total = trim($_POST['grand_total']);

$insert_stmt = $mysqli->prepare("INSERT INTO booking_logs(invoice_id, user_id, room_type, checkin_date, checkout_date, subtotal, grand_total, payment) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$insert_stmt->bind_param("sssssdds", $invoice_id, $user_id, $room_type, $checkin_date, $checkout_date, $subtotal, $grand_total, $payment_method);

if($insert_stmt->execute()){
    $_SESSION['success_message'] = 'Payment Success!';
    header('Location: ../invoice.php');
    exit();
}else{
    $_SESSION['error_message'] = 'Payment Failed!';
    header('Location: ../invoice.php');
    exit();
}

$insert_stmt->close();

$mysqli->close();
exit();

?>
<?php
session_start();

require_once("../config.php");

$booking_id = $_GET['id'];

$sql = "
    SELECT 
        b.checkin_date, 
        b.checkout_date, 
        b.subtotal, 
        b.grand_total, 
        b.payment_method,
        b.invoice_id,
        b.booking_timestamp,
        
        u.first_name, 
        u.last_name, 
        u.email AS user_email, 
        u.phone AS user_phone,
        
        r.name AS room_name,
        r.price AS price_per_night

    FROM 
        bookings AS b
    LEFT JOIN 
        users AS u ON b.user_id = u.id
    LEFT JOIN 
        rooms AS r ON b.room_type = r.id
    WHERE 
        b.id = ? 
";

$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$result = $stmt->get_result();
$booking_info = $result->fetch_assoc();

// --- Retrieve all data from our chosen $source_data array ---
$room_name = $booking_info['room_name'];
$price_per_night = (float)$booking_info['price_per_night'];
$subtotal = (float)$booking_info['subtotal'];
$grand_total = (float)$booking_info['grand_total'];
$checkin_date = $booking_info['checkin_date'];
$checkout_date = $booking_info['checkout_date'];
$payment_method = $booking_info['payment_method'];
$invoice_id = $booking_info['invoice_id'];
$timestamp_from_db = $booking_info['booking_timestamp'];
$formatted_date = date('d M Y', strtotime($timestamp_from_db));

// Combine first and last name for the user's full name
$user_full_name = $booking_info['first_name'] . ' ' . $booking_info['last_name'];
$user_email = $booking_info['user_email'];
$user_phone = $booking_info['user_phone'];

// Calculate the number of nights
$checkin = new DateTime($checkin_date);
$checkout = new DateTime($checkout_date);
$interval = $checkin->diff($checkout);
$total_nights = $interval->days;

const HOTEL_TAX_RATE = 0.10;
const PPN_RATE = 0.11;

$hotel_tax = $subtotal * HOTEL_TAX_RATE;
$ppn = $subtotal * PPN_RATE;

// The $user array now correctly holds all the user info.

$stmt->close();
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Invoice & Payment | Al Capone</title>
    <link rel="icon" type="image/x-icon" href="./assets/img/Logo.webp" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" />

    <!-- CSS -->
    <link rel="stylesheet" href="./assets/css/style.css" />
</head>

<body>

    <!-- Start Main Invoice Section -->
    <main>

        <section id="invoice" class="py-5 bg-light">
            <div class="container">

                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <a
                            href="dashboard.php"
                            class="btn btn-outline-secondary mb-3">
                            <i class="bi bi-arrow-left"></i>
                            Go Back
                        </a>
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-dark text-white">
                                <h2 class="mb-0">Invoice</h2>
                            </div>
                            <div class="card-body p-4">
                                <!-- Invoice Header -->
                                <div class="row mb-4">
                                    <div class="col-sm-6">
                                        <h5 class="mb-3">Billed To:</h5>
                                        <p class="mb-1"><strong><?= htmlspecialchars($user_full_name) ?></strong></p>
                                        <p class="mb-1"><?= htmlspecialchars($user_email) ?></p>
                                        <p class="mb-1"><?= htmlspecialchars($user_phone) ?></p>
                                    </div>
                                    <div class="col-sm-6 text-sm-end">
                                        <p class="mb-1"><strong>Invoice ID:</strong> <?= htmlspecialchars($invoice_id) ?></p>
                                        <p class="mb-1"><strong>Date:</strong> <?= htmlspecialchars($formatted_date) ?></p>
                                    </div>
                                </div>

                                <!-- Booking Details -->
                                <div class="table-responsive mb-4">
                                    <table class="table table-bordered">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Description</th>
                                                <th class="text-center">Dates</th>
                                                <th class="text-end">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <strong><?= htmlspecialchars($room_name) ?></strong><br>
                                                    <small class="text-muted">(<?= htmlspecialchars($total_nights) ?> nights at Rp <?= number_format($price_per_night, 0, ',', '.') ?>)</small>
                                                </td>
                                                <td class="text-center align-middle"><?= date('d M', strtotime($checkin_date)) ?> - <?= date('d M Y', strtotime($checkout_date)) ?></td>
                                                <td class="text-end">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Price Breakdown -->
                                <div class="row justify-content-end">
                                    <div class="col-md-6">
                                        <div class="table-responsive">
                                            <table class="table table-sm">
                                                <tbody>
                                                    <tr>
                                                        <th class="fw-normal">Subtotal</th>
                                                        <td class="text-end">Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="fw-normal">Hotel Tax (10%)</th>
                                                        <td class="text-end">Rp <?= number_format($hotel_tax, 0, ',', '.') ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="fw-normal">PPN (11%)</th>
                                                        <td class="text-end">Rp <?= number_format($ppn, 0, ',', '.') ?></td>
                                                    </tr>
                                                    <tr class="fs-5">
                                                        <th class="fw-bold">Grand Total</th>
                                                        <td class="text-end fw-bold text-primary">Rp <?= number_format($grand_total, 0, ',', '.') ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="fw-normal">Has been paid with</th>
                                                        <td class="text-end"><?= htmlspecialchars($payment_method) ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th class="fw-normal">on:</th>
                                                        <td class="text-end"><?= htmlspecialchars($timestamp_from_db) ?></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- End Main Invoice Section -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Main JS -->
    <script src="./assets/js/main.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>
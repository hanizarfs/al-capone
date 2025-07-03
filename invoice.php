<?php
session_start();

// --- NEW LOGIC TO HANDLE DATA FROM SESSION OR POST ---

$source_data = []; // An empty array to hold our data source

// Check if we are returning from the booking creation script
if (isset($_SESSION['invoice_data'])) {
    // If yes, use the data stored in the session
    $source_data = $_SESSION['invoice_data'];
    // IMPORTANT: Unset the session data so it's not reused on a page refresh
    unset($_SESSION['invoice_data']);
} 
// Else, check if this is the first visit from booking.php
else if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // If yes, use the fresh data from the POST request
    $source_data = $_POST;
} 
// If there's no data from session or post, the page was accessed incorrectly
else {
    header('Location: rooms.php');
    exit;
}

// --- Now, retrieve all data from our chosen $source_data array ---
$room_id = htmlspecialchars($source_data['room_id'] ?? 'N/A');
$room_name = htmlspecialchars($source_data['room_name'] ?? 'N/A');
$price_per_night = (float)($source_data['price_per_night'] ?? 0);
$total_nights = (int)($source_data['total_nights'] ?? 0);
$subtotal = (float)($source_data['total_price'] ?? 0);

$full_name = htmlspecialchars($source_data['full_name'] ?? '');
$email = htmlspecialchars($source_data['email'] ?? '');
$phone = htmlspecialchars($source_data['phone'] ?? '');
$checkin_date = htmlspecialchars($source_data['checkin_date'] ?? '');
$checkout_date = htmlspecialchars($source_data['checkout_date'] ?? '');

// If essential data is still missing, redirect
if ($total_nights <= 0 || $subtotal <= 0) {
    header('Location: rooms.php');
    exit;
}

// --- CALCULATE TAXES AND GRAND TOTAL (This part remains the same) ---
const HOTEL_TAX_RATE = 0.10;
const PPN_RATE = 0.11;

$hotel_tax = $subtotal * HOTEL_TAX_RATE;
$ppn = $subtotal * PPN_RATE;
$grand_total = $subtotal + $hotel_tax + $ppn;

// Use the invoice_id from the source data, or generate a new one if it doesn't exist yet
$invoice_id = htmlspecialchars($source_data['invoice_id'] ?? 'INV-' . strtoupper(uniqid()));



?>
<!-- The rest of your invoices.php HTML file remains exactly the same -->
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
        <!-- Start Navbar -->
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container">
                <a class="navbar-brand fw-semibold d-flex justify-content-center align-items-center" href="index.php">
                    <img src="./assets/img/Logo.webp" alt="Logo" width="30" height="30" />
                    <span class="ms-2"> Al Capone </span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                        <li class="nav-item"><a class="nav-link active" href="rooms.php">Rooms</a></li>
                        <li class="nav-item"><a class="nav-link" href="gallery.php">Gallery</a></li>
                        <li class="nav-item"><a class="nav-link" href="faq.php">FAQ</a></li>
                    </ul>
                    <div class="d-flex justify-content-center align-items-center">
                        <a href="login.php" class="btn bg-blue"> Login </a>
                    </div>
                </div>
            </div>
        </nav>
        <!-- End Navbar -->

        <!-- Start Main Invoice Section -->
        <main>
            <section id="invoice" class="py-5 bg-light">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-dark text-white">
                                    <h2 class="mb-0">Invoice</h2>
                                </div>
                                <div class="card-body p-4">
                                    <!-- Invoice Header -->
                                    <div class="row mb-4">
                                        <div class="col-sm-6">
                                            <h5 class="mb-3">Billed To:</h5>
                                            <p class="mb-1"><strong><?= htmlspecialchars($_SESSION['first_name']. '' . $_SESSION['last_name']) ?></strong></p>
                                            <p class="mb-1"><?= htmlspecialchars($_SESSION['email']) ?></p>
                                            <p class="mb-1"><?= htmlspecialchars($_SESSION['phone']) ?></p>
                                        </div>
                                        <div class="col-sm-6 text-sm-end">
                                            <p class="mb-1"><strong>Invoice ID:</strong> <?= $invoice_id ?></p>
                                            <p class="mb-1"><strong>Date:</strong> <?= date('d M Y') ?></p>
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
                                                        <strong><?= $room_name ?></strong><br>
                                                        <small class="text-muted">(<?= $total_nights ?> nights at Rp <?= number_format($price_per_night, 0, ',', '.') ?>)</small>
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
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>

                                    <!-- Payment Form -->
                                    <form action="CRUD/booking_create.php" method="post">
                                        <!-- Pass all data again as hidden fields to the final creation script -->
                                        <input type="hidden" name="invoice_id" value="<?= $invoice_id ?>">
                                        <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                        <input type="hidden" name="room_id" value="<?= $room_id ?>">
                                        <input type="hidden" name="checkin_date" value="<?= $checkin_date ?>">
                                        <input type="hidden" name="checkout_date" value="<?= $checkout_date ?>">
                                        <input type="hidden" name="subtotal" value="<?= $subtotal ?>">
                                        <input type="hidden" name="grand_total" value="<?= $grand_total ?>">

                                        <h4 class="mb-3">Select Payment Method</h4>
                                        <div class="list-group">
                                            <label class="list-group-item d-flex gap-2">
                                                <input class="form-check-input flex-shrink-0" type="radio" name="payment_method" id="payment_bca" value="BCA Virtual Account" checked>
                                                <span>
                                                    BCA Virtual Account
                                                    <small class="d-block text-muted">Pay securely using your BCA mobile app or ATM.</small>
                                                </span>
                                            </label>
                                            <label class="list-group-item d-flex gap-2">
                                                <input class="form-check-input flex-shrink-0" type="radio" name="payment_method" id="payment_mandiri" value="Mandiri Virtual Account">
                                                <span>
                                                    Mandiri Virtual Account
                                                    <small class="d-block text-muted">Pay with your Livin' by Mandiri app or ATM.</small>
                                                </span>
                                            </label>
                                            <label class="list-group-item d-flex gap-2">
                                                <input class="form-check-input flex-shrink-0" type="radio" name="payment_method" id="payment_paypal" value="PayPal">
                                                <span>
                                                    PayPal
                                                    <small class="d-block text-muted">Pay with your PayPal balance or linked card.</small>
                                                </span>
                                            </label>
                                        </div>

                                        <div class="d-grid mt-4">
                                            <button type="submit" class="btn bg-blue btn-lg">Confirm and Pay Rp <?= number_format($grand_total, 0, ',', '.') ?></button>
                                        </div>
                                    </form>
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
    </body>
</html>

<?php
session_start();

// --- 1. VALIDATE THE REQUEST & FETCH ROOM DATA ---

// Include your database configuration file
require_once('config.php');

// Security: Check if a room_id was passed in the URL. If not, redirect back to the rooms page.
if (!isset($_GET['room_id']) || empty($_GET['room_id'])) {
    // You can optionally set an error message in the session here
    header('Location: rooms.php');
    exit;
}

$room_id = $_GET['room_id'];

// Use a prepared statement to prevent SQL injection and fetch the selected room's details
$stmt = $mysqli->prepare("SELECT id, name, price, description FROM rooms WHERE id = ?");
$stmt->bind_param("s", $room_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if a room with that ID was actually found
if ($result->num_rows === 1) {
    $room = $result->fetch_assoc();
} else {
    // No room found with that ID. Redirect with an error.
    // You can set an error message here.
    header('Location: rooms.php');
    exit;
}

$stmt->close();
$mysqli->close();
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Book Your Stay | Al Capone</title>
        <link rel="icon" type="image/x-icon" href="./assets/img/Logo.webp" />

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />

        <!-- Bootstrap Icon -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" />

        <!-- CSS -->
        <link rel="stylesheet" href="./assets/css/style.css" />
        <style>
            .summary-card {
                position: sticky;
                top: 20px; /* Keeps the card visible when scrolling */
            }
        </style>
    </head>
    <body>
        <!-- Start Navbar (Copied from your index.php for consistency) -->
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

        <!-- Start Main Booking Section -->
        <main>
            <section id="booking" class="py-5">
                <div class="container">
                    <div class="row">
                        <!-- Left Column: Room Details & Booking Form -->
                        <div class="col-lg-8">
                            <h1 class="fw-bold mb-3">Confirm Your Booking</h1>
                            <hr>
                            
                            <!-- Room Details -->
                            <div class="room-details mb-4">
                                <h3 class="text-primary"><?= htmlspecialchars($room['name']); ?></h3>
                                <img src="https://placehold.co/1200x600/EEE/31343C?text=<?= urlencode($room['name']); ?>" class="img-fluid rounded mb-3" alt="<?= htmlspecialchars($room['name']); ?>">
                                <p class="text-muted"><?= htmlspecialchars($room['description']); ?></p>
                            </div>
                            <hr>

                            <!-- Booking Form -->
                            <form action="invoices.php" method="post" id="booking-form">
                                <!-- Hidden fields to pass crucial data to the next page -->
                                <input type="hidden" name="room_id" value="<?= htmlspecialchars($room['id']); ?>">
                                <input type="hidden" name="room_name" value="<?= htmlspecialchars($room['name']); ?>">
                                <input type="hidden" name="price_per_night" value="<?= htmlspecialchars($room['price']); ?>">
                                <input type="hidden" name="total_nights" id="form-total-nights" value="0">
                                <input type="hidden" name="total_price" id="form-total-price" value="0">

                                <h4 class="mt-4 mb-3">Select Your Dates</h4>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="checkin_date" class="form-label">Check-in Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="checkin_date" name="checkin_date" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="checkout_date" class="form-label">Check-out Date <span class="text-danger">*</span></label>
                                        <input type="date" class="form-control" id="checkout_date" name="checkout_date" required>
                                    </div>
                                </div>

                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn bg-blue btn-lg">Book & Proceed to Payment</button>
                                </div>
                            </form>
                        </div>

                        <!-- Right Column: Booking Summary -->
                        <div class="col-lg-4">
                            <div class="card shadow-sm summary-card">
                                <div class="card-body">
                                    <h5 class="card-title fw-bold border-bottom pb-3 mb-3">Booking Summary</h5>
                                    <div class="d-flex justify-content-between">
                                        <p class="mb-1 fw-semibold">Room:</p>
                                        <p class="mb-1"><?= htmlspecialchars($room['name']); ?></p>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <p class="mb-1 fw-semibold">Price per night:</p>
                                        <p class="mb-1">Rp <?= number_format($room['price'], 0, ',', '.'); ?></p>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between">
                                        <p class="mb-1 fw-semibold">Quantity (Nights):</p>
                                        <p class="mb-1" id="summary-nights">--</p>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <p class="mb-1 fw-semibold">Subtotal:</p>
                                        <p class="mb-1" id="summary-subtotal">--</p>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="mb-0 fw-bold fs-5">Total Price:</p>
                                        <p class="mb-0 fw-bold fs-5 text-primary" id="summary-total">--</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <!-- End Main Booking Section -->

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Main JS (for dark mode toggle, etc.) -->
        <script src="./assets/js/main.js"></script>

        <!-- Custom JS for Price Calculation -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const checkinInput = document.getElementById('checkin_date');
                const checkoutInput = document.getElementById('checkout_date');
                const summaryNights = document.getElementById('summary-nights');
                const summarySubtotal = document.getElementById('summary-subtotal');
                const summaryTotal = document.getElementById('summary-total');
                const formTotalNights = document.getElementById('form-total-nights');
                const formTotalPrice = document.getElementById('form-total-price');

                // Get the price per night from the PHP variable
                const pricePerNight = <?= (float)$room['price']; ?>;

                // Set minimum date for check-in to today
                const today = new Date().toISOString().split('T')[0];
                checkinInput.setAttribute('min', today);

                function updateBookingSummary() {
                    const checkinDate = new Date(checkinInput.value);
                    const checkoutDate = new Date(checkoutInput.value);

                    if (checkinInput.value && checkoutInput.value && checkoutDate > checkinDate) {
                        // Calculate the difference in time (in milliseconds)
                        const timeDiff = checkoutDate.getTime() - checkinDate.getTime();
                        
                        // Convert milliseconds to days (nights)
                        const nights = Math.ceil(timeDiff / (1000 * 3600 * 24));
                        
                        const subtotal = nights * pricePerNight;

                        // Display the calculated values
                        summaryNights.textContent = nights;
                        summarySubtotal.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
                        summaryTotal.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');

                        // Update the hidden form fields
                        formTotalNights.value = nights;
                        formTotalPrice.value = subtotal;

                    } else {
                        // Reset if dates are invalid
                        summaryNights.textContent = '--';
                        summarySubtotal.textContent = '--';
                        summaryTotal.textContent = '--';
                        formTotalNights.value = 0;
                        formTotalPrice.value = 0;
                    }
                }

                // Add event listeners to both date inputs
                checkinInput.addEventListener('change', function() {
                    // Set minimum date for checkout to be one day after check-in
                    const nextDay = new Date(checkinInput.value);
                    nextDay.setDate(nextDay.getDate() + 1);
                    checkoutInput.setAttribute('min', nextDay.toISOString().split('T')[0]);
                    updateBookingSummary();
                });
                checkoutInput.addEventListener('change', updateBookingSummary);
            });
        </script>
    </body>
</html>

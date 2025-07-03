<?php
require_once('config.php');

// Ambil data rooms
$sql = "SELECT * FROM rooms ORDER BY price ASC";
$result = $mysqli->query($sql);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Rooms - Al Capone Resort</title>
    <link rel="icon" href="./assets/img/Logo.webp" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" />
    <style>
        .hero-section {
            background-image: url('https://source.unsplash.com/1600x900/?hotel,resort');
            background-size: cover;
            background-position: center;
            height: 75vh;
            position: relative;
            color: white;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: rgba(0, 0, 0, 0.5);
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }
    </style>
</head>

<body>

    <!-- Hero Section -->
    <section class="hero-section d-flex align-items-center justify-content-center text-center">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <h1 class="display-4 fw-bold">Explore Our Rooms</h1>
            <p class="lead">Luxury, Comfort, and Convenience</p>
            <a href="#room-list" class="btn btn-light btn-lg">See Available Rooms</a>
        </div>
    </section>

    <!-- Room List -->
    <main>
        <section id="room-list" class="py-5 bg-light">
            <div class="container">
                <div class="text-center mb-5">
                    <h2 class="fw-bold">Our Available Rooms</h2>
                    <p class="text-muted">Choose the best room that suits your need and enjoy your stay with us.</p>
                </div>

                <div class="row">
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($room = $result->fetch_assoc()): ?>
                            <div class="col-lg-4 col-md-6 mb-4 d-flex align-items-stretch">
                                <div class="card shadow-sm border-0 w-100 h-100">
                                    <img src="https://source.unsplash.com/600x400/?hotel,<?= urlencode($room['name']) ?>" class="card-img-top" alt="<?= htmlspecialchars($room['name']); ?>">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title fw-bold"><?= htmlspecialchars($room['name']); ?></h5>
                                        <p class="text-primary fw-bold fs-5">Rp <?= number_format($room['price'], 0, ',', '.'); ?> / night</p>
                                        <p class="text-muted small"><?= htmlspecialchars($room['description']); ?></p>
                                        <p>
                                            <span class="badge <?= $room['availability'] > 0 ? 'bg-success' : 'bg-danger' ?>">
                                                <?= $room['availability'] > 0 ? $room['availability'] . ' Available' : 'Fully Booked' ?>
                                            </span>
                                        </p>
                                        <div class="mt-auto">
                                            <?php if ($room['availability'] > 0): ?>
                                                <a href="booking.php?room_id=<?= $room['id'] ?>" class="btn btn-outline-primary w-100">Book Now</a>
                                            <?php else: ?>
                                                <button class="btn btn-secondary w-100" disabled>Not Available</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <div class="col-12">
                            <div class="alert alert-warning text-center">
                                No rooms available at the moment. Please check back later.
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </section>
    </main>

    <!-- Facilities Section -->
    <section class="py-5 bg-white">
        <div class="container text-center">
            <h2 class="fw-bold mb-4">Facilities</h2>
            <div class="row g-4">
                <div class="col-md-3"><i class="bi bi-wifi fs-2 text-primary"></i>
                    <p>Free Wi-Fi</p>
                </div>
                <div class="col-md-3"><i class="bi bi-cup-hot fs-2 text-primary"></i>
                    <p>Restaurant</p>
                </div>
                <div class="col-md-3"><i class="bi bi-spa fs-2 text-primary"></i>
                    <p>Spa & Wellness</p>
                </div>
                <div class="col-md-3"><i class="bi bi-car-front fs-2 text-primary"></i>
                    <p>Free Parking</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white text-center">
        <div class="container">
            <h2 class="fw-bold mb-3">Ready to Book Your Stay?</h2>
            <p class="mb-4">Choose your perfect room and make your reservation now!</p>
            <a href="booking.php" class="btn btn-light btn-lg">Book Now</a>
        </div>
    </section>

    <!-- Reviews Section -->
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="fw-bold mb-4">What Our Guests Say</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="bg-white p-4 rounded shadow-sm h-100">
                        <p class="fst-italic">“Absolutely beautiful resort! The rooms were spotless, the staff was amazing. Will definitely return!”</p>
                        <h6 class="fw-bold mt-3">– Clara P.</h6>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white p-4 rounded shadow-sm h-100">
                        <p class="fst-italic">“Best vacation experience we've ever had. The view from our room was breathtaking.”</p>
                        <h6 class="fw-bold mt-3">– Richard G.</h6>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="bg-white p-4 rounded shadow-sm h-100">
                        <p class="fst-italic">“Great value for money. Clean facilities, excellent food, and relaxing atmosphere.”</p>
                        <h6 class="fw-bold mt-3">– Yuki M.</h6>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p class="mb-0">&copy; <?= date('Y') ?> Al Capone Resort. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
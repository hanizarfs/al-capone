<?php
// --- 1. DATABASE CONNECTION & DATA FETCHING ---

// Include your database configuration file
// Make sure the path is correct relative to this rooms.php file.
require_once('config.php');

// Prepare the SQL query to get all rooms, ordered by price
$sql = "SELECT id, name, price, description FROM rooms ORDER BY price ASC";

// Execute the query
$result = $mysqli->query($sql);

// The $result variable now holds the room data from the database.
// We will loop through it in the HTML section below.
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Our Rooms | Al Capone</title>
    <link rel="icon" type="image/x-icon" href="./assets/img/Logo.webp" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" />

    <!-- CSS -->
    <link rel="stylesheet" href="./assets/css/style.css" />

    <!-- Custom Style for Button Hover Effect -->
    <style>
        .btn-outline-blue {
            --bs-btn-color: #0d6efd;
            --bs-btn-border-color: #0d6efd;
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: #0d6efd;
            --bs-btn-hover-border-color: #0d6efd;
            --bs-btn-focus-shadow-rgb: 13, 110, 253;
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: #0d6efd;
            --bs-btn-active-border-color: #0d6efd;
        }
    </style>
</head>

<body>
    <!-- Start Navbar (Your existing navbar code goes here) -->
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
                    <!-- Theme Toggle can go here -->
                    <a href="login.php" class="btn bg-blue"> Login </a>
                </div>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->

    <!-- Start Main -->
    <main>
        <section id="rooms" class="py-5 bg-light">
            <div class="container">
                <div class="row text-center mb-5">
                    <div class="col">
                        <h1 class="fw-bold">Explore Our Rooms</h1>
                        <p class="lead text-muted">Find the perfect space for your stay. We offer a variety of rooms to suit your needs, each designed for your comfort and convenience.</p>
                    </div>
                </div>

                <div class="row">
                    <!-- --- 2. PHP LOOP TO GENERATE CARDS --- -->
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($room = $result->fetch_assoc()): ?>
                            <div class="col-lg-3 col-md-6 mb-4 d-flex align-items-stretch">
                                <div class="card h-100 w-100 shadow-sm border-0">
                                    <!-- In a real app, you'd have an image column in your DB. For now, we use a placeholder. -->
                                    <img src="https://placehold.co/600x400/EEE/31343C?text=<?= urlencode($room['name']); ?>" class="card-img-top" alt="<?= htmlspecialchars($room['name']); ?>">

                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title fw-bold"><?= htmlspecialchars($room['name']); ?></h5>
                                        <p class="card-text text-primary fw-bold fs-5 mb-2">Rp <?= number_format($room['price'], 0, ',', '.'); ?> / night</p>
                                        <p class="card-text small text-muted"><?= htmlspecialchars($room['description']); ?></p>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <!-- This message is shown if no rooms are found in the database -->
                        <div class="col-12">
                            <div class="alert alert-warning text-center" role="alert">
                                We're sorry, there are currently no rooms available. Please check back later.
                            </div>
                        </div>
                    <?php endif; ?>
                    <!-- --- END OF PHP LOOP --- -->
                </div>
            </div>
        </section>
    </main>
    <!-- End Main -->

    <footer class="py-4 mt-auto">
        <div class="container text-center">
            <p class="text-muted mb-0">&copy; <?= date('Y'); ?> Al Capone Hotel. All Rights Reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Main JS (for dark mode toggle, etc.) -->
    <script src="./assets/js/main.js"></script>
</body>

</html>
<?php
// --- 3. CLEAN UP ---
// Free the result set from memory and close the database connection
if ($result) {
    $result->free();
}
$mysqli->close();
?>
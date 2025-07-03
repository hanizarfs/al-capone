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

    <!-- Start Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary z-1000 fixed-top">
        <div class="container">
            <!-- Logo -->
            <a class="navbar-brand fw-semibold d-flex justify-content-center align-items-center" href="index.php">
                <img src="./assets/img/Logo.webp" alt="Logo" width="30" height="30" />
                <span class="ms-2"> Al Capone </span>
            </a>

            <!-- Navbar Toggler Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Nav -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Nav Items -->
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="rooms.php">Rooms</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="gallery.php">Gallery</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="faq.php">FAQ</a>
                    </li>
                </ul>

                <!-- Right Side (Login and Dark Mode Toggle) -->
                <div class="d-flex justify-content-center align-items-center">
                    <!-- Dark Mode Toggle -->
                    <!-- <div class="form-check form-switch me-3">
                            <input
                                class="form-check-input fs-5"
                                type="checkbox"
                                id="darkModeToggle"
                                aria-label="Toggle Dark Mode"
                            />
                        </div> -->

                    <!-- Cambiar Tema (Theme Toggle) -->
                    <div class="dropdown-center mx-2">
                        <button class="btn btn-bd-blue d-flex align-items-center" id="bd-theme" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)" style="outline: none; border: none; box-shadow: none">
                            <!-- Theme icon (dynamically updated) -->
                            <i id="theme-icon" class="bi bi-circle-half theme-icon-active" style="font-size: 1em"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                                    <i class="bi bi-sun-fill me-2 opacity-50 theme-icon" style="font-size: 1rem"></i>
                                    Light
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                                    <i class="bi bi-moon-stars me-2 opacity-50 theme-icon" style="font-size: 1rem"></i>
                                    Dark
                                </button>
                            </li>
                        </ul>
                    </div>
                    <!-- End Cambiar Tema -->

                    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
                        <!-- Dashboard Dropdown -->
                        <div class="dropdown-center">
                            <button class="btn btn-bd-primary dropdown-toggle d-flex align-items-center" id="profile-dropdown" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle profile options" style="outline: none; border: none; box-shadow: none">
                                <i class="bi bi-person-circle" style="font-size: 1.3em"></i>
                                <span class="ms-2" id="username-text"><?= htmlspecialchars($user['username']) ?></span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profile-dropdown">
                                <li>
                                    <a href="<?= $user['status'] == 1 ? 'user/dashboard.php' : 'admin/dashboard.php' ?>" class="dropdown-item d-flex align-items-center">
                                        <i class="bi bi-person me-2 opacity-50 theme-icon" style="font-size: 1rem"></i>
                                        Dashboard
                                        <svg class="bi ms-auto d-none" width="1em" height="1em">
                                            <path d="M1 1l4 4 4-4" />
                                        </svg>
                                    </a>
                                </li>
                                <li>
                                    <a href="logout.php" class="dropdown-item d-flex align-items-center">
                                        <i class="bi bi-box-arrow-right me-2 opacity-50 theme-icon" style="font-size: 1rem"></i>
                                        Logout
                                        <svg class="bi ms-auto d-none" width="1em" height="1em">
                                            <path d="M1 1l4 4 4-4" />
                                        </svg>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <!-- Login Button -->
                        <a href="login.php" class="btn bg-blue">Login</a>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->

    <!-- Start Main -->
    <main>


        <!-- Hero Section -->
        <section class="hero-section d-flex align-items-center justify-content-center text-center">
            <div class="hero-overlay"></div>
            <div class="container hero-content">
                <h1 class="display-4 fw-bold">Explore Our Rooms</h1>
                <p class="lead">Luxury, Comfort, and Convenience</p>
                <a href="#room-list" class="btn btn-light btn-lg">See Available Rooms</a>
            </div>
        </section>

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
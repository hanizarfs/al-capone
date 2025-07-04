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
        .overlay {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.6);
        }

        .hero-text {
            position: relative;
            z-index: 2;
        }

        .hero-about {
            position: relative;
            background-image: url('https://images.unsplash.com/photo-1582574643306-d00ea3f7d49b?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1yZWxhdGVkfDd8fHxlbnwwfHx8fHw%3D');
            background-size: cover;
            background-position: center;
            height: 60vh;
            color: white;
            z-index: 1;
        }

        .hero-about::before {
            content: "";
            position: absolute;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.5);
            /* dark overlay */
            z-index: 2;
        }

        .hero-about .hero-text {
            position: relative;
            z-index: 3;
        }

        .card-hover:hover {
            /* transform: translateY(-5px); */
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease-in-out;
        }

        .card-hover img {
            transition: transform 0.3s ease;
        }

        .card-hover:hover img {
            transform: scale(1.02);
        }
    </style>
</head>

<body>

    <!-- Start Navbar -->
    <nav class="navbar navbar-expand-lg bg-body-tertiary z-1000 fixed-top shadow-sm">
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
                        <a class="nav-link" href="rooms.php">Rooms</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="gallery.php">Gallery</a>
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
        <section class="hero-about d-flex align-items-center justify-content-center text-center">
            <div class="container hero-text">
                <h1 class="display-4 fw-bold">Explore Our Gallery</h1>
                <p class="lead">Luxury, Comfort, and Convenience</p>
                <a href="#gallery" class="btn btn-light btn-lg">See All Gallery</a>
            </div>
        </section>

        <section id="gallery" class="py-5">
            <div class="container">
                <div class="row g-3">
                    <div class="col-6 col-md-4">
                        <div class="card card-hover h-100 p-3 rounded-3">
                            <img src="https://images.unsplash.com/photo-1729808641681-e8b5024253d8?w=600&auto=format&fit=crop&q=60"
                                class="w-100 object-fit-cover rounded-3"
                                height="250" alt="Pool and Beach">
                            <div class="text-center">
                                <h6 class="card-title mb-0 pt-3">Pool and Beach</h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-4">
                        <div class="card card-hover h-100 p-3 rounded-3">
                            <img src="assets/img/deluxe_king.png"
                                class="w-100 object-fit-cover rounded-3"
                                height="250" alt="Room">
                            <div class="text-center">
                                <h6 class="card-title mb-0 pt-3">Deluxe Room</h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-4">
                        <div class="card card-hover h-100 p-3 rounded-3">
                            <img src="https://plus.unsplash.com/premium_photo-1683134297492-cce5fc6dae31?w=600&auto=format&fit=crop&q=60"
                                class="w-100 object-fit-cover rounded-3"
                                height="250" alt="Spa">
                            <div class="text-center">
                                <h6 class="card-title mb-0 pt-3">Luxury Spa</h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-4">
                        <div class="card card-hover h-100 p-3 rounded-3">
                            <img src="https://images.unsplash.com/photo-1559329007-40df8a9345d8?w=600&auto=format&fit=crop&q=60"
                                class="w-100 object-fit-cover rounded-3"
                                height="250" alt="Restaurant">
                            <div class="text-center">
                                <h6 class="card-title mb-0 pt-3">Restaurant</h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-4">
                        <div class="card card-hover h-100 p-3 rounded-3">
                            <img src="https://images.unsplash.com/photo-1540541338287-41700207dee6?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8cmVzb3J0JTIwaG90ZWx8ZW58MHx8MHx8fDA%3D"
                                class="w-100 object-fit-cover rounded-3"
                                height="250" alt="Restaurant">
                            <div class="text-center">
                                <h6 class="card-title mb-0 pt-3">View</h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-4">
                        <div class="card card-hover h-100 p-3 rounded-3">
                            <img src="https://images.unsplash.com/photo-1662386392989-bd9388cc636f?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8cmVzb3J0JTIwaG90ZWwlMjBuaWdodHxlbnwwfHwwfHx8MA%3D%3D"
                                class="w-100 object-fit-cover rounded-3"
                                height="250" alt="Restaurant">
                            <div class="text-center">
                                <h6 class="card-title mb-0 pt-3">View</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>
    <!-- End Main -->

    <!-- Footer -->
    <footer class="bg-body-tertiary py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-3 mb-md-0">
                    <h5>Al Capone Resort</h5>
                    <p class="mb-0">Jl. Tropis Indah No.123, Bali, Indonesia</p>
                    <p class="mb-0">Email: contact@alcaponeresort.com</p>
                    <p>Phone: +62 812-3456-7890</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h6>Quick Links</h6>
                    <a href="index.html" class="text-decoration-none">Home</a> |
                    <a href="rooms.php" class="text-decoration-none">Rooms</a> |
                    <a href="gallery.php" class="text-decoration-none">Gallery</a> |
                    <a href="faq.php" class="text-decoration-none">FAQ</a>
                </div>
            </div>
            <hr class="my-4 border-light" />
            <p class="text-center mb-0">© <?= date('Y') ?> Al Capone Resort. All rights reserved.</p>
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
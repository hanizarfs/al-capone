<?php
session_start();
require_once('config.php');

$user = null;
$user_id = null;
$user_status = null;

if (isset($_SESSION['user_id']) && isset($_SESSION['user_status'])) {
    $user_id = $_SESSION['user_id'];
    $user_status = $_SESSION['user_status'];

    $stmt = $mysqli->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}

$mysqli->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>About | Al Capone Resort</title>

    <link rel="icon" type="image/x-icon" href="./assets/img/Logo.webp" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" />

    <!-- CSS -->
    <link rel="stylesheet" href="./assets/css/style.css" />

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
            background-image: url('https://plus.unsplash.com/premium_photo-1681922761181-ee59fa91edc7?w=1200&auto=format&fit=crop&q=60');
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
                        <a class="nav-link active" href="about.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="rooms.php">Rooms</a>
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

    <!-- Hero Section -->
    <section class="hero-about d-flex align-items-center justify-content-center text-center">
        <div class="container hero-text">
            <h1 class="display-4 fw-bold">About Al Capone Resort</h1>
            <p class="lead">A Sanctuary of Luxury, Tranquility & Natural Beauty</p>
        </div>
    </section>

    <!-- About Content -->
    <section class="py-5">
        <div class="container">
            <h2 class="mb-4 text-center fw-bold">Our Story</h2>
            <p class="fs-5 text-center mb-4">Founded in 2015, Al Capone Resort was born out of a passion to create a luxurious escape surrounded by nature. Nestled in a serene environment, our resort combines modern comfort with local charm to deliver unforgettable experiences to travelers from around the world.</p>
            <div class="row mt-5">
                <div class="col-md-6 mb-4">
                    <img src="https://images.unsplash.com/photo-1652789728615-d988a7744c26?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTB8fHJlc29ydCUyMGZhY2lsaXRlc3xlbnwwfHwwfHx8MA%3D%3D" class="img-fluid w-100 shadow" alt="Resort Facilities">
                </div>
                <div class="col-md-6">
                    <h4 class="fw-semibold mb-3">What Makes Us Special</h4>
                    <ul class="list-unstyled fs-5">
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Private Villas with Scenic Views</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> 5-Star Spa & Wellness Experience</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Infinity Pool Overlooking the Valley</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Locally Sourced Gourmet Dining</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Personalized Guest Services</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Vision & Mission -->
    <section class="py-5 bg-light">
        <div class="container text-center">
            <h2 class="mb-4 fw-bold">Our Vision & Commitment</h2>
            <div class="row justify-content-center">
                <div class="col-md-5 mb-3">
                    <div class="p-4 card shadow rounded h-100">
                        <h5 class="fw-bold">Vision</h5>
                        <p>To be the leading resort in Southeast Asia, offering world-class hospitality in harmony with nature.</p>
                    </div>
                </div>
                <div class="col-md-5 mb-3">
                    <div class="p-4 card shadow rounded h-100">
                        <h5 class="fw-bold">Mission</h5>
                        <p>To provide unmatched comfort, personalized services, and unforgettable moments for every guest through sustainable and ethical tourism practices.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Meet the Team -->
    <section class="py-5">
        <div class="container text-center">
            <h2 class="mb-4 fw-bold">Meet Our Team</h2>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-3">
                        <img src="https://randomuser.me/api/portraits/men/5.jpg" class="rounded-circle d-block mx-auto" width="100" alt="CEO">
                        <div class="mt-3 text-center">
                            <h5 class="card-title mb-0">Rizky</h5>
                            <p class="text-muted">Founder & CEO</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-3">
                        <img src="https://randomuser.me/api/portraits/women/68.jpg" class="rounded-circle d-block mx-auto" width="100" alt="Manager">
                        <div class="mt-3 text-center">
                            <h5 class="card-title mb-0">Kartini</h5>
                            <p class="text-muted">Resort Manager</p>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card border-0 shadow-sm p-3">
                        <img src="https://randomuser.me/api/portraits/men/78.jpg" class="rounded-circle d-block mx-auto" width="100" alt="Chef">
                        <div class="mt-3 text-center">
                            <h5 class="card-title mb-0">Chef Alvin</h5>
                            <p class="text-muted">Executive Chef</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

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

    <!-- Main JS -->
    <script src="./assets/js/main.js"></script>
</body>

</html>
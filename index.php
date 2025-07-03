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
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Al Capone Resort</title>

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
                        <a class="nav-link active" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about.php">About</a>
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
    <section class="hero-section d-flex align-items-center justify-content-center text-center">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <p class="text-uppercase text-white-50 mb-2">Welcome To</p>
            <h1 class="display-3 fw-bold mb-3">Al Capone Resort</h1>
            <p class="lead mb-4">Experience luxury, nature, and timeless tranquility</p>
            <div class="d-flex justify-content-center gap-3">
                <a href="rooms.php" class="btn btn-lg btn-custom px-4">Book Now</a>
                <a href="gallery.php" class="btn btn-lg btn-outline-light px-4">Explore Gallery</a>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section class="py-5">
        <div class="container text-center">
            <h2 class="section-heading">A Luxury Escape Awaits</h2>
            <p class="about-text mb-4">
                Al Capone Resort is an exclusive destination designed to take you away from the hustle and bustle of everyday life.
                With stunning natural scenery, modern facilities and premium services, the resort is an ideal choice for a vacation,
                honeymoon or special occasion.
            </p>
            <p class="about-text mb-4">
                It’s a place where you and your family will enjoy pristine nature and have a great time in a distinguished and luxurious setting.
            </p>
            <a href="about.php" class="btn btn-outline-primary btn-lg mt-2">About Al Capone</a>
        </div>
    </section>

    <!-- Facilities Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <img src="https://source.unsplash.com/800x600/?villa,pool" class="img-fluid w-100 shadow" alt="Resort Facilities">
                </div>
                <div class="col-md-6">
                    <h3 class="section-heading">Unwind in Paradise</h3>
                    <ul class="list-unstyled fs-5">
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Private villas with infinity pool</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Restaurant with international menu</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Spa & wellness center</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Outdoor activities & BBQ nights</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Gallery Placeholder Section -->
    <section class="py-5">
        <div class="container text-center">
            <h2 class="section-heading">Gallery Preview</h2>
            <p class="about-text mb-4">Take a look at the beauty of Al Capone Resort before you arrive.</p>
            <div class="row g-3">
                <div class="col-6 col-md-3">
                    <img src="https://source.unsplash.com/400x400/?resort,beach" class="img-fluid" alt="Gallery 1" />
                </div>
                <div class="col-6 col-md-3">
                    <img src="https://source.unsplash.com/400x400/?hotel,room" class="img-fluid" alt="Gallery 2" />
                </div>
                <div class="col-6 col-md-3">
                    <img src="https://source.unsplash.com/400x400/?spa,resort" class="img-fluid" alt="Gallery 3" />
                </div>
                <div class="col-6 col-md-3">
                    <img src="https://source.unsplash.com/400x400/?pool,villa" class="img-fluid" alt="Gallery 4" />
                </div>
            </div>
            <a href="gallery.php" class="btn btn-outline-primary btn-lg mt-4">View Full Gallery</a>
        </div>
    </section>

    <!-- Facilities -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <img src="https://source.unsplash.com/800x600/?resort,pool" class="img-fluid rounded shadow" alt="Resort Facilities">
                </div>
                <div class="col-md-6">
                    <h3 class="section-heading">Unwind in Paradise</h3>
                    <ul class="list-unstyled fs-5">
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Private villas with infinity pool</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Restaurant with international menu</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Spa & wellness center</li>
                        <li><i class="bi bi-check-circle-fill text-success me-2"></i> Outdoor activities & BBQ nights</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-5">
        <div class="container text-center">
            <h2 class="section-heading">What Our Guests Say</h2>
            <div class="row g-4 mt-4">
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <p class="mb-2">"Amazing service and the view was breathtaking. Highly recommended!"</p>
                        <h6 class="fw-bold">Sarah M.</h6>
                        <small class="text-muted">Traveler</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <p class="mb-2">"Perfect getaway for my honeymoon. We'll definitely return!"</p>
                        <h6 class="fw-bold">Daniel & Rina</h6>
                        <small class="text-muted">Couple</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="testimonial-card">
                        <p class="mb-2">"One of the best resorts we've visited. Excellent food and facilities."</p>
                        <h6 class="fw-bold">James L.</h6>
                        <small class="text-muted">Business Traveler</small>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-primary text-white text-center">
        <div class="container">
            <h2 class="mb-3">Ready to Make Your Dream Stay a Reality?</h2>
            <p class="mb-4">Book your room today and enjoy unforgettable luxury and comfort.</p>
            <a href="rooms.php" class="btn btn-light btn-lg">Check Room Availability</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
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
                    <a href="index.php">Home</a> |
                    <a href="rooms.php">Rooms</a> |
                    <a href="gallery.php">Gallery</a> |
                    <a href="contact.php">Contact</a>
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
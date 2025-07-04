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

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <style>
        .hero-container {
            position: relative;
            height: 100vh;
            overflow: hidden;
        }

        .hero-container video {
            position: absolute;
            top: 50%;
            left: 50%;
            min-width: 100%;
            min-height: 100%;
            width: auto;
            height: auto;
            z-index: 1;
            transform: translate(-50%, -50%);
            object-fit: cover;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            z-index: 2;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            /* semi-transparent overlay */
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
            padding: 2rem;
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

        .swiper {
            width: 100%;
            max-width: 100%;
            padding: 0 60px;
        }
    </style>
</head>

<body>

    <!-- WhatsApp Button -->
    <a href="https://wa.me/6281229700588" target="_blank"
        class="btn btn-success rounded-circle shadow d-flex align-items-center justify-content-center"
        style="position: fixed; bottom: 20px; right: 20px; width: 60px; height: 60px; z-index: 100;">
        <i class="bi bi-whatsapp fs-3"></i>
    </a>

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

    <div class="hero-container">
        <!-- Background video from assets -->
        <video autoplay muted loop playsinline>
            <source src="./assets/video/Luxury Hotel Video Reel 2023.mp4" type="video/mp4" />
            Your browser does not support the video tag.
        </video>

        <!-- Overlay content -->
        <div class="hero-overlay">
            <div class="container position-relative text-white">
                <p class="text-uppercase text-white-50 mb-2">Welcome To</p>
                <h1 class="display-3 fw-bold mb-3">Al Capone Resort</h1>
                <p class="lead mb-4">Experience luxury, nature, and timeless tranquility</p>
                <div class="d-flex justify-content-center gap-3">
                    <a href="rooms.php" class="btn btn-lg btn-custom px-4">Book Now</a>
                    <a href="gallery.php" class="btn btn-lg btn-outline-light px-4">Explore Gallery</a>
                </div>
            </div>
        </div>
    </div>

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
            <a href="about.php" class="btn btn-outline-primary mt-2">About Al Capone</a>
        </div>
    </section>

    <!-- Facilities Section -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    <img src="https://images.unsplash.com/photo-1652789728615-d988a7744c26?w=600&auto=format&fit=crop&q=60&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MTB8fHJlc29ydCUyMGZhY2lsaXRlc3xlbnwwfHwwfHx8MA%3D%3D" class="img-fluid w-100 shadow" alt="Resort Facilities">
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
            <div class="container py-5">
                <div class="row g-3">
                    <div class="col-6 col-md-3">
                        <div class="card card-hover h-100 p-3 rounded-3">
                            <img src="https://images.unsplash.com/photo-1729808641681-e8b5024253d8?w=600&auto=format&fit=crop&q=60"
                                class="w-100 object-fit-cover rounded-3"
                                height="250" alt="Pool and Beach">
                            <div class="text-center">
                                <h6 class="card-title mb-0 pt-3">Pool and Beach</h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card card-hover h-100 p-3 rounded-3">
                            <img src="assets/img/deluxe_king.png"
                                class="w-100 object-fit-cover rounded-3"
                                height="250" alt="Room">
                            <div class="text-center">
                                <h6 class="card-title mb-0 pt-3">Deluxe Room</h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card card-hover h-100 p-3 rounded-3">
                            <img src="https://plus.unsplash.com/premium_photo-1683134297492-cce5fc6dae31?w=600&auto=format&fit=crop&q=60"
                                class="w-100 object-fit-cover rounded-3"
                                height="250" alt="Spa">
                            <div class="text-center">
                                <h6 class="card-title mb-0 pt-3">Luxury Spa</h6>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 col-md-3">
                        <div class="card card-hover h-100 p-3 rounded-3">
                            <img src="https://images.unsplash.com/photo-1559329007-40df8a9345d8?w=600&auto=format&fit=crop&q=60"
                                class="w-100 object-fit-cover rounded-3"
                                height="250" alt="Restaurant">
                            <div class="text-center">
                                <h6 class="card-title mb-0 pt-3">Restaurant</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <a href="gallery.php" class="btn btn-outline-primary mt-4">View Full Gallery</a>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5 bg-blue text-white text-center">
        <div class="container">
            <h2 class="mb-3">Ready to Make Your Dream Stay a Reality?</h2>
            <p class="mb-4">Book your room today and enjoy unforgettable luxury and comfort.</p>
            <a href="rooms.php" class="btn btn-light">Check Room Availability</a>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="py-5">
        <div class="container text-center">
            <h2 class="section-heading mb-5">What Our Guests Say</h2>
            <!-- Slider main container -->
            <div class="swiper">
                <!-- Additional required wrapper -->
                <div class="swiper-wrapper">
                    <!-- Slides -->
                    <div class="swiper-slide">
                        <div class="p-4 border rounded shadow-sm h-100">
                            <div class="d-flex align-items-center mb-3">
                                <img src="https://randomuser.me/api/portraits/men/56.jpg" alt="James L." class="rounded-circle me-3" width="50" height="50">
                                <div>
                                    <h6 class="mb-0 fw-bold">James L.</h6>
                                    <small class="text-muted">Business Traveler</small>
                                </div>
                            </div>
                            <p class="mb-2">"One of the best resorts we've visited. Excellent food and facilities."</p>
                            <div class="text-warning">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star"></i>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="p-4 border rounded shadow-sm h-100">
                            <div class="d-flex align-items-center mb-3">
                                <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="Sarah M." class="rounded-circle me-3" width="50" height="50">
                                <div>
                                    <h6 class="mb-0 fw-bold">Sarah M.</h6>
                                    <small class="text-muted">Traveler</small>
                                </div>
                            </div>
                            <p class="mb-2">"Amazing service and the view was breathtaking. Highly recommended!"</p>
                            <div class="text-warning">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-half"></i>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="p-4 border rounded shadow-sm h-100">
                            <div class="d-flex align-items-center mb-3">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Daniel & Rina" class="rounded-circle me-3" width="50" height="50">
                                <div>
                                    <h6 class="mb-0 fw-bold">Daniel &amp; Rina</h6>
                                    <small class="text-muted">Couple</small>
                                </div>
                            </div>
                            <p class="mb-2">"Perfect getaway for my honeymoon. We'll definitely return!"</p>
                            <div class="text-warning">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="p-4 border rounded shadow-sm h-100">
                            <div class="d-flex align-items-center mb-3">
                                <img src="https://randomuser.me/api/portraits/men/56.jpg" alt="James L." class="rounded-circle me-3" width="50" height="50">
                                <div>
                                    <h6 class="mb-0 fw-bold">James L.</h6>
                                    <small class="text-muted">Business Traveler</small>
                                </div>
                            </div>
                            <p class="mb-2">"One of the best resorts we've visited. Excellent food and facilities."</p>
                            <div class="text-warning">
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star-fill"></i>
                                <i class="bi bi-star"></i>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- If we need navigation buttons -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>

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

    <script>
        const swiper = new Swiper('.swiper', {
            // Optional parameters
            loop: true,
            // Navigation arrows
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            breakpoints: {
                // when window width is >= 320px
                768: {
                    slidesPerView: 2,
                    spaceBetween: 20
                },
                // when window width is >= 480px
                1024: {
                    slidesPerView: 3,
                    spaceBetween: 30
                },
                // when window width is >= 640px
                1280: {
                    slidesPerView: 3,
                    spaceBetween: 30
                }
            }
        });
    </script>
</body>

</html>
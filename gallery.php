<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Gallery | Al Capone Resort</title>
    <link rel="icon" type="image/x-icon" href="./assets/img/Logo.webp" />

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" />

    <style>
        .hero-gallery {
            background: url('https://source.unsplash.com/1600x600/?resort,nature,luxury') center/cover no-repeat;
            height: 50vh;
            position: relative;
            color: white;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: rgba(0, 0, 0, 0.6);
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .search-bar {
            max-width: 400px;
            margin: auto;
        }

        .gallery-img {
            height: 250px;
            object-fit: cover;
        }

        footer {
            background-color: #1e293b;
            color: white;
            padding: 2rem 0;
        }

        footer a {
            color: #cbd5e1;
            text-decoration: none;
        }

        footer a:hover {
            color: white;
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

    <!-- Hero Section with Search -->
    <section class="hero-gallery d-flex align-items-center text-center">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <h1 class="display-5 fw-bold mb-3">Explore Our Gallery</h1>
        </div>
    </section>

    <!-- Gallery Section -->
    <section class="py-5">
        <div class="container">
            <div class="row g-4">
                <!-- Gallery Item -->
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card border-0 shadow-sm">
                        <img src="https://source.unsplash.com/600x400/?hotel-room" class="card-img-top gallery-img" alt="Room" />
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card border-0 shadow-sm">
                        <img src="https://source.unsplash.com/600x400/?resort-pool" class="card-img-top gallery-img" alt="Pool" />
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card border-0 shadow-sm">
                        <img src="https://source.unsplash.com/600x400/?spa,relax" class="card-img-top gallery-img" alt="Spa" />
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card border-0 shadow-sm">
                        <img src="https://source.unsplash.com/600x400/?resort-view" class="card-img-top gallery-img" alt="View" />
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card border-0 shadow-sm">
                        <img src="https://source.unsplash.com/600x400/?resort-night" class="card-img-top gallery-img" alt="Night View" />
                    </div>
                </div>
                <div class="col-sm-6 col-md-4 col-lg-3">
                    <div class="card border-0 shadow-sm">
                        <img src="https://source.unsplash.com/600x400/?restaurant,hotel" class="card-img-top gallery-img" alt="Restaurant" />
                    </div>
                </div>
                <!-- Add more as needed -->
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Al Capone Resort</h5>
                    <p class="mb-0">Jl. Tropis Indah No.123, Bali, Indonesia</p>
                    <p class="mb-0">Email: contact@alcaponeresort.com</p>
                    <p>Phone: +62 812-3456-7890</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h6>Quick Links</h6>
                    <a href="index.html">Home</a> |
                    <a href="about.html">About</a> |
                    <a href="rooms.php">Rooms</a> |
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
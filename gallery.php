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

    <!-- Hero Section with Search -->
    <section class="hero-gallery d-flex align-items-center text-center">
        <div class="hero-overlay"></div>
        <div class="container hero-content">
            <h1 class="display-5 fw-bold mb-3">Explore Our Gallery</h1>
            <form class="search-bar d-flex" role="search" onsubmit="event.preventDefault(); alert('Search feature is coming soon!')">
                <input class="form-control me-2" type="search" placeholder="Search images..." aria-label="Search" />
                <button class="btn btn-light" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </form>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
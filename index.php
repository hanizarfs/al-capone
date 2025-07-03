<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Al Capone Resort</title>
    <link rel="icon" type="image/x-icon" href="./assets/img/Logo.webp" />

    <!-- Bootstrap CSS & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" />

    <style>
        /* Hero Section */
        .hero-section {
            background-image: url('https://source.unsplash.com/1600x900/?luxury-resort,beach');
            background-size: cover;
            background-position: center;
            height: 100vh;
            position: relative;
            color: white;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            width: 100%;
            background: rgba(0, 0, 0, 0.55);
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        /* Button styling */
        .btn-custom {
            background-color: #1e40af;
            color: #fff;
            border: none;
            transition: 0.3s;
        }

        .btn-custom:hover {
            background-color: #1d4ed8;
            color: #fff;
        }

        .section-heading {
            font-weight: 700;
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .about-text {
            font-size: 1.125rem;
            color: #444;
        }

        .img-fluid {
            border-radius: 12px;
            object-fit: cover;
        }
    </style>
</head>

<body>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>About | Al Capone Resort</title>
    <link rel="icon" href="./assets/img/Logo.webp" type="image/x-icon" />

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" />

    <style>
        .hero-about {
            background-image: url('https://source.unsplash.com/1600x700/?resort,luxury,nature');
            background-size: cover;
            background-position: center;
            height: 60vh;
            position: relative;
            color: white;
        }

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
            color: #ffffff;
        }
    </style>
</head>

<body>

    <!-- Hero Section -->
    <section class="hero-about d-flex align-items-center justify-content-center text-center">
        <div class="overlay"></div>
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
                    <img src="https://source.unsplash.com/800x500/?luxury-resort,spa" class="img-fluid rounded shadow" alt="Resort Image" />
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
                    <div class="p-4 bg-white shadow rounded h-100">
                        <h5 class="fw-bold">Vision</h5>
                        <p>To be the leading resort in Southeast Asia, offering world-class hospitality in harmony with nature.</p>
                    </div>
                </div>
                <div class="col-md-5 mb-3">
                    <div class="p-4 bg-white shadow rounded h-100">
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
                    <div class="card border-0 shadow-sm">
                        <img src="https://source.unsplash.com/300x300/?portrait,ceo" class="card-img-top" alt="CEO">
                        <div class="card-body">
                            <h5 class="card-title mb-0">Michael Santos</h5>
                            <p class="text-muted">Founder & CEO</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <img src="https://source.unsplash.com/300x300/?portrait,manager" class="card-img-top" alt="Manager">
                        <div class="card-body">
                            <h5 class="card-title mb-0">Julia Hartono</h5>
                            <p class="text-muted">Resort Manager</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card border-0 shadow-sm">
                        <img src="https://source.unsplash.com/300x300/?portrait,chef" class="card-img-top" alt="Chef">
                        <div class="card-body">
                            <h5 class="card-title mb-0">Chef Antonio</h5>
                            <p class="text-muted">Executive Chef</p>
                        </div>
                    </div>
                </div>
            </div>
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
                    <a href="index.html">Home</a> |
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
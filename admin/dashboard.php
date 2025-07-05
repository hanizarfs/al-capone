<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: ../login.php');
    exit;
}

if ($_SESSION['user_status'] == 1) {
    header('location: ../index.php');
    exit;
}

require_once('../config.php');

$totalRooms = $mysqli->query("SELECT COUNT(*) AS total FROM rooms")->fetch_assoc()['total'];

$activeBookings = $mysqli->query("SELECT COUNT(*) AS total FROM bookings WHERE status = 'ACTIVE'")->fetch_assoc()['total'];

$nonActiveBookings = $mysqli->query("SELECT COUNT(*) AS total FROM bookings WHERE status != 'ACTIVE'")->fetch_assoc()['total'];

$totalUsers = $mysqli->query("SELECT COUNT(*) AS total FROM users")->fetch_assoc()['total'];
$availableRooms = $mysqli->query("SELECT SUM(availability) AS total FROM rooms")->fetch_assoc()['total'];
$todayBookings = $mysqli->query("SELECT COUNT(*) AS total FROM bookings WHERE DATE(booking_timestamp) = CURDATE()")->fetch_assoc()['total'];

$user_id = $_SESSION['user_id'];

$stmt = $mysqli->prepare("SELECT id, username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// $stmt->close();
// $mysqli->close();

if (!$user) {
    session_destroy();
    header('location: ../login.php');
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard | Al Capone</title>
    <link rel="icon" type="image/x-icon" href="../assets/img/Logo.webp" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" />

    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" />

    <!-- CSS -->
    <link rel="stylesheet" href="../assets/css/style.css" />

</head>

<body>

    <!-- Aside -->
    <?php include_once __DIR__ . '/sidebar.php'; ?>
    <!-- End of Aside -->

    <main class="col-lg-10" id="main">
        <!-- NavBar -->
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <!-- Logo -->
                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample" style="margin-right: 10px; padding: 2px 6px 2px 6px" id="sidebarshow">
                    <i class="bi bi-arrow-bar-right"></i>
                </button>
                <h4 class="fw-semibold mb-0">Dashboard</h4>

                <!-- Right Side (Login and Dark Mode Toggle) -->
                <div class="d-flex justify-content-center align-items-center ms-auto">
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
                    <div class="dropdown-center">
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

                    <!-- Separator with text-black-50 -->
                    <div class="">|</div>

                    <!-- Profile Dropdown -->
                    <div class="dropdown-center">
                        <button class="btn btn-bd-primary dropdown-toggle d-flex align-items-center" id="profile-dropdown" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle profile options" style="outline: none; border: none; box-shadow: none">
                            <i class="bi bi-person-circle" style="font-size: 1.3em"></i>
                            <span class="ms-2" id="username-text"><?php echo htmlspecialchars($user['username']) ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profile-dropdown">
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center">
                                    <i class="bi bi-person me-2 opacity-50 theme-icon" style="font-size: 1rem"></i>
                                    Profile
                                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                                        <path d="M1 1l4 4 4-4" />
                                    </svg>
                                </button>
                            </li>
                            <li>
                                <a href="#" onclick="logout()" class="dropdown-item d-flex align-items-center">
                                    <i class="bi bi-box-arrow-right me-2 opacity-50 theme-icon" style="font-size: 1rem"></i>
                                    Logout
                                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                                        <path d="M1 1l4 4 4-4" />
                                    </svg>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>

        <hr class="mt-0" />

        <!-- End NavBar -->

        <div class="container">
            <h1 class="mb-4 fw-bold">👋 Halo, Admin</h1>

            <!-- Statistik Kartu -->
            <div class="row g-4 mb-4">

                <div class="col-md-4">
                    <div class="card text-white bg-primary shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-light">Total Rooms</h6>
                            <h2 class="fw-bold"><?= $totalRooms ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-white bg-success shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-light">Active Bookings</h6>
                            <h2 class="fw-bold"><?= $activeBookings ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-white bg-danger shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-light">Non Active Bookings</h6>
                            <h2 class="fw-bold"><?= $nonActiveBookings ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-white bg-info shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-light">Available Rooms</h6>
                            <h2 class="fw-bold"><?= $availableRooms ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-white bg-warning shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-dark">Today's Bookings</h6>
                            <h2 class="fw-bold"><?= $todayBookings ?></h2>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="card text-white bg-dark shadow-sm border-0">
                        <div class="card-body">
                            <h6 class="text-light">Total Users</h6>
                            <h2 class="fw-bold"><?= $totalUsers ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik dan Aktivitas -->
            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-3">📊 Booking Status Overview</h5>
                            <canvas id="bookingChart" height="200"></canvas>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title fw-bold mb-3">🕓 Recent Activities</h5>
                            <ul class="list-group list-group-flush">
                                <?php
                                $logs = $mysqli->query("SELECT * FROM user_logs ORDER BY log_timestamp DESC LIMIT 5");
                                while ($log = $logs->fetch_assoc()):
                                ?>
                                    <li class="list-group-item small">
                                        <strong><?= htmlspecialchars($log['action']) ?></strong> by admin #<?= $log['admin_id'] ?> - <?= date('d M Y H:i', strtotime($log['log_timestamp'])) ?>
                                    </li>
                                <?php endwhile;
                                $mysqli->close();
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <a href="../index.html" class="link-body-emphasis fw-bold fs-5 text-decoration-none offcanvas-title" id="offcanvasExampleLabel">Al Capone</a>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mt-0">
            <ul class="list-unstyled ps-0" id="sidebar">
                <li class="mb-2">
                    <a href="dashboard.php" class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed rounded-3 w-100"> <i class="bi bi-house-door-fill me-2"></i> Dashboard </a>
                </li>
                <li class="mb-2">
                    <a href="userManagement.php" class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed rounded-3 w-100"> <i class="bi bi-person-lines-fill me-2"></i> User Management </a>
                </li>
                <li class="mb-2">
                    <a href="cancellation-requests.php" class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed rounded-3 w-100"> <i class="bi bi-x-circle-fill me-2"></i> Cancellation Requests </a>
                </li>
                <li class="mb-2">
                    <a href="online-checkin.php" class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed rounded-3 w-100"> <i class="bi bi-check-circle-fill me-2"></i> Online Check-in </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- End Main Content -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Main JS -->
    <script src="../assets/js/main.js"></script>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('bookingChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Active Bookings', 'Non Active'],
                datasets: [{
                    data: [<?= $activeBookings ?>, <?= $nonActiveBookings ?>],
                    backgroundColor: ['#198754', '#dc3545'],
                    borderColor: ['#fff', '#fff'],
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            color: '#333'
                        }
                    }
                }
            }
        });
    </script>

    <script>
        function logout() {
            let theme = localStorage.getItem('theme');
            console.log('Current theme:', theme);
            if (theme == 'dark') {
                console.log("halooo");
                localStorage.removeItem('theme');
                console.log("tesss");
                localStorage.setItem('themess', 'red');
                localStorage.clear('theme');
                // localStorage.setItem('theme', 'red'); // Atur ulang
            }
            localStorage.setItem('themes', 'blue');
            localStorage.setItem('theme', 'black');

            // Redirect
            // window.location.href = '../logout.php';
        }
    </script>

    <script>
        // Get the current URL path (without the base URL)
        const currentUrl = window.location.pathname;

        // Select all the sidebar links
        const sidebarLinks = document.querySelectorAll("#sidebar a");

        // Loop through the sidebar links to check if the current URL matches
        sidebarLinks.forEach((link) => {
            // Get the href of the link (relative URL)
            const linkHref = link.getAttribute("href");

            // Check if the current URL path includes the link's href (for index.html, create.html, etc.)
            if (currentUrl.includes(linkHref)) {
                // Add the 'bg-blue' class to the active link
                link.classList.add("bg-blue");
            }
        });
    </script>
</body>

</html>
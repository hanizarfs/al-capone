<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: ../../login.php');
    exit;
}

if ($_SESSION['user_status'] == 1) {
    header('location: ../../index.php');
    exit;
}

require_once('../../config.php');

$user_id = $_SESSION['user_id'];

$stmt = $mysqli->prepare("SELECT id, username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$stmt->close();

if (!$user) {
    session_destroy();
    header('location: ../login.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $room_id = trim($_POST['room_id']);
    $name = trim($_POST['name']);
    $price = floatval($_POST['price']);
    $description = trim($_POST['description']);
    $availability = $_POST['availability'] ?? 'available';
    $created_at = date('Y-m-d H:i:s');

    // Insert to DB
    $stmtCreateRoom = $mysqli->prepare("INSERT INTO rooms (id, name, price, description, availability, created_at) VALUES (?, ?, ?, ?, ?, ?)");
    $stmtCreateRoom->bind_param("ssdsis", $room_id, $name, $price, $description, $availability, $created_at);

    if ($stmtCreateRoom->execute()) {
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Room created successfully.'];
    } else {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Failed to create room.'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Create Rooms | Al Capone</title>
    <link rel="icon" type="image/x-icon" href="../../assets/img/Logo.webp" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" />

    <!-- Bootstrap Icon -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" />

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" />

    <!-- CSS -->
    <link rel="stylesheet" href="../../assets/css/style.css" />

    <!-- Style -->
    <style>
        body {
            height: 100%;
        }

        aside {
            /* border: 1px yellow solid; */
            position: fixed;
            overflow: auto;
            height: calc(100vh - 12px);
            justify-content: flex-start;
            align-self: flex-start;
        }

        nav {
            position: sticky;
        }

        main {
            position: relative;
            overflow: visible;
            margin-left: auto;
            justify-content: flex-end;
            align-self: flex-end;
        }

        #sidebarshow {
            display: none;
        }

        .b-example-divider {
            width: 100%;
            height: 3rem;
            background-color: rgba(0, 0, 0, 0.1);
            border: solid rgba(0, 0, 0, 0.15);
            border-width: 1px 0;
            box-shadow: inset 0 0.5em 1.5em rgba(0, 0, 0, 0.1), inset 0 0.125em 0.5em rgba(0, 0, 0, 0.15);
        }

        .b-example-vr {
            flex-shrink: 0;
            width: 1.5em;
            height: 100vh;
        }

        .bi {
            vertical-align: -0.125em;
            fill: currentColor;
        }

        @media screen and (max-width: 992px) {
            #sidebarshow {
                display: inline;
            }

            #sidebartoggle {
                display: none;
            }
        }

        #sidebar button:hover {
            background: darkblue;
        }

        .dataTables_length {
            margin-bottom: 12px !important;
        }
    </style>
</head>

<body>
    <!-- Aside -->
    <?php include_once __DIR__ . '../../sidebar.php'; ?>
    <!-- End of Aside -->

    <main class="col-lg-10" id="main">
        <!-- NavBar -->
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <!-- Logo -->
                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample" style="margin-right: 10px; padding: 2px 6px 2px 6px" id="sidebarshow">
                    <i class="bi bi-arrow-bar-right"></i>
                </button>
                <h4 class="fw-semibold mb-0">Rooms</h4>

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

            <!-- Heading and Breadcrumb for "Create New Room" -->
            <div class="section-header mb-4">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="card-title mb-0">Create New Room</h5>
                </div>

                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item">
                            <a href="index.php" class="text-primary text-decoration-none">Rooms</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Create</li>
                    </ol>
                </nav>
            </div>

            <form method="POST">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Room ID</label>
                        <input type="text" name="room_id" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Room Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Price (IDR)</label>
                        <input type="number" name="price" class="form-control" step="0.01" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Availability</label>
                        <input type="number" name="availability" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" class="btn bg-blue w-auto">Save Room</button>
                </div>
            </form>
        </div>

    </main>


    <!-- Sidebar for Mobile -->
    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasExample"
        aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <a href="../../index.php" class="link-body-emphasis fw-bold fs-5 text-decoration-none offcanvas-title"
                id="offcanvasExampleLabel">Al Capone</a>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mt-0">
            <ul class="list-unstyled ps-0" id="sidebar">
                <li class="mb-2">
                    <a href="../dashboard.php"
                        class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed rounded-3 w-100">
                        <i class="bi bi-house-door-fill me-2"></i> Dashboard </a>
                </li>
                <li class="mb-2">
                    <a href="index.php"
                        class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed rounded-3 w-100 bg-blue">
                        <i class="bi bi-person-lines-fill me-2"></i> User Management </a>
                </li>
                <li class="mb-2">
                    <a href="../cancellationRequests.php"
                        class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed rounded-3 w-100">
                        <i class="bi bi-x-circle-fill me-2"></i> Cancellation Requests </a>
                </li>
                <li class="mb-2">
                    <a href="../onlineCheckin.php"
                        class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed rounded-3 w-100">
                        <i class="bi bi-check-circle-fill me-2"></i> Online Check-in </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- End Sidebar for Mobile -->

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

    <!-- Main JS -->
    <script src="../../assets/js/main.js"></script>

    <?php if (isset($_SESSION['flash'])): ?>
        <script>
            Swal.fire({
                icon: '<?= $_SESSION['flash']['type'] ?>',
                title: '<?= $_SESSION['flash']['type'] === 'success' ? 'Success!' : 'Error!' ?>',
                text: <?= json_encode($_SESSION['flash']['message']) ?>,
                timer: 2000,
                showConfirmButton: false,
                willClose: () => {
                    window.location.href = "index.php";
                }
            });
        </script>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <script>
        $(document).ready(function() {
            $("#dataTables").DataTable({
                columnDefs: [{
                    orderable: false,
                    targets: [0, 3]
                }],
            });
        });
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

            // Check if the current URL path contains part of the link's href (e.g., 'user-management')
            if (currentUrl.includes(linkHref.split("/").pop())) {
                // Add the 'bg-blue' class to the active link
                link.classList.add("bg-blue");
            }
        });

        document.getElementById("addUserForm").addEventListener("submit", function(e) {
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("password-confirm").value;

            if (password !== confirmPassword) {
                e.preventDefault();
                Swal.fire({
                    title: 'Error!',
                    text: 'Passwords do not match!',
                    icon: 'error'
                });
                return false;
            }
        });
    </script>
</body>

</html>
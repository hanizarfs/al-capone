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

$user_id_to_view = $_GET['id'];

$user_info_stmt = $mysqli->prepare("SELECT first_name, last_name, username, email, phone, created_at, updated_at FROM users WHERE id = ?");
$user_info_stmt->bind_param("i", $user_id_to_view);
$user_info_stmt->execute();
$result = $user_info_stmt->get_result();
$user_view = $result->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Detail User Management and User Levels | Al Capone</title>
    <link rel="icon" type="image/x-icon" href="../../assets/img/Logo.webp" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" />

    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" />

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
    <!-- Aside Left -->
    <aside class="collapse show collapse-horizontal col-sm-2 p-3 border-end d-none d-lg-block" id="collapseWidthExample">
        <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
            <img src="../../assets/img/Logo.webp" alt="Logo" width="40" />
            <span class="d-print-block ms-2 fw-bold fs-5">Al Capone</span>
        </a>
        <br />
        <ul class="list-unstyled ps-0" id="sidebar">
            <li class="mb-2">
                <a href="../dashboard.php" class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed rounded-3 w-100"> <i class="bi bi-house-door-fill me-2"></i> Dashboard </a>
            </li>
            <li class="mb-2">
                <a href="index.php" class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed rounded-3 w-100 bg-blue"> <i class="bi bi-person-lines-fill me-2"></i> User Management </a>
            </li>
            <li class="mb-2">
                <a href="../cancellationRequests.php" class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed rounded-3 w-100"> <i class="bi bi-x-circle-fill me-2"></i> Cancellation Requests </a>
            </li>
            <li class="mb-2">
                <a href="../onlineCheckin.php" class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed rounded-3 w-100"> <i class="bi bi-check-circle-fill me-2"></i> Online Check-in </a>
            </li>
        </ul>
    </aside>
    <!-- End Aside Left -->

    <!-- Aside Right -->
    <main class="col-lg-10 px-0 px-md-3" id="main">
        <!-- NavBar -->
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <!-- Logo -->
                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample" style="margin-right: 10px; padding: 2px 6px 2px 6px" id="sidebarshow">
                    <i class="bi bi-arrow-bar-right"></i>
                </button>
                <h5 class="mb-0 fw-bold">Detail User Management and User Levels</h5>

                <!-- Right Side (Login and Dark Mode Toggle) -->
                <div class="d-flex justify-content-center align-items-center ms-auto">
                    <!-- Dark Mode Toggle -->

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
                        <button class="btn btn-bd-blue d-flex align-items-center" id="profile-dropdown" type="button" aria-expanded="false" data-bs-toggle="dropdown" aria-label="Toggle theme (auto)" style="outline: none; border: none; box-shadow: none">
                            <i class="bi bi-person-circle" style="font-size: 1.3em"></i>
                            <span class="ms-2 d-none d-md-block" id="username-text"><?php echo htmlspecialchars($user['username']) ?></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profile-dropdown">
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                                    <i class="bi bi-person me-2 opacity-50 theme-icon" style="font-size: 1rem"></i>
                                    Profile
                                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                                        <path d="M1 1l4 4 4-4" />
                                    </svg>
                                </button>
                            </li>
                            <li>
                                <a href="../../index.php" type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
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
        <!-- End NavBar -->

        <hr class="mt-0" />

        <!-- Section Content -->
        <section id="main-content">
            <div class="container">
                <!-- Heading for Detail User Management and User Level -->
                <div class="section-header mb-4">
                    <p>View the details of the selected user, including their name, email, role, and account status. You can choose to edit or delete their account if needed.</p>
                    <nav style="--bs-breadcrumb-divider: '>'" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="index.php" class="text-blue">User Management</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Details</li>
                        </ol>
                    </nav>
                </div>

                <form class="mb-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">First Name</label>
                                <input type="text" class="form-control" disabled value="<?= $user_view['first_name']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Last Name</label>
                                <input type="text" class="form-control" disabled value="<?= $user_view['last_name']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Username</label>
                                <input type="text" class="form-control" disabled value="<?= $user_view['username']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Phone Number</label>
                                <input type="number" class="form-control" disabled value="<?= $user_view['phone']; ?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Email address</label>
                                <input type="email" class="form-control" disabled value="<?= $user_view['email']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Created at</label>
                                <input type="date" class="form-control" disabled value="<?= date('Y-m-d', strtotime($user_view['created_at'])); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Last Updated at</label>
                                <input type="date" class="form-control" disabled value="<?= date('Y-m-d', strtotime($user_view['updated_at'])); ?>">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </section>
        <!-- End Main Content -->
    </main>
    <!-- End Aside Right -->

    <!-- Sidebar for Mobile -->
    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <a href="../../index.php" class="link-body-emphasis fw-bold fs-5 text-decoration-none offcanvas-title" id="offcanvasExampleLabel">Al Capone</a>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body mt-0">
            <ul class="list-unstyled ps-0" id="sidebar">
                <li class="mb-2">
                    <a href="../dashboard.php" class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed rounded-3 w-100"> <i class="bi bi-house-door-fill me-2"></i> Dashboard </a>
                </li>
                <li class="mb-2">
                    <a href="index.php" class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed rounded-3 w-100 bg-blue"> <i class="bi bi-person-lines-fill me-2"></i> User Management </a>
                </li>
                <li class="mb-2">
                    <a href="../cancellationRequests.php" class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed rounded-3 w-100"> <i class="bi bi-x-circle-fill me-2"></i> Cancellation Requests </a>
                </li>
                <li class="mb-2">
                    <a href="../onlineCheckin.php" class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed rounded-3 w-100"> <i class="bi bi-check-circle-fill me-2"></i> Online Check-in </a>
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
    </script>

    <script>
        const togglePassword = document.getElementById("toggle-password");
        const passwordField = document.getElementById("password");
        const eyeIcon = document.getElementById("eye-icon");

        togglePassword.addEventListener("click", function() {
            if (passwordField.type === "password") {
                passwordField.type = "text";
                eyeIcon.classList.remove("bi-eye-slash");
                eyeIcon.classList.add("bi-eye");
            } else {
                passwordField.type = "password";
                eyeIcon.classList.remove("bi-eye");
                eyeIcon.classList.add("bi-eye-slash");
            }
        });

        const togglePasswordConfirm = document.getElementById("toggle-password-confirm");
        const passwordConfirmField = document.getElementById("password-confirm");
        const eyeIconConfirm = document.getElementById("eye-icon-confirm");

        togglePasswordConfirm.addEventListener("click", function() {
            if (passwordConfirmField.type === "password") {
                passwordConfirmField.type = "text";
                eyeIconConfirm.classList.remove("bi-eye-slash");
                eyeIconConfirm.classList.add("bi-eye");
            } else {
                passwordConfirmField.type = "password";
                eyeIconConfirm.classList.remove("bi-eye");
                eyeIconConfirm.classList.add("bi-eye-slash");
            }
        });
    </script>
</body>

</html>
<?php
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: ../../login.php');
    exit;
}

$success_message = '';
if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    unset($_SESSION['success_message']);
}
$error_message = '';
if (isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    unset($_SESSION['error_message']);
}

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: ../../login.php');
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
    header('location: ../../login.php');
    exit;
}

$query_stmt = $mysqli->prepare("SELECT id, room_type, checkin_date, checkout_date, status, appeal_reason, rejected_reason FROM bookings WHERE user_id = ? AND  status != 'Inactive'");
$query_stmt->bind_param("i", $user_id);
$query_stmt->execute();
$active_result = $query_stmt->get_result();

$query_stmt->close();

// Pastikan session user_id tersedia
if (!isset($_SESSION['user_id'])) {
    echo "User not logged in.";
    exit;
}

$user_id = $_GET['user_id'] ?? null;

if (!$user_id) {
    // Redirect jika tidak ada user_id
    header("Location: index.php");
    exit();
}

$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_view = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (strlen($new_password) < 6) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Password must be at least 6 characters.'];
    } elseif ($new_password !== $confirm_password) {
        $_SESSION['flash'] = ['type' => 'error', 'message' => 'Passwords do not match.'];
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $update = $mysqli->prepare("UPDATE users SET password_hash = ?, updated_at = NOW() WHERE id = ?");
        $update->bind_param("si", $hashed_password, $user_id);

        if ($update->execute()) {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Password updated successfully.'];
        } else {
            $_SESSION['flash'] = ['type' => 'error', 'message' => 'Failed to update password.'];
        }

        header("Location: reset_password.php?user_id=$user_id");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile | Al Capone</title>
    <link rel="icon" type="image/x-icon" href="../../assets/img/Logo.webp" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" />

    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" />

    <!-- CSS -->
    <link rel="stylesheet" href="../../assets/css/style.css" />

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
                <h4 class="mb-0 fw-semibold">Profile</h4>

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
                                <a href="profile/index.php" type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                                    <i class="bi bi-person me-2 opacity-50 theme-icon" style="font-size: 1rem"></i>
                                    Profile
                                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                                        <path d="M1 1l4 4 4-4" />
                                    </svg>
                                </a>
                            </li>
                            <li>
                                <a href="../../logout.php" type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
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

        <!-- HTML Form -->
        <div class="container">
            <h5 class="card-title mb-4">Reset Password for <?= htmlspecialchars($user_view['username'] ?? '') ?></h5>
            <form method="POST">
                <div class="mb-3">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="confirm_password" class="form-label">Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <button type="submit" class="btn bg-blue w-auto">Update Password</button>
                <a href="index.php" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </main>

    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
        <div class="offcanvas-header">
            <a href="../../index.html" class="link-body-emphasis fw-bold fs-5 text-decoration-none offcanvas-title" id="offcanvasExampleLabel">Al Capone</a>
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
    <script src="../../assets/js/main.js"></script>

    <?php if (isset($_SESSION['flash'])): ?>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            Swal.fire({
                icon: '<?= $_SESSION['flash']['type'] ?>',
                title: '<?= $_SESSION['flash']['type'] === 'success' ? 'Success!' : 'Error!' ?>',
                text: <?= json_encode($_SESSION['flash']['message']) ?>,
                timer: 1500,
                showConfirmButton: false
            });
        </script>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <script>
        <?php if (!empty($success_message)): ?>
            Swal.fire({
                title: 'Success!',
                text: <?php echo json_encode($success_message); ?>,
                icon: 'success',
                confirmButtonText: 'OK'
            });
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            Swal.fire({
                title: 'Error!',
                text: <?php echo json_encode($error_message); ?>,
                icon: 'error',
                confirmButtonText: 'Try Again'
            });
        <?php endif; ?>

        const cancelButton = document.querySelectorAll('.cancel-room-btn');
        cancelButton.forEach(button => {
            button.addEventListener('click', function(e) {
                // Prevent the default link behavior
                e.preventDefault();

                // Get the user ID and username from the data attributes
                const bookingId = this.dataset.id;
                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to cancel this booking?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, please'
                }).then((result) => {
                    // Step 1: Check if the admin confirmed the first dialog.
                    if (result.isConfirmed) {

                        // Step 2: If confirmed, immediately show the second dialog to ask for a reason.
                        Swal.fire({
                            input: "textarea",
                            inputLabel: "Reason for Cancellation",
                            inputPlaceholder: "Type your reason here...",
                            inputAttributes: {
                                "aria-label": "Type your reason here"
                            },
                            showCancelButton: true,
                            confirmButtonText: 'Submit Appeal',
                            // Optional: Add validation to ensure a reason is entered
                            inputValidator: (value) => {
                                if (!value) {
                                    return "You need to write a reason!";
                                }
                            }
                        }).then((reasonResult) => {
                            // Step 3: Check if the second dialog was confirmed and has a value.
                            if (reasonResult.isConfirmed && reasonResult.value) {

                                // Get the reason text from the textarea.
                                const reason = reasonResult.value;

                                // IMPORTANT: Encode the reason to make it safe to pass in a URL.
                                const encodedReason = encodeURIComponent(reason);

                                // Step 4: Redirect to your PHP script with BOTH the ID and the reason.
                                window.location.href = `CRUD/create_appeal.php?id=${bookingId}&reason=${encodedReason}`;
                            }
                        });
                    }
                });
            });
        });

        // Select all elements with the class '.appeal-room-btn'
        const appealButtons = document.querySelectorAll('.appeal-room-btn');

        // Loop through the correct variable 'appealButtons'
        appealButtons.forEach(button => {
            button.addEventListener('click', function(e) {
                // Prevent the default link behavior if it's an <a> tag
                e.preventDefault();

                // Get the reason from the data-appeal attribute
                const reason = this.dataset.appeal;

                Swal.fire({
                    title: "Cancellation Appeal Reason:",
                    // Use backticks (`) instead of single quotes (') to correctly display the variable
                    html: `<pre style="white-space: pre-wrap; text-align: left; margin-left: 1rem;">${reason}</pre>`,
                    confirmButtonText: 'Close'
                });
            });
        });

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
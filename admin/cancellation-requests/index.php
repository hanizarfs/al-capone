<?php
session_start();

//Delete user swal 
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

// Query ambil semua data dari tabel rooms
$sql = "SELECT bc.booking_id, u.username, bc.reason, bc.status , r.name, b.checkin_date, b.checkout_date, bc.requested_at, b.invoice_id, b.payment_method, b.booking_timestamp, bc.id
FROM booking_cancellations as bc
LEFT JOIN users AS u on bc.user_id = u.id
LEFT JOIN bookings AS b on bc.booking_id = b.id
LEFT JOIN rooms AS r on b.room_type = r.id

ORDER BY bc.id DESC";

$stmt = $mysqli->prepare($sql);
$stmt->execute();
$booking_cancellations = $stmt->get_result();

$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cancellation Requests | Al Capone</title>
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
    <!-- Aside -->
    <?php include_once __DIR__ . '/../sidebar.php'; ?>
    <!-- End of Aside -->

    <main class="col-lg-10" id="main">
        <!-- NavBar -->
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <!-- Logo -->
                <button class="btn btn-outline-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample" style="margin-right: 10px; padding: 2px 6px 2px 6px" id="sidebarshow">
                    <i class="bi bi-arrow-bar-right"></i>
                </button>
                <h4 class="fw-semibold mb-0">Cancellation Requests</h4>

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
                                <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
                                    <i class="bi bi-person me-2 opacity-50 theme-icon" style="font-size: 1rem"></i>
                                    Profile
                                    <svg class="bi ms-auto d-none" width="1em" height="1em">
                                        <path d="M1 1l4 4 4-4" />
                                    </svg>
                                </button>
                            </li>
                            <li>
                                <a href="../../logout.php" type="button" class="dropdown-item d-flex align-items-center" aria-pressed="false">
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

            <!-- Table for Cancellation Reqeusts -->
            <div class="table-responsive">
                <table id="dataTables" class="table table-striped border">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Usename</th>
                            <th scope="col">Reservation</th>
                            <th scope="col">Reason</th>
                            <th scope="col">Requested Date</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php

                        if ($booking_cancellations && $booking_cancellations->num_rows > 0) {
                            $row_number = 1;

                            while ($booking_query = $booking_cancellations->fetch_assoc()) {

                        ?>
                                <tr>
                                    <th scope="row"><?php echo $row_number++; ?></th>

                                    <td><?php echo htmlspecialchars($booking_query['username']); ?></td>

                                    <td> <button class="btn bg-blue info-btn"
                                            data-invoice-id="<?= htmlspecialchars($booking_query['invoice_id']) ?>"
                                            data-room-type="<?= htmlspecialchars($booking_query['name']) ?>"
                                            data-checkin="<?= htmlspecialchars($booking_query['checkin_date']) ?>"
                                            data-checkout="<?= htmlspecialchars($booking_query['checkout_date']) ?>"
                                            data-payment-method="<?= htmlspecialchars($booking_query['payment_method']) ?>"
                                            data-timestamp="<?= htmlspecialchars($booking_query['booking_timestamp']) ?>">
                                            Show Info
                                        </button></td>

                                    <td><?php echo htmlspecialchars($booking_query['reason']); ?></td>

                                    <td><?php echo htmlspecialchars($booking_query['requested_at']); ?></td>

                                    <?php if ($booking_query['status'] == "Appealed") { ?>
                                        <td><span class="badge bg-warning">Pending</span></td>
                                        <td>
                                            <button class="btn btn-success approve-btn" href="javascript:void(0);" data-id="<?= $booking_query['booking_id'] ?>" data-appeal="<?= $booking_query['id']?>">Approve</button>
                                            <button class="btn btn-danger reject-btn" href="javascript:void(0);" data-id="<?= $booking_query['booking_id'] ?>" data-appeal ="<?= $booking_query['id']?>">Reject</button>
                                        </td>
                                    <?php } elseif ($booking_query['status'] == "Rejected") { ?>
                                        <td><span class="badge bg-danger">Rejected</span></td>
                                        <td><button class="btn btn-danger why-btn" href="javascript:void(0);" data-id="<?= $booking_query['id'] ?>">">why?</button></td>
                                    <?php } else { ?>
                                        <td><span class="badge bg-success">Approve</span></td>
                                        <td><button class="btn btn-success why-btn" href="javascript:void(0);" data-id="<?= $booking_query['id'] ?>">">Why?</button></td>
                                    <?php } ?>
                                </tr>
                            <?php
                            }
                        } else {
                            ?>
                            <tr>
                                <td colspan-="5" class="text-center">0</td>
                                <td colspan-="5" class="text-center">Nothing found</td>
                                <td colspan-="5" class="text-center">Nothing found</td>
                                <td colspan-="5" class="text-center">Nothing found</td>
                                <td colspan-="5" class="text-center">Nothing found</td>
                                <td colspan-="5" class="text-center">Nothing found</td>
                                <td colspan-="5" class="text-center">--</td>
                            </tr>
                        <?php
                        }
                        $booking_cancellations->free();
                        $mysqli->close();
                        ?>

                        <tr>
                            <th scope="row">1</th>
                            <td>Mark</td>
                            <td>Villa Sunset</td>
                            <td>Change of plans</td>
                            <td>2023-06-01</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td>
                                <button class="btn btn-success approve-btn">Approve</button>
                                <button class="btn btn-danger reject-btn">Reject</button>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>Jacob</td>
                            <td>Villa Oasis</td>
                            <td>Family emergency</td>
                            <td>2023-06-02</td>
                            <td><span class="badge bg-warning">Pending</span></td>
                            <td>
                                <button class="btn btn-success approve-btn">Approve</button>
                                <button class="btn btn-danger reject-btn">Reject</button>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>John</td>
                            <td>Villa Dream</td>
                            <td>Weather issues</td>
                            <td>2023-06-03</td>
                            <td><span class="badge bg-success">Approved</span></td>
                            <td>
                                <button class="btn btn-secondary" disabled>Approved</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
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


        // Select all buttons with the class '.info-btn'
        const infoButtons = document.querySelectorAll('.info-btn');

        // Loop through each button and add a click listener
        infoButtons.forEach(button => {
            button.addEventListener('click', function(e) {

                // Get ALL data from the clicked button's 'dataset'
                const invoiceId = this.dataset.invoiceId;
                const roomType = this.dataset.roomType;
                const checkin = this.dataset.checkin;
                const checkout = this.dataset.checkout;
                const paymentMethod = this.dataset.paymentMethod;
                const timestamp = this.dataset.timestamp;

                // Optional: Format the timestamp for better readability
                const formattedTimestamp = new Date(timestamp).toLocaleString('en-GB', {
                    day: 'numeric',
                    month: 'long',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                // Display the data in the SweetAlert pop-up
                Swal.fire({
                    title: "<strong>Booking Information</strong>",
                    icon: 'info',
                    html: `
                <div style="text-align: left; padding: 0 1rem;">
                    <b>Invoice ID:</b> ${invoiceId}<br>
                    <b>Room Type:</b> ${roomType}<br>
                    <b>Check-in:</b> ${checkin}<br>
                    <b>Check-out:</b> ${checkout}<br>
                    <b>Payment:</b> ${paymentMethod}<br>
                    <b>Booked On:</b> ${formattedTimestamp}
                </div>
            `,
                    confirmButtonText: 'Close'
                });
            });
        });


        // Add SweetAlert2 confirmation for delete
        const approveButton = document.querySelectorAll('.approve-btn');
        approveButton.forEach(button => {
            button.addEventListener('click', function(e) {
                // Prevent the default link behavior
                e.preventDefault();

                // Get the user ID and username from the data attributes
                const bookingId = this.dataset.id;
                const appealId = this.dataset.appeal;
                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to approve this appeal`,
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
                            inputLabel: "Reason for Approving",
                            inputPlaceholder: "Type your reason here...",
                            inputAttributes: {
                                "aria-label": "Type your reason here"
                            },
                            showCancelButton: true,
                            confirmButtonText: 'Approve appeal',
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
                                window.location.href = `../CRUD/appeal_process.php?id=${bookingId}&reason=${encodedReason}& type=approved&appealId=${appealId}`;
                            }
                        });
                    }
                });
            });
        });

        const rejectButton = document.querySelectorAll('.reject-btn');
        rejectButton.forEach(button => {
            button.addEventListener('click', function(e) {
                // Prevent the default link behavior
                e.preventDefault();

                // Get the user ID and username from the data attributes
                const bookingId = this.dataset.id;
                const appealId = this.dataset.appeal;
                Swal.fire({
                    title: 'Are you sure?',
                    text: `You are about to reject this appeal`,
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
                            inputLabel: "Reason for Rejecting",
                            inputPlaceholder: "Type your reason here...",
                            inputAttributes: {
                                "aria-label": "Type your reason here"
                            },
                            showCancelButton: true,
                            confirmButtonText: 'Reject appeal',
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
                                window.location.href = `../CRUD/appeal_process.php?id=${bookingId}&reason=${encodedReason}&type=rejected&appealId=${appealId}`;
                            }
                        });
                    }
                });
            });
        });

        // swal delete
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
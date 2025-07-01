<?php
session_start();
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$admin_message = isset($_SESSION['admin_message']) ? $_SESSION['admin_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['success_message'], $_SESSION['admin_message'], $_SESSION['error_message']);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Login | Al Capone</title>
        <link rel="icon" type="image/x-icon" href="./assets/img/Logo.webp" />

        <!-- Bootstrap CSS -->
        <link
            rel="stylesheet"
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"
        />

        <!-- Bootstrap Icon -->
        <link
            rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css"
        />

        <!-- CSS -->
        <link rel="stylesheet" href="./assets/css/style.css" />
    </head>
    <body>
        <!-- Start Main Content -->
        <main style="height: 100vh">
            <!-- Start Login Section -->
            <section id="login" class="h-100">
                <div class="container-fluid h-100">
                    <div class="row h-100">
                        <div class="col-12 col-md-6 p-0 d-none d-md-block">
                            <img
                                src="./assets/img/Bali.webp"
                                alt="Gambar"
                                class="img-fluid object-fit-cover"
                                style="width: 100%; height: 100%"
                                loading="lazy"
                            />
                        </div>
                        <div
                            class="col-12 col-md-6 d-flex justify-content-center align-items-center"
                        >
                            <div class="container">
                                <div
                                    class="d-flex justify-content-center align-items-center h-100"
                                >
                                    <div class="login-form w-100 px-3 px-md-5">
                                        <div class="mb-4">
                                            <a
                                                href="index.php"
                                                class="btn btn-outline-secondary mb-4"
                                            >
                                                <i class="bi bi-arrow-left"></i>
                                                Go Back
                                            </a>
                                            <h1 class="fw-bold">
                                                Welcome Back!
                                            </h1>
                                            <p>
                                                Please enter your credentials to
                                                access your account. We’re
                                                excited to have you back!
                                            </p>
                                        </div>
                                        <form class="mb-4" action="CRUD/login.php" method="post" id="loginForm">
                                            <div class="mb-3">
                                                <label class="form-label"
                                                    >Email address
                                                    <span class="text-danger"
                                                        >*</span
                                                    ></label
                                                >
                                                <input
                                                    type="email"
                                                    class="form-control"
                                                    name="email"
                                                    required
                                                />
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label"
                                                    >Password
                                                    <span class="text-danger"
                                                        >*</span
                                                    ></label
                                                >
                                                <div class="input-group">
                                                    <input
                                                        type="password"
                                                        class="form-control"
                                                        name="password"
                                                        id="password"
                                                        required
                                                    />
                                                    <button
                                                        class="btn btn-outline-secondary"
                                                        type="button"
                                                        id="toggle-password"
                                                    >
                                                        <i
                                                            class="bi bi-eye-slash"
                                                            id="eye-icon"
                                                        ></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="mb-3 text-end">
                                                <a href="" class="text-blue"
                                                    >Forgot Password?</a
                                                >
                                            </div>
                                            <button type="login" class="btn bg-blue" id="login-btn" name="login">
                                                Login
                                            </button>
                                        </form>
                                        <p class="text-center">
                                            Don't have an account?
                                            <a
                                                href="signup.php"
                                                class="text-blue"
                                                >Sign up here</a
                                            >
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <!-- End Main Content -->

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

        <!-- SweetAlert2 CDN -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <!-- Main JS -->
        <script src="./assets/js/main.js"></script>

        <script>
            //swal success/admin/error
            <?php if($success_message): ?>
            Swal.fire({
                title: 'Success!',
                text: '<?php echo $success_message; ?>',
                icon: 'success',
                showConfirmButton: false,
                timer: 2000,
                willClose: () => {
                    window.location.href = 'index.php';
                }
            });
            <?php endif; ?>
            
            <?php if($admin_message): ?>
                Swal.fire({
                    title: "Login Success!",
                    text: '<?php echo $admin_message; ?>',
                    showDenyButton: true,
                    confirmButtonText: "Dashboard",
                    denyButtonText: `Main Page`
                    }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Will be redirected to dashboard shortly!',
                            text: '',
                            icon: 'info',
                            showConfirmButton: false,
                            timer: 2000,
                            willClose: () => {
                                window.location.href = 'admin/dashboard.php';
                            }}
                        );
                    } else if (result.isDenied) {
                        Swal.fire({
                            title: 'Will be redirected to main page shortly!',
                            text: '',
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 2000,
                            willClose: () => {
                                window.location.href = 'index.php';
                            }}
                        );
                    }
                });
            <?php endif; ?>

            <?php if($error_message): ?>
                Swal.fire({
                    title: 'Error!',
                    text: '<?php echo $error_message; ?>',
                    icon: 'error',
                    confirmButtonText: 'Try Again'
                });
            <?php endif; ?>

            // pass toggle
            const togglePassword = document.getElementById("toggle-password");
            const passwordField = document.getElementById("password");
            const eyeIcon = document.getElementById("eye-icon");

            togglePassword.addEventListener("click", function () {
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
        </script>
    </body>
</html>

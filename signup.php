<?php
session_start();
$success_message = isset($_SESSION['success_message']) ? $_SESSION['success_message'] : '';
$error_message = isset($_SESSION['error_message']) ? $_SESSION['error_message'] : '';
unset($_SESSION['success_message'], $_SESSION['error_message']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up | Al Capone</title>
    <link rel="icon" type="image/x-icon" href="./assets/img/Logo.webp" />

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" />

    <!-- Bootstrap Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.11.3/font/bootstrap-icons.min.css" />

    <!-- CSS -->
    <link rel="stylesheet" href="./assets/css/style.css" />
</head>

<body>
    <!-- Start Main Content -->
    <main style="height: 100vh">
        <!-- Start Register Section -->
        <section id="register" class="h-100">
            <div class="container-fluid h-100">
                <div class="row h-100">
                    <div class="col-12 col-md-6 p-0 d-none d-md-block">
                        <img src="./assets/img/Bali.webp" alt="Gambar" class="img-fluid object-fit-cover" style="width: 100%; height: 100%" loading="lazy" />
                    </div>
                    <div class="col-12 col-md-6 d-flex justify-content-center align-items-center">
                        <div class="container">
                            <div class="d-flex justify-content-center align-items-center h-100">
                                <div class="login-form w-100 px-3 px-md-5">
                                    <div class="mb-4">
                                        <a href="login.php" class="btn btn-outline-secondary mb-4">
                                            <i class="bi bi-arrow-left"></i>
                                            Go Back
                                        </a>
                                        <h1 class="fw-bold">Create Your Account</h1>
                                        <p>Join us today! Sign up and start enjoying exclusive access to your personalized dashboard.</p>
                                    </div>
                                    <form class="mb-4" action="CRUD/register.php" method="post" id="registerForm">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">First Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="name_first" required />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Last Name <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="name_last" required />
                                                </div>
                                            </div>


                                            <div class="mb-3">
                                                <label class="form-label">Username <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" name="username" required />
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Email address <span class="text-danger">*</span></label>
                                                    <input type="email" class="form-control" name="email" required />
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label">Phone Number <span class="text-danger">*</span></label>
                                                    <input type="number" class="form-control" name="phone" required />
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Password <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="password" name="password" required />
                                                    <button class="btn btn-outline-secondary" type="button" id="toggle-password">
                                                        <i class="bi bi-eye-slash" id="eye-icon"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                                <div class="input-group">
                                                    <input type="password" class="form-control" id="password-confirm" required />
                                                    <button class="btn btn-outline-secondary" type="button" id="toggle-password-confirm">
                                                        <i class="bi bi-eye-slash" id="eye-icon-confirm"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn bg-blue" id="register-btn" name="register">Register</button>
                                    </form>
                                    <p class="text-center">
                                        Already have an account?
                                        <a href="login.php" class="text-blue">Login here</a>
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
        //swal success/error
        <?php if ($success_message): ?>
            Swal.fire({
                title: 'Success!',
                text: '<?php echo $success_message; ?>',
                icon: 'success',
                showConfirmButton: false,
                timer: 2000,
                willClose: () => {
                    window.location.href = 'login.php';
                }
            });
        <?php endif; ?>

        <?php if ($error_message): ?>
            Swal.fire({
                title: 'Error!',
                text: '<?php echo $error_message; ?>',
                icon: 'error',
                confirmButtonText: 'Try Again'
            });
        <?php endif; ?>

        //pass toggle eye function
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

        // swal check pass
        document.getElementById("registerForm").addEventListener("submit", function(e) {
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
<?php
session_start();

// Only process POST requests
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {

    // Include database config
    include_once("../config.php");

    // Get trimmed input data directly
    $n_first = trim($_POST['name_first']);
    $n_last = trim($_POST['name_last']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if username or email already exists using a prepared statement
    $check_stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = 'Username or Email already exists!';
        header('Location: ../signup.php?error=exists');
    } else {
        // Insert new user using a prepared statement
        $insert_stmt = $mysqli->prepare("INSERT INTO users(username, email, password_hash, first_name, last_name, phone) VALUES(?, ?, ?, ?, ?, ?)");
        $insert_stmt->bind_param("ssssss", $username, $email, $password_hash, $n_first, $n_last, $phone);

        if ($insert_stmt->execute()) {
            $_SESSION['success_message'] = 'Registration Successful! You can now log in.';
            header('Location: ../signup.php?success=1');
            exit();
        } else {
            $_SESSION['error_message'] = 'Registration failed. Please try again.';
            header('Location: ../signup.php?error=failed');
            exit();
        }
        // Close the insert statement
        $insert_stmt->close();
    }

    // Close the check statement and the connection
    $check_stmt->close();
    $mysqli->close();
    exit();
} else {
    // Redirect if not a POST request
    header('Location: ../signup.php');
    exit();
}

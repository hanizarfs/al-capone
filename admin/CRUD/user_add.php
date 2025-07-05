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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register'])) {

    // Include database config
    include_once("../../config.php");

    // Get trimmed input data directly
    $n_first = trim($_POST['name_first']);
    $n_last = trim($_POST['name_last']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $status = trim($_POST['status']);
    $reason = trim($_POST['reason']);
    

    // Check if username or email already exists using a prepared statement
    $check_stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $check_stmt->bind_param("ss", $username, $email);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = 'Username or Email already exists!';
        header('Location: ../userManagement.php?error=exists');
    } else {
        // Insert new user using a prepared statement
        $insert_stmt = $mysqli->prepare("INSERT INTO users(status, username, email, password_hash, first_name, last_name, phone) VALUES(?, ?, ?, ?, ?, ?, ?)");
        $insert_stmt->bind_param("issssss", $status, $username, $email, $password_hash, $n_first, $n_last, $phone);

        if ($insert_stmt->execute()) {
            $_SESSION['success_message'] = 'User added Successfully!';

            $get_stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $get_stmt->bind_param("ss", $username, $email);
            $get_stmt->execute();
            $result = $get_stmt->get_result();
            $user = $result->fetch_assoc();
            
            $action_taken = "Added User";
            $admin_id = $_SESSION['user_id'];
            $log_stmt = $mysqli->prepare("INSERT INTO user_logs(admin_id, action, affected_user, reason) VALUES (?, ?, ?, ?)");
            $log_stmt->bind_param("isis", $admin_id, $action_taken, $user['id'], $reason);
            $log_stmt->execute();
            $log_stmt->close();

            header('Location: ../userManagement.php?success=1');
            exit();
        } else {
            $_SESSION['error_message'] = 'User not added. Please try again.';
            header('Location: ../userManagement.php?error=failed');
            exit();
        }
        // Close the insert statement
        $insert_stmt->close();
    }

    // Close the check statement and the connection
    $check_stmt->close();
    $mysqli->close();
    exit();
}

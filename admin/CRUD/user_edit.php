<?php
session_start();

// --- Security Guard Clauses ---
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('location: ../../login.php');
    exit;
}
if ($_SESSION['user_status'] == 1) { // Assuming status 1 is a regular user
    header('location: ../../index.php');
    exit;
}

// --- Process form only if submitted ---
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit'])) {
    include_once("../../config.php");

    // --- 1. Get All Form Data (No password field anymore) ---
    $user_id_to_edit = $_POST['user_id'];
    $n_first = trim($_POST['name_first']);
    $n_last = trim($_POST['name_last']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);

    // --- 2. Correct "Already Exists" Check ---
    // Check if the new username/email is already taken by ANOTHER user.
    $check_stmt = $mysqli->prepare("SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?");
    $check_stmt->bind_param("ssi", $username, $email, $user_id_to_edit);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        $_SESSION['error_message'] = 'Username or Email already exists for another user!';
        header('Location: ../userManagement.php?error=exists');
        exit();
    }
    $check_stmt->close();


    // --- 3. The Simplified UPDATE Query ---
    // The SQL query is now static since we are never updating the password.
    $sql = "UPDATE users SET first_name = ?, last_name = ?, username = ?, email = ?, phone = ?, updated_at = NOW() WHERE id = ?";

    $update_stmt = $mysqli->prepare($sql);

    // The types and parameters are now fixed. 5 strings and 1 integer.
    $update_stmt->bind_param("sssssi", $n_first, $n_last, $username, $email, $phone, $user_id_to_edit);

    // --- 4. Execute the Update ---
    if ($update_stmt->execute()) {
        $_SESSION['success_message'] = 'User details updated successfully!';

        // Log the action
        $action_taken = "Edited User Profile";
        $admin_username = $_SESSION['username'];
        $log_stmt = $mysqli->prepare("INSERT INTO user_logs(admin_uname, action, affected_user) VALUES (?, ?, ?)");
        $log_stmt->bind_param("sss", $admin_username, $action_taken, $email);
        $log_stmt->execute();
        $log_stmt->close();

        header('Location: ../userManagement.php?success=1');
    } else {
        $_SESSION['error_message'] = 'Failed to update user. Please try again.';
        header('Location: ../userManagement.php?error=failed');
    }

    $update_stmt->close();
    $mysqli->close();
    exit();
}
?>
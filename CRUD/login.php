<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['login'])) {
    include_once("../config.php");

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $mysqli->prepare("SELECT id, username, email, password_hash, status FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($result->num_rows === 1){
        $user = $result->fetch_assoc();

        if(password_verify($password, $user['password_hash'])){
            session_regenerate_id(true);

            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_status'] = $user['status'];

            

            if($user['status'] == 1){
                $_SESSION['success_message'] = 'Login Successful! Welcome back :)';
                header('Location: ../login.php?success=1');
                exit();
            }else{
                $_SESSION['admin_message'] = 'Welcome back Admin :)';
                header('Location: ../login.php?admin=1');
                exit();
            }
        }else{
            $_SESSION['error_message'] = 'Invalid Credential Information';
            header('Location: ../login.php?error=failed');
            exit();
        }
        
        $stmt->close();
        $mysqli->close();

    }else{
        $_SESSION['error_message'] = 'User not found :(';
        header('Location: ../login.php?error=notFound');
        exit();
    }
    
}else {
        header("Location: ../login.php");
        exit();
}
?>
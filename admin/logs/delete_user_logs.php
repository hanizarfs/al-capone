<?php

require_once('../../config.php');

$mysqli->query("DELETE FROM user_logs");
$_SESSION['flash'] = ['type' => 'success', 'message' => 'User logs cleared successfully.'];
header("Location: index.php");
exit();

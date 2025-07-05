<?php

require_once('../../config.php');

$mysqli->query("DELETE FROM cancellation_logs");
$_SESSION['flash'] = ['type' => 'success', 'message' => 'Cancellation logs cleared successfully.'];
header("Location: index.php");
exit();

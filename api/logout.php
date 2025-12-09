<?php
require_once '../config/database.php';

session_destroy();
header('Location: ../main.php');
exit;
?>

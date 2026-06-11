<?php
session_start();

// unset all session variables
$_SESSION = [];

// destroy session
session_destroy();

// 🔥 force no cache
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// redirect
header("Location: index.php");
exit();
?>
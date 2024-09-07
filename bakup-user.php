<?php
session_start();
$current_url = $_SERVER['REQUEST_URI'];

if (!isset($_SESSION['user_id']) && strpos($current_url, 'user_management.php') === false) {
    // Only store the redirect URL if it's not already set and we're not on the login page
    if (!isset($_SESSION['redirect_url'])) {
        $_SESSION['redirect_url'] = $current_url;
    }
    header("Location: user_management.php");
    exit();
}
?> 

if (!isset($_SESSION['user_id'])) {
    header("Location: user_management.php");
    exit();
}

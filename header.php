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
<!-- header.php -->
<link rel="stylesheet" href="styles.css">
<header>
    <nav>
        <ul>
            <li><a href="index.php">Home</a></li>
            <li><a href="resource-library.php">Knowledge Hub</a></li>
            <li><a href="market-prices.php">Market Prices</a></li>
            <li><a href="transaction-records.php">Transactions</a></li>
            <li><a href="negotiation-tools.php">Negotiations</a></li>
            <li><a href="weather-forecasts.php">Weather Today</a></li>
            <!-- Check if user is logged in -->
            <?php if(isset($_SESSION['user_id'])): ?>
                <li><a href="logout.php">Logout</a></li>
                <li><?php echo htmlspecialchars($_SESSION['username']); ?></li>
            <?php else: ?>
                <li><a href="user_management.php">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

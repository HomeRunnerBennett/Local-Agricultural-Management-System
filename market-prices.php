<?php
if (!isset($_SESSION['user_id'])) {
    // User is not logged in, handle accordingly
} else {
    // User is logged in, you can display their name or other info
    $username = $_SESSION['username'];
}
?>   
   <link rel="stylesheet" href="styles.css">
	
<?php 
include 'header.php';
include 'config.php';

?>
<div class="container section">
    <h2>Market Prices</h2>
    <div class="price-list">
        <div class="price-item">
            <h3>Maize</h3>
            <p>Current Price: Mwk20,000 per bag</p>
        </div>
        <div class="price-item">
            <h3>Wheat</h3>
            <p>Current Price: Mwk47,000 per bag</p>
        </div>
        <!-- Additional price items can be added here -->
    </div>
</div>

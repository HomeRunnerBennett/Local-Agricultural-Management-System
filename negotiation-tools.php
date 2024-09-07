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
    <h2>Negotiation Tools</h2>
    <div class="negotiation-list">
        <div class="negotiation-item">
            <h3>Negotiation with Lumbani Munthali</h3>
            <p>Proposed Price: Mwk18,000 per bag for Maize</p>
            <button class="btn">Accept</button>
            <button class="btn">Counter Offer</button>
        </div>
        <div class="negotiation-item">
            <h3>Negotiation with Jimmy Kadansana</h3>
            <p>Proposed Price: Mwk45,000 per bag for Wheat</p>
            <button class="btn">Accept</button>
            <button class="btn">Counter Offer</button>
        </div>
        <!-- Additional negotiation items can be added here -->
    </div>
</div>

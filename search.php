<?php

if (!isset($_SESSION['user_id'])) {
    // User is not logged in, handle accordingly
} else {
    // User is logged in, you can display their name or other info
    $username = $_SESSION['username'];
}
?>   
<!-- search.php -->
    <link rel="stylesheet" href="styles.css">
	
<?php 
include 'config.php';


?>
<div class="search-container">
    <h2>Search Farm Activities</h2>
    <div class="form-group">
        <label for="search">Search:</label>
        <input type="text" id="search" name="search" placeholder="Enter activity description...">
    </div>
    <div id="results">
        <!-- Search results will be displayed here -->
    </div>
</div>

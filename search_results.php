<!-- search_results.php -->

<?php
<?php

if (!isset($_SESSION['user_id'])) {
    // User is not logged in, handle accordingly
} else {
    // User is logged in, you can display their name or other info
    $username = $_SESSION['username'];
}
?>   
include 'config.php';

if (isset($_GET['q'])) {
    $search = $conn->real_escape_string($_GET['q']);
    
    $query = "SELECT * FROM FarmActivity WHERE description LIKE '%$search%'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="search-item">';
            echo '<h3>' . htmlspecialchars($row['description']) . '</h3>';
            echo '<p>Date: ' . htmlspecialchars($row['activity_date']) . '</p>';
            echo '</div>';
        }
    } else {
        echo '<p>No results found.</p>';
    }
}
?>

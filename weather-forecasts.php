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
<?php
$query = "SELECT * FROM WeatherForecast ORDER BY date DESC LIMIT 1";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    // Fetch the latest weather forecast
    $forecast = $result->fetch_assoc();
} else {
    $forecast = null;
}

$conn->close();
?>
<body>
    <div class="weather-container">
        <h2>Today's Weather</h2>
        <?php if ($forecast): ?>
            <p><strong>Date:</strong> <?php echo htmlspecialchars($forecast['date']); ?></p>
            <p><strong>Temperature:</strong> <?php echo htmlspecialchars($forecast['temperature']); ?> °C</p>
            <p><strong>Precipitation:</strong> <?php echo htmlspecialchars($forecast['precipitation']); ?> mm</p>
            <p><strong>Wind Speed:</strong> <?php echo htmlspecialchars($forecast['wind_speed']); ?> km/h</p>
            <p><strong>Humidity:</strong> <?php echo htmlspecialchars($forecast['humidity']); ?>%</p>
        <?php else: ?>
            <p>No weather data available.</p>
        <?php endif; ?>
    </div>
</body>
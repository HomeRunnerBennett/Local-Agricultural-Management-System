<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Localized Agricultural Management System</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php 
	
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    // Redirect to login if not logged in
    header('Location: user_management.php');
    exit;
}
// Check if the database already exists
$db_exists = $conn->select_db($dbname);

if (!$db_exists) {
    // If the database does not exist, run the setup script
    $sql = file_get_contents('setup.sql');

    if ($conn->multi_query($sql) === TRUE) {
        echo "Database setup successfully.";
    } else {
        echo "Error setting up database: " . $conn->error;
    }

    // Ensure all queries have completed
    while ($conn->more_results() && $conn->next_result()) {;}
}

// Close connection
$conn->close();


// Include header
include 'header.php';
include 'search.php';
	?>
</body>
</html>

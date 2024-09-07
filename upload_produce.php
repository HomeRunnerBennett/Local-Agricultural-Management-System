<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id']) && $_SESSION['role'] == 'Farmer') {
    $farmer_id = $_SESSION['user_id'];
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    $query = "INSERT INTO Produce (farmer_id, item_name, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('isid', $farmer_id, $item_name, $quantity, $price);
    if ($stmt->execute()) {
        header('Location: transaction-records.php');
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

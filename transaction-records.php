<link rel="stylesheet" href="styles.css">	
<?php
session_start();
$current_url = $_SERVER['REQUEST_URI'];
include 'header.php';
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login if not logged in
    header('Location: user_management.php');
    exit;
}

// Fetch user details
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Fetch transactions from the database
$query = "SELECT t.transaction_id, t.price, t.transaction_date, 
                 b.name as buyer_name, s.name as seller_name, p.item_name
          FROM Transactions t
          JOIN Farmer b ON t.buyer_id = b.farmer_id
          JOIN Farmer s ON t.seller_id = s.farmer_id
          JOIN Produce p ON t.produce_id = p.produce_id";
$result = $conn->query($query);
?>

<link rel="stylesheet" href="styles.css">

<div class="container section">
    <h2>Transaction Records</h2>
    <div class="transaction-list">
        <?php while ($row = $result->fetch_assoc()): ?>
            <div class="transaction-item">
                <h3>Transaction #<?php echo $row['transaction_id']; ?></h3>
                <p>Buyer: <?php echo htmlspecialchars($row['buyer_name']); ?></p>
                <p>Seller: <?php echo htmlspecialchars($row['seller_name']); ?></p>
                <p>Item: <?php echo htmlspecialchars($row['item_name']); ?></p>
                <p>Price: Mwk<?php echo number_format($row['price'], 2); ?></p>
                <p>Date: <?php echo $row['transaction_date']; ?></p>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- Farmers' form to upload produce -->
    <?php if ($_SESSION['role'] == 'Farmer'): ?>
        <h2>Upload Your Produce</h2>
        <form method="POST" action="upload_produce.php">
            <div class="form-group">
                <label for="item_name">Item Name</label>
                <input type="text" id="item_name" name="item_name" required>
            </div>
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" id="quantity" name="quantity" required>
            </div>
            <div class="form-group">
                <label for="price">Price per Unit</label>
                <input type="number" id="price" name="price" step="0.01" required>
            </div>
            <button type="submit" class="btn">Upload</button>
        </form>
    <?php endif; ?>
</div>
<?php if ($_SESSION['role'] == 'Farmer' && $row['seller_id'] == $_SESSION['user_id']): ?>
    <!-- Show edit and delete options for the farmer's own produce -->
    <a href="edit_produce.php?produce_id=<?php echo $row['produce_id']; ?>">Edit</a>
    <a href="delete_produce.php?produce_id=<?php echo $row['produce_id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
<?php endif; ?>

<?php if ($_SESSION['role'] == 'Administrator'): ?>
    <!-- Admin can edit any produce -->
    <a href="edit_produce.php?produce_id=<?php echo $row['produce_id']; ?>">Edit</a>
<?php endif; ?>
<!-- Inside the produce-item div -->
<button class="read-more-btn" data-id="<?php echo $row['produce_id']; ?>">Read More</button>
<div class="more-info" id="more-info-<?php echo $row['produce_id']; ?>" style="display:none;">
    <p>Quantity: <?php echo $row['quantity']; ?></p>
    <p>Price: Mwk<?php echo number_format($row['price'], 2); ?></p>
</div>

<script>
    document.querySelectorAll('.read-more-btn').forEach(button => {
        button.addEventListener('click', () => {
            const moreInfo = document.getElementById('more-info-' + button.dataset.id);
            if (moreInfo.style.display === 'none') {
                moreInfo.style.display = 'block';
                button.textContent = 'Show Less';
            } else {
                moreInfo.style.display = 'none';
                button.textContent = 'Read More';
            }
        });
    });
</script>

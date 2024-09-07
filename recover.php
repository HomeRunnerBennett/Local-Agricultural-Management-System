<!-- recover.php -->
<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $token = bin2hex(random_bytes(50));

    $sql = "SELECT * FROM UserAccount WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Store token in database
        $sql = "UPDATE UserAccount SET reset_token='$token', reset_expires=DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE email='$email'";
        $conn->query($sql);

        // Send reset email
        $resetLink = "http://yourwebsite.com/reset_password.php?token=" . $token;
        $message = "Click the following link to reset your password: " . $resetLink;
        mail($email, "Password Recovery", $message);

        echo "Recovery email sent!";
    } else {
        echo "No account associated with this email!";
    }
}
?>

<form method="POST" action="recover.php">
    <h2>Recover Password</h2>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <button type="submit">Send Recovery Email</button>
</form>

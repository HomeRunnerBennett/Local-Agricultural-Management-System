<?php

include 'header.php';
include 'config.php';

// Fetch resources from the database
$query = "SELECT * FROM Resources ORDER BY created_at DESC";
$result = $conn->query($query);

?>
<link rel="stylesheet" href="styles.css">

<div class="container section">
    <h2>Resource Library</h2>
    <div class="resource-list">
        <?php while($row = $result->fetch_assoc()): ?>
        <div class="resource-item">
            <h3><?php echo htmlspecialchars($row['title']); ?></h3>
            <p><?php echo htmlspecialchars($row['description']); ?></p>
            <button class="btn" onclick="toggleContent(<?php echo $row['id']; ?>)">Read More</button>
            <div id="content-<?php echo $row['id']; ?>" style="display:none;">
                <p><?php echo nl2br(htmlspecialchars($row['content'])); ?></p>
                <?php if (!empty($row['attachments'])): ?>
                <div class="attachments">
                    <?php foreach (explode(',', $row['attachments']) as $file): ?>
                    <a href="<?php echo $file; ?>" target="_blank"><?php echo basename($file); ?></a>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endwhile; ?>
    </div>
</div>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $content = $_POST['content'];
    $attachments = '';

    if (!empty($_FILES['attachments']['name'][0])) {
        $attachment_files = [];
        foreach ($_FILES['attachments']['name'] as $key => $name) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename($_FILES['attachments']['name'][$key]);
            if (move_uploaded_file($_FILES['attachments']['tmp_name'][$key], $target_file)) {
                $attachment_files[] = $target_file;
            }
        }
        $attachments = implode(',', $attachment_files);
    }

    $query = "INSERT INTO ResourceLibrary (title, description, content, attachments) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssss', $title, $description, $content, $attachments);

    if ($stmt->execute()) {
        header("Location: resource_library.php"); // Redirect to the resource library page
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<div class="container section">
    <h2>Add New Resource</h2>
    <?php if (isset($error_message)): ?>
    <p style="color:red;"><?php echo $error_message; ?></p>
    <?php endif; ?>
    <form method="POST" action="" enctype="multipart/form-data">
		<div class="form-group">
            <label for="title">Title</label>
            <input type="text" id="title" name="title" required>
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <input type="text" id="description" name="description" required>
        </div>

        <div class="form-group">
            <label for="content">Content</label>
            <textarea id="content" name="content" rows="8" required></textarea>
        </div>

        <div class="form-group">
            <label for="attachments">Attachments (Images/Videos)</label>
            <input type="file" id="attachments" name="attachments[]" accept="image/*,video/*" multiple>
        </div>
        <button type="submit" class="btn">Add Resource</button>
    </form>
</div>

<script>
    // JavaScript for "Read More" functionality
    document.querySelectorAll('.read-more').forEach(button => {
        button.addEventListener('click', function() {
            const content = this.nextElementSibling;
            if (content.style.display === "none") {
                content.style.display = "block";
                this.textContent = "Show Less";
            } else {
                content.style.display = "none";
                this.textContent = "Read More";
            }
        });
    });
</script>

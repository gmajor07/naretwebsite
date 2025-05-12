<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $content = trim($_POST['content']);
    
    // Update in database
    $stmt = $conn->prepare("UPDATE services_content SET title = ?, content = ? WHERE id = 1");
    $stmt->bind_param("ss", $title, $content);
    $stmt->execute();
    
    $_SESSION['message'] = "Content updated successfully!";
    header("Location: edit_content.php");
    exit;
}

// Get current content
$servicesContent = $conn->query("SELECT * FROM services_content WHERE id = 1")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Services Content</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
        <?php include 'navbar.php'; ?>

    <div class="container py-5">
        <h1>Edit Services Content</h1>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?= $_SESSION['message'] ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        
        <form method="POST">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" class="form-control" id="title" name="title" 
                       value="<?= htmlspecialchars($servicesContent['title'] ?? '') ?>" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea class="form-control" id="content" name="content" rows="5" required><?= 
                    htmlspecialchars($servicesContent['content'] ?? '') 
                ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</body>
</html>
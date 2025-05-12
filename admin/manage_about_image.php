<?php
session_start();
require_once '../includes/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['about_image'])) {
    try {
        // Simple validation
        if ($_FILES['about_image']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Upload error occurred");
        }

        // Check if image
        $allowed = ['image/jpeg', 'image/png', 'image/webp'];
        if (!in_array($_FILES['about_image']['type'], $allowed)) {
            throw new Exception("Only JPG, PNG, or WEBP images allowed");
        }

        // Upload directory
        $uploadDir = '../assets/img/about/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate filename
        $filename = 'about_' . time() . '.' . pathinfo($_FILES['about_image']['name'], PATHINFO_EXTENSION);
        $destination = $uploadDir . $filename;

        // Move file
        if (!move_uploaded_file($_FILES['about_image']['tmp_name'], $destination)) {
            throw new Exception("Failed to save image");
        }

        // Store in database (we'll only keep one image)
        $conn->query("DELETE FROM about_images"); // Remove old entries
        $stmt = $conn->prepare("INSERT INTO about_images (image_path) VALUES (?)");
        $webPath = 'assets/img/about/' . $filename;
        $stmt->bind_param("s", $webPath);
        $stmt->execute();

        $_SESSION['message'] = "Image updated successfully!";
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
    header("Location: manage_about_image.php");
    exit;
}

// Get current image
$currentImage = $conn->query("SELECT image_path FROM about_images ORDER BY uploaded_at DESC LIMIT 1")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage About Image</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-4">
        <h2>Update About Section Image</h2>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?= $_SESSION['message'] ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Select New Image</label>
                        <input type="file" name="about_image" class="form-control" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload Image</button>
                </form>
                
                <?php if ($currentImage): ?>
                <div class="mt-4">
                    <h5>Current Image:</h5>
                    <img src="../<?= htmlspecialchars($currentImage['image_path']) ?>" 
                         class="img-fluid mt-2" style="max-height: 300px;">
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
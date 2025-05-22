<?php
session_start();
require_once '../includes/db.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Handle new image upload
        if (isset($_FILES['cta_image'])) {
            // Simple validation
            if ($_FILES['cta_image']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("Upload error occurred");
            }

            // Check if image
            $allowed = ['image/jpeg', 'image/png', 'image/webp'];
            if (!in_array($_FILES['cta_image']['type'], $allowed)) {
                throw new Exception("Only JPG, PNG, or WEBP images allowed");
            }

            // Upload directory
            $uploadDir = '../assets/img/cta/';
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            // Generate filename
            $filename = 'cta_' . time() . '.' . pathinfo($_FILES['cta_image']['name'], PATHINFO_EXTENSION);
            $destination = $uploadDir . $filename;

            // Move file
            if (!move_uploaded_file($_FILES['cta_image']['tmp_name'], $destination)) {
                throw new Exception("Failed to save image");
            }

            // Store in database
            $stmt = $conn->prepare("INSERT INTO cta_images (image_path) VALUES (?)");
            $webPath = 'assets/img/cta/' . $filename;
            $stmt->bind_param("s", $webPath);
            $stmt->execute();
            
            $_SESSION['message'] = "CTA image uploaded successfully!";
        }
        
        // Handle image activation
        if (isset($_POST['activate_image'])) {
            $imageId = (int)$_POST['activate_image'];
            
            // First deactivate all images
            $conn->query("UPDATE cta_images SET is_active = FALSE");
            
            // Then activate the selected one
            $stmt = $conn->prepare("UPDATE cta_images SET is_active = TRUE WHERE id = ?");
            $stmt->bind_param("i", $imageId);
            $stmt->execute();
            
            $_SESSION['message'] = "CTA image activated successfully!";
        }
        
        // Handle image deletion
        if (isset($_POST['delete_image'])) {
            $imageId = (int)$_POST['delete_image'];
            
            // Get image path first
            $result = $conn->query("SELECT image_path FROM cta_images WHERE id = $imageId");
            if ($result->num_rows > 0) {
                $image = $result->fetch_assoc();
                $filePath = '../' . $image['image_path'];
                
                // Delete from database
                $conn->query("DELETE FROM cta_images WHERE id = $imageId");
                
                // Delete file
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                
                $_SESSION['message'] = "CTA image deleted successfully!";
            }
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
    header("Location: manage_cta_image.php");
    exit;
}

// Get all CTA images
$ctaImages = $conn->query("SELECT * FROM cta_images ORDER BY uploaded_at DESC");
// Get active CTA image
$activeImage = $conn->query("SELECT * FROM cta_images WHERE is_active = TRUE LIMIT 1")->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage CTA Images</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .image-thumbnail {
            max-width: 150px;
            max-height: 150px;
        }
        .active-image {
            border: 3px solid #0d6efd;
        }
        .cta-preview {
            max-height: 300px;
            object-fit: cover;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-4">
        <h2>Manage Call-to-Action Images</h2>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?= $_SESSION['message'] ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <h5 class="card-title">Upload New CTA Image</h5>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Select Image (Recommended: 1920x1080px for full-width banners)</label>
                        <input type="file" name="cta_image" class="form-control" accept="image/*" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Upload Image</button>
                </form>
            </div>
        </div>
        
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Current Active CTA Image</h5>
                <?php if ($activeImage): ?>
                    <div class="text-center mb-4">
                        <img src="../<?= htmlspecialchars($activeImage['image_path']) ?>" 
                             class="img-fluid rounded cta-preview">
                        <p class="mt-2">Currently active CTA image</p>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning">No active CTA image selected</div>
                <?php endif; ?>
                
                <h5 class="card-title mt-4">All CTA Images</h5>
                <div class="row">
                    <?php while ($image = $ctaImages->fetch_assoc()): ?>
                        <div class="col-md-4 mb-3">
                            <div class="card <?= $image['is_active'] ? 'border-primary' : '' ?>">
                                <img src="../<?= htmlspecialchars($image['image_path']) ?>" 
                                     class="card-img-top image-thumbnail">
                                <div class="card-body">
                                    <p class="card-text">
                                        Uploaded: <?= date('M j, Y g:i a', strtotime($image['uploaded_at'])) ?>
                                    </p>
                                    <div class="d-flex justify-content-between">
                                        <?php if (!$image['is_active']): ?>
                                            <form method="POST" style="display:inline;">
                                                <button type="submit" name="activate_image" 
                                                        value="<?= $image['id'] ?>" 
                                                        class="btn btn-sm btn-success">
                                                    Set as Active
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <span class="badge bg-primary">Active</span>
                                        <?php endif; ?>
                                        <form method="POST" style="display:inline;" 
                                              onsubmit="return confirm('Are you sure you want to delete this CTA image?');">
                                            <button type="submit" name="delete_image" 
                                                    value="<?= $image['id'] ?>" 
                                                    class="btn btn-sm btn-danger">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
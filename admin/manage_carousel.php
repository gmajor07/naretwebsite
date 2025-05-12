<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';


// Handle form actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_carousel'])) {
        // Add new carousel item
        $altText = trim($_POST['alt_text']);
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $displayOrder = (int)$_POST['display_order'];
        
        // Handle image upload
        $imagePath = handleImageUpload('image', '../assets/img/carousel/');
        
        
        $query = "INSERT INTO carousel_images 
                 (image_path, alt_text, is_active, display_order) 
                 VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssii", $imagePath, $altText, $isActive, $displayOrder);
        $stmt->execute();
        
        $_SESSION['message'] = "Carousel item added successfully!";
        header("Location: manage_carousel.php");
        exit;
        
    } elseif (isset($_POST['update_carousel'])) {
        
        function handleImageUpload($inputName, $targetDir) {
            // Check if file was uploaded
            if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
                throw new Exception("File upload failed");
            }
        
            // Validate image type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($fileInfo, $_FILES[$inputName]['tmp_name']);
            
            if (!in_array($mimeType, $allowedTypes)) {
                throw new Exception("Only JPG, PNG, and WEBP images are allowed");
            }
        
            // Create directory if it doesn't exist
            if (!file_exists($targetDir)) {
                mkdir($targetDir, 0755, true);
            }
        
            // Generate unique filename
            $extension = pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION);
            $filename = 'carousel_' . uniqid() . '.' . $extension;
            $targetPath = $targetDir . $filename;
        
            // Move uploaded file
            if (!move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetPath)) {
                throw new Exception("Failed to move uploaded file");
            }
        
            // Return path without leading slash or ../
            $webPath = str_replace(['../', '//'], '', $targetPath);
            return $webPath; // e.g. "assets/img/carousel/carousel_642ba3c7.jpg"
        }
        
        // Update existing carousel item
        $id = (int)$_POST['id'];
        $altText = trim($_POST['alt_text']);
        $isActive = isset($_POST['is_active']) ? 1 : 0;
        $displayOrder = (int)$_POST['display_order'];
        
        // Update with/without new image
        if ($_FILES['image']['size'] > 0) {
            $imagePath = handleImageUpload('image', '../assets/img/carousel/');
            
            
            $query = "UPDATE carousel_images SET 
                     image_path=?, alt_text=?, is_active=?, display_order=? 
                     WHERE id=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssiii", $imagePath, $altText, $isActive, $displayOrder, $id);
        } else {
            $query = "UPDATE carousel_images SET 
                     alt_text=?, is_active=?, display_order=? 
                     WHERE id=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("siii", $altText, $isActive, $displayOrder, $id);
        }
        
        $stmt->execute();
        $_SESSION['message'] = "Carousel item updated successfully!";
        header("Location: manage_carousel.php");
        exit;
    }
} elseif (isset($_GET['delete'])) {
    // Delete carousel item
    $id = (int)$_GET['delete'];
    $query = "DELETE FROM carousel_images WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    $_SESSION['message'] = "Carousel item deleted successfully!";
    header("Location: manage_carousel.php");
    exit;
} elseif (isset($_GET['toggle'])) {
    // Toggle active status
    $id = (int)$_GET['toggle'];
    $query = "UPDATE carousel_images SET is_active = NOT is_active WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    header("Location: manage_carousel.php");
    exit;
}

// Get all carousel items
$carouselItems = $conn->query("SELECT * FROM carousel_images ORDER BY display_order, created_at DESC")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Carousel | NARET Admin</title>
    <link href="../assets/css/admin.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Manage Homepage Carousel</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCarouselModal">
                        <i class="fas fa-plus"></i> Add New Slide
                    </button>
                </div>
                
                <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $_SESSION['message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['message']); endif; ?>
                
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Preview</th>
                                        <th>Alt Text</th>
                                        <th>Status</th>
                                        <th>Order</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($carouselItems as $item): ?>
                                    <tr>
                                        <td><?= $item['id']; ?></td>
                                        <td>
                                            <img src="../<?= htmlspecialchars($item['image_path']); ?>" 
                                                 alt="Preview" 
                                                 style="width: 120px; height: 80px; object-fit: cover;"
                                                 class="img-thumbnail">
                                        </td>
                                        <td><?= htmlspecialchars($item['alt_text']); ?></td>
                                        <td>
                                            <a href="manage_carousel.php?toggle=<?= $item['id']; ?>" 
                                               class="btn btn-sm btn-<?= $item['is_active'] ? 'success' : 'secondary'; ?>">
                                                <?= $item['is_active'] ? 'Active' : 'Inactive'; ?>
                                            </a>
                                        </td>
                                        <td><?= $item['display_order']; ?></td>
                                        <td><?= date('M d, Y', strtotime($item['created_at'])); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning edit-carousel" 
                                                    data-id="<?= $item['id']; ?>"
                                                    data-alt_text="<?= htmlspecialchars($item['alt_text']); ?>"
                                                    data-display_order="<?= $item['display_order']; ?>"
                                                    data-is_active="<?= $item['is_active']; ?>">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <a href="manage_carousel.php?delete=<?= $item['id']; ?>" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Delete this carousel slide?')">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <!-- Add Carousel Modal -->
    <div class="modal fade" id="addCarouselModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Add Carousel Slide</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                            <small class="text-muted">Recommended size: 1920x1080px (16:9 ratio)</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alt Text</label>
                            <input type="text" name="alt_text" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Display Order</label>
                                <input type="number" name="display_order" class="form-control" value="0">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Active Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" checked>
                                    <label class="form-check-label">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_carousel" class="btn btn-primary">Add Slide</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Carousel Modal -->
    <div class="modal fade" id="editCarouselModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="editCarouselId">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">Edit Carousel Slide</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">New Image (Optional)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">Leave empty to keep current image</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alt Text</label>
                            <input type="text" name="alt_text" id="editCarouselAltText" class="form-control" required>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Display Order</label>
                                <input type="number" name="display_order" id="editCarouselDisplayOrder" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Active Status</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="editCarouselActive">
                                    <label class="form-check-label">Active</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_carousel" class="btn btn-warning">Update Slide</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    $(document).ready(function() {
        // Handle edit button clicks
        $('.edit-carousel').click(function() {
            const id = $(this).data('id');
            const altText = $(this).data('alt_text');
            const displayOrder = $(this).data('display_order');
            const isActive = $(this).data('is_active');
            
            $('#editCarouselId').val(id);
            $('#editCarouselAltText').val(altText);
            $('#editCarouselDisplayOrder').val(displayOrder);
            $('#editCarouselActive').prop('checked', isActive == 1);
            
            $('#editCarouselModal').modal('show');
        });
        
        // Auto-close alerts after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    });
    </script>
</body>
</html>
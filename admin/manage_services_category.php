<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['add_service'])) {
            // Add new service
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            $imagePath = handleImageUpload('image', '../assets/img/services/');
            
            $stmt = $conn->prepare("INSERT INTO service_features (title, description, image_path, is_featured) VALUES (?, ?, ?, 1)");
            $stmt->bind_param("sss", $title, $description, $imagePath);
            $stmt->execute();
            
            $_SESSION['message'] = "Service added successfully!";
        }
        elseif (isset($_POST['update_service'])) {
            // Update existing service
            $id = (int)$_POST['id'];
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            
            if ($_FILES['image']['size'] > 0) {
                $imagePath = handleImageUpload('image', '../assets/img/services/');
                $stmt = $conn->prepare("UPDATE service_features SET title=?, description=?, image_path=? WHERE id=?");
                $stmt->bind_param("sssi", $title, $description, $imagePath, $id);
            } else {
                $stmt = $conn->prepare("UPDATE service_features SET title=?, description=? WHERE id=?");
                $stmt->bind_param("ssi", $title, $description, $id);
            }
            $stmt->execute();
            
            $_SESSION['message'] = "Service updated successfully!";
        }
        header("Location: manage_services_category.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
}

// Handle delete action
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM service_features WHERE id=$id");
    $_SESSION['message'] = "Service deleted successfully!";
    header("Location: manage_services_category.php");
    exit;
}

// Get all services
$services = $conn->query("SELECT * FROM service_features ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Services | NARET Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .service-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        .edit-service {
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-4">
        <h2>Manage Services</h2>
        
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
                <h5 class="card-title">Add New Service</h5>
                <form method="POST" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Service Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Service Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" required></textarea>
                    </div>
                    <button type="submit" name="add_service" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Service
                    </button>
                </form>
            </div>
        </div>
        
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Current Services</h5>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($services as $service): ?>
                            <tr>
                                <td><?= $service['id'] ?></td>
                                <td>
                                    <img src="../<?= htmlspecialchars($service['image_path']) ?>" 
                                         class="service-img" 
                                         alt="<?= htmlspecialchars($service['title']) ?>">
                                </td>
                                <td><?= htmlspecialchars($service['title']) ?></td>
                                <td><?= htmlspecialchars($service['description']) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-warning edit-service"
                                            data-id="<?= $service['id'] ?>"
                                            data-title="<?= htmlspecialchars($service['title']) ?>"
                                            data-description="<?= htmlspecialchars($service['description']) ?>">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <a href="manage_services_category.php?delete=<?= $service['id'] ?>" 
                                       class="btn btn-sm btn-danger"
                                       onclick="return confirm('Are you sure you want to delete this service?')">
                                        <i class="fas fa-trash"></i> Delete
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Service Modal -->
    <div class="modal fade" id="editServiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="editServiceId">
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title">Edit Service</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Service Title</label>
                            <input type="text" name="title" id="editServiceTitle" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Service Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">Leave empty to keep current image</small>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="editServiceDescription" class="form-control" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_service" class="btn btn-warning">Update Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    $(document).ready(function() {
        // Handle edit button clicks
        $('.edit-service').click(function() {
            $('#editServiceId').val($(this).data('id'));
            $('#editServiceTitle').val($(this).data('title'));
            $('#editServiceDescription').val($(this).data('description'));
            $('#editServiceModal').modal('show');
        });
        
        // Auto-close alerts after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    });
    </script>
</body>
</html>
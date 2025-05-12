<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle form actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_service'])) {
        // Add new service
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $category = trim($_POST['category']);
        
        // Handle image upload
        $imagePath = handleImageUpload('image', '../assets/img/fumigation/');
        
        $query = "INSERT INTO fumigation (title, description, image_path, category) 
        VALUES (?, ?, ?, ?)"; // Should only have 4 placeholders
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssss", $title, $description, $imagePath, $category); // Fix bind types too

        $stmt->execute();
        
        $_SESSION['message'] = "Fumigation Service added successfully!";
        header("Location: manage_fumigation.php");
        exit;
        
    } elseif (isset($_POST['update_service'])) {

      
        
        // Update existing service
        $id = (int)$_POST['id'];
        $title = trim($_POST['title']);
        $description = trim($_POST['description']);
        $category = trim($_POST['category']);
        
        // Update with/without new image
        if ($_FILES['image']['size'] > 0) {
            $imagePath = handleImageUpload('image', '../assets/img/services/');
            $query = "UPDATE fumigation SET title=?, description=?, image_path=?, category=? WHERE id=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssssi", $title, $description, $imagePath, $category, $id);
        } else {
            $query = "UPDATE fumigation SET title=?, description=?, category=? WHERE id=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssi", $title, $description, $category, $id);

        }
        
        $stmt->execute();
        $_SESSION['message'] = "Fumigation service updated successfully!";
        header("Location: manage_fumigation.php");
        exit;
        
    } 
}

if (isset($_GET['delete'])) {
        // Delete service
        $id = (int)$_GET['delete'];
        $query = "DELETE FROM fumigation WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        
        $_SESSION['message'] = "Fumigation Service deleted successfully!";
        header("Location: manage_fumigation.php");
        exit;
    }
// Get all services
$services = $conn->query("SELECT * FROM fumigation ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services | NARET Admin</title>
    <!-- Your Admin CSS -->
    <link href="../assets/css/admin.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Manage Fumigation</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addServiceModal">
                        <i class="fas fa-plus"></i> Add New Service
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
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($services as $service): ?>
                                    <tr>
                                        <td><?= $service['id']; ?></td>
                                        <td>
                                            <img src="../<?= htmlspecialchars($service['image_path']); ?>" 
                                                 alt="<?= htmlspecialchars($service['title']); ?>" 
                                                 style="width: 80px; height: 60px; object-fit: cover;">
                                        </td>
                                        <td><?= htmlspecialchars($service['title']); ?></td>
                                        <td><?= htmlspecialchars($service['category']); ?></td>
                                    
                                        <td>
                                            <button class="btn btn-sm btn-warning edit-service" 
                                                    data-id="<?= $service['id']; ?>"
                                                    data-title="<?= htmlspecialchars($service['title']); ?>"
                                                    data-description="<?= htmlspecialchars($service['description']); ?>"
                                                    data-category="<?= htmlspecialchars($service['category']); ?>"
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <a href="manage_fumigation.php?delete=<?= $service['id']; ?>" 
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
            </main>
        </div>
    </div>

    <!-- Add Service Modal -->
    <div class="modal fade" id="addServiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Add New Service</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Service Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <input type="text" name="category" class="form-control" required>
                            </div>
                          
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Service Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                            <small class="text-muted">Recommended size: 800x600px</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_service" class="btn btn-primary">Save Service</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Service Modal -->
    <div class="modal fade" id="editServiceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="editServiceId">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">Edit Service</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Service Title</label>
                            <input type="text" name="title" id="editServiceTitle" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="editServiceDescription" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Category</label>
                                <input type="text" name="category" id="editServiceCategory" class="form-control" required>
                            </div>
                          
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Service Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">Leave empty to keep current image</small>
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

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
    // Handle edit button clicks
    $(document).ready(function() {
        $('.edit-service').click(function() {
            const id = $(this).data('id');
            const title = $(this).data('title');
            const description = $(this).data('description');
            const category = $(this).data('category');
            
            $('#editServiceId').val(id);
            $('#editServiceTitle').val(title);
            $('#editServiceDescription').val(description);
            $('#editServiceCategory').val(category);
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
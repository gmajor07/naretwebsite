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
    if (isset($_POST['add_work'])) {
        try {
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            
            // Handle image upload - returns path like "assets/img/works/filename.jpg"
            $imagePath = handleImageUpload('image', '../assets/img/works/');
            
            $query = "INSERT INTO recent_works (title, description, image_path) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sss", $title, $description, $imagePath);
            $stmt->execute();
            
            $_SESSION['message'] = "Work added successfully!";
        } catch (Exception $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
        header("Location: manage_works.php");
        exit;
        
    } elseif (isset($_POST['update_work'])) {

       
        try {
            $id = (int)$_POST['id'];
            $title = trim($_POST['title']);
            $description = trim($_POST['description']);
            
            // Update with/without new image
            if ($_FILES['image']['size'] > 0) {
                $imagePath = handleImageUpload('image', '../assets/img/works/');
                $query = "UPDATE recent_works SET title=?, description=?, image_path=? WHERE id=?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("sssi", $title, $description, $imagePath, $id);
            } else {
                $query = "UPDATE recent_works SET title=?, description=? WHERE id=?";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ssi", $title, $description, $id);
            }
            
            $stmt->execute();
            $_SESSION['message'] = "Work updated successfully!";
        } catch (Exception $e) {
            $_SESSION['error'] = "Error: " . $e->getMessage();
        }
        header("Location: manage_works.php");
        exit;
    }
} elseif (isset($_GET['delete'])) {
    try {
        $id = (int)$_GET['delete'];
        $query = "DELETE FROM recent_works WHERE id=?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $_SESSION['message'] = "Work deleted successfully!";
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
    header("Location: manage_works.php");
    exit;
}

// Get all works
$works = $conn->query("SELECT * FROM recent_works ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Works | NARET Admin</title>
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
                    <h1 class="h2">Manage Recent Works</h1>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWorkModal">
                        <i class="fas fa-plus"></i> Add New Work
                    </button>
                </div>
                
                <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $_SESSION['message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['message']); endif; ?>
                
                <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= $_SESSION['error']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error']); endif; ?>
                
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Image</th>
                                        <th>Title</th>
                                        <th>Description</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($works as $work): ?>
                                    <tr>
                                        <td><?= $work['id']; ?></td>
                                        <td>
    <img src="../<?= $work['image_path']; ?>" alt="Work image" width="100">
</td>

                                        <td><?= htmlspecialchars($work['title']); ?></td>
                                        <td><?= nl2br(htmlspecialchars(substr($work['description'], 0, 100) . '...')); ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-warning edit-work" 
                                                    data-id="<?= $work['id']; ?>"
                                                    data-title="<?= htmlspecialchars($work['title']); ?>"
                                                    data-description="<?= htmlspecialchars($work['description']); ?>">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <a href="manage_works.php?delete=<?= $work['id']; ?>" 
                                               class="btn btn-sm btn-danger" 
                                               onclick="return confirm('Are you sure you want to delete this work?')">
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

    <!-- Add Work Modal -->
    <div class="modal fade" id="addWorkModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Add New Work</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*" required>
                            <small class="text-muted">Recommended size: 800x600px</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_work" class="btn btn-primary">Add Work</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Work Modal -->
    <div class="modal fade" id="editWorkModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="editWorkId">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">Edit Work</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" id="editWorkTitle" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="editWorkDescription" class="form-control" rows="4" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">New Image (Optional)</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <small class="text-muted">Leave empty to keep current image</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_work" class="btn btn-warning">Update Work</button>
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
        $('.edit-work').click(function() {
            $('#editWorkId').val($(this).data('id'));
            $('#editWorkTitle').val($(this).data('title'));
            $('#editWorkDescription').val($(this).data('description'));
            $('#editWorkModal').modal('show');
        });
        
        // Auto-close alerts after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
    });
    </script>
</body>
</html>
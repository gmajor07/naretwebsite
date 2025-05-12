<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle image upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_client'])) {
    try {
        // Validate file
        if (!isset($_FILES['client_image']) || $_FILES['client_image']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Please select a valid image file");
        }

        // Check image type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $fileType = $_FILES['client_image']['type'];
        if (!in_array($fileType, $allowedTypes)) {
            throw new Exception("Only JPG, PNG, and WEBP images are allowed");
        }

        // Create upload directory if needed
        $uploadDir = '../assets/img/clients/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique filename
        $extension = pathinfo($_FILES['client_image']['name'], PATHINFO_EXTENSION);
        $filename = 'client_' . uniqid() . '.' . $extension;
        $targetPath = $uploadDir . $filename;

        // Move uploaded file
        if (!move_uploaded_file($_FILES['client_image']['tmp_name'], $targetPath)) {
            throw new Exception("Failed to save image");
        }

        // Get next display order
        $nextOrder = $conn->query("SELECT COALESCE(MAX(display_order), 0) + 1 FROM clients")->fetch_row()[0];

        // Insert into database
        $stmt = $conn->prepare("INSERT INTO clients (image_path, display_order, is_active) VALUES (?, ?, 1)");
        $webPath = 'assets/img/clients/' . $filename;
        $stmt->bind_param("si", $webPath, $nextOrder);
        $stmt->execute();

        $_SESSION['message'] = "Client image added successfully!";
        header("Location: manage_client_images.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
}

// Handle image deletion
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $conn->query("DELETE FROM clients WHERE id = $id");
    $_SESSION['message'] = "Client image deleted successfully!";
    header("Location: manage_client_images.php");
    exit;
}

// Handle display order updates
if (isset($_POST['update_order'])) {
    foreach ($_POST['order'] as $id => $order) {
        $id = (int)$id;
        $order = (int)$order;
        $conn->query("UPDATE clients SET display_order = $order WHERE id = $id");
    }
    $_SESSION['message'] = "Display order updated!";
    header("Location: manage_client_images.php");
    exit;
}

// Get all client images
$clients = $conn->query("SELECT * FROM clients ORDER BY display_order")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Client Images</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .client-img {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border: 2px solid #ddd;
            border-radius: 4px;
        }
        .sortable-handle {
            cursor: move;
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-4">
        <h2>Manage Client Images</h2>
        
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
                <h5 class="card-title">Add New Client Image</h5>
                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Client Image (Square ratio recommended)</label>
                        <input type="file" name="client_image" class="form-control" accept="image/*" required>
                    </div>
                    <button type="submit" name="add_client" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Image
                    </button>
                </form>
            </div>
        </div>
        
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Current Client Images</h5>
                <form method="POST" id="order-form">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="50">Order</th>
                                    <th>Preview</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($clients as $client): ?>
                                <tr>
                                    <td>
                                        <input type="number" name="order[<?= $client['id'] ?>]" 
                                               value="<?= $client['display_order'] ?>" 
                                               class="form-control form-control-sm" min="1">
                                    </td>
                                    <td>
                                        <img src="../<?= htmlspecialchars($client['image_path']) ?>" 
                                             class="client-img" 
                                             alt="Client Image">
                                    </td>
                                    <td>
                                        

                                        <a href="manage_client_images.php?delete=<?= $client['id'] ?>" 
   class="btn btn-sm btn-danger delete-btn">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if (!empty($clients)): ?>
                    <button type="submit" name="update_order" class="btn btn-primary mt-3">
                        <i class="fas fa-save"></i> Save Order
                    </button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="deleteConfirmLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="deleteConfirmLabel">Confirm Deletion</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete this client image?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <a href="#" class="btn btn-danger" id="confirmDeleteBtn">Delete</a>
      </div>
    </div>
  </div>
</div>


<script>
    document.querySelectorAll('.delete-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault(); // Prevent default link behavior

            const deleteUrl = this.getAttribute('href');
            const confirmBtn = document.getElementById('confirmDeleteBtn');

            // Set delete link in modal
            confirmBtn.setAttribute('href', deleteUrl);

            // Show the Bootstrap modal
            const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            deleteModal.show();
        });
    });
</script>


</body>
</html>
<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Redirect if not logged in as admin
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle image upload (Add or Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_image'])) {
        $imagePath = handleImageUpload('image', '../assets/img/deci/');
        $query = "INSERT INTO deci (image_path) VALUES (?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $imagePath);
        $stmt->execute();

        $_SESSION['message'] = "Image added successfully!";
        header("Location: manage_deci.php");
        exit;
    } elseif (isset($_POST['update_image'])) {
        $id = (int)$_POST['id'];
        if ($_FILES['image']['size'] > 0) {
            $imagePath = handleImageUpload('image', '../assets/img/deci/');
            $query = "UPDATE deci SET image_path=? WHERE id=?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $imagePath, $id);
            $stmt->execute();
        }
        $_SESSION['message'] = "Image updated successfully!";
        header("Location: manage_deci.php");
        exit;
    }
}

// Handle image delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $query = "DELETE FROM deci WHERE id=?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $_SESSION['message'] = "Image deleted successfully!";
    header("Location: manage_deci.php");
    exit;
}

// Fetch images
$images = $conn->query("SELECT * FROM deci ORDER BY id DESC")->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Images</title>
    <link href="../assets/css/admin.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h2>Manage Images</h2>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['message']; ?>
                <?php unset($_SESSION['message']); ?>
            </div>
        <?php endif; ?>

        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addImageModal">Add New Image</button>

        <div class="row">
            <?php foreach ($images as $img): ?>
                <div class="col-md-3 mb-4">
                    <div class="card">
                        <img src="../<?= htmlspecialchars($img['image_path']); ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                        <div class="card-body text-center">
                            <button class="btn btn-warning btn-sm edit-image" data-id="<?= $img['id']; ?>" data-bs-toggle="modal" data-bs-target="#editImageModal">Edit</button>
                            <a href="manage_deci.php?delete=<?= $img['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this image?')">Delete</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Add Image Modal -->
    <div class="modal fade" id="addImageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">Add New Image</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="add_image" class="btn btn-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Image Modal -->
    <div class="modal fade" id="editImageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="editImageId">
                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title">Update Image</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">New Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="update_image" class="btn btn-warning">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.querySelectorAll('.edit-image').forEach(button => {
            button.addEventListener('click', () => {
                const id = button.getAttribute('data-id');
                document.getElementById('editImageId').value = id;
            });
        });
    </script>
</body>
</html>

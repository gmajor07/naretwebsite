<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}


// Temporary direct connection
$pdo = new PDO('mysql:host=localhost;dbname=naret_company', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  if (isset($_POST['add_point'])) {
    // Add new point
    $point_text = trim($_POST['point_text']);
    $has_icon = isset($_POST['has_icon']) ? 1 : 0;
    
    try {
        // Get next display order
        $maxOrderStmt = $pdo->query("SELECT IFNULL(MAX(display_order), 0) + 1 AS next_order FROM about_points");
        $nextOrder = $maxOrderStmt->fetchColumn();
        
        // Insert new record
        $stmt = $pdo->prepare("INSERT INTO about_points (point_text, has_icon, display_order, created_at) 
                              VALUES (?, ?, ?, NOW())");
        $stmt->execute([$point_text, $has_icon, $nextOrder]);
        
        $_SESSION['success'] = "Point added successfully!";
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error adding point: " . $e->getMessage();
    }
    
    header("Location: manage_about_points.php");
    exit();
}
    elseif (isset($_POST['update_points'])) {
        // Update existing points
        foreach ($_POST['points'] as $id => $data) {
            $point_text = trim($data['text']);
            $has_icon = isset($data['has_icon']) ? 1 : 0;
            $display_order = (int)$data['order'];
            
            $stmt = $pdo->prepare("UPDATE about_points 
                                 SET point_text = ?, has_icon = ?, display_order = ?
                                 WHERE id = ?");
            $stmt->execute([$point_text, $has_icon, $display_order, $id]);
        }
        
        $_SESSION['success'] = "Points updated successfully!";
    }
    
    header("Location: manage_about_points.php");
    exit();
}

// Handle delete action
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    
    $stmt = $pdo->prepare("DELETE FROM about_points WHERE id = ?");
    $stmt->execute([$id]);
    
    // Reorder remaining points
    $stmt = $pdo->query("SET @count = 0");
    $stmt = $pdo->query("UPDATE about_points SET display_order = @count:= @count + 1 ORDER BY display_order");
    
    $_SESSION['success'] = "Point deleted successfully!";
    header("Location: manage_about_points.php");
    exit();
}

// Fetch all points ordered by display_order
$points = $pdo->query("SELECT * FROM about_points ORDER BY display_order")->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit About Points</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
        <?php include 'navbar.php'; ?>

    <div class="container py-4">
        <h1 class="mb-4">Edit About Points</h1>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <div class="card mb-4">
            <div class="card-header">
                <h5>Add New Point</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="point_text" class="form-label">Point Text</label>
                        <textarea class="form-control" id="point_text" name="point_text" rows="3" required></textarea>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="has_icon" name="has_icon" checked>
                        <label class="form-check-label" for="has_icon">Show check icon</label>
                    </div>
                    <button type="submit" name="add_point" class="btn btn-primary">Add Point</button>
                </form>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5>Manage Existing Points</h5>
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th width="50">Order</th>
                                    <th>Text</th>
                                    <th width="100">Has Icon</th>
                                    <th width="100">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($points as $point): ?>
                                <tr>
                                    <td>
                                        <input type="number" name="points[<?= $point['id'] ?>][order]" 
                                               value="<?= $point['display_order'] ?>" 
                                               class="form-control form-control-sm" min="1">
                                    </td>
                                    <td>
                                        <textarea name="points[<?= $point['id'] ?>][text]" 
                                                  class="form-control form-control-sm" rows="2"><?= htmlspecialchars($point['point_text']) ?></textarea>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="points[<?= $point['id'] ?>][has_icon]" 
                                               class="form-check-input" <?= $point['has_icon'] ? 'checked' : '' ?>>
                                    </td>
                                    <td>
                                        <a href="manage_about_points.php?delete=<?= $point['id'] ?>" 
                                           class="btn btn-sm btn-danger" 
                                           onclick="return confirm('Are you sure you want to delete this point?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if (!empty($points)): ?>
                    <button type="submit" name="update_points" class="btn btn-primary">Save Changes</button>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
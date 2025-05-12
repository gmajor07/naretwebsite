<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get current about content
$aboutContent = getSectionContent('home', 'about');
$aboutPoints = getAboutPoints($aboutContent['id'] ?? 0);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Update main about content
        $title = trim($_POST['title']);
        $content = trim($_POST['content']);
        
        // Check if about section exists
        if (empty($aboutContent)) {
            $query = "INSERT INTO site_content (page, section, title, content, created_at, updated_at) 
                     VALUES ('home', 'about', ?, ?, NOW(), NOW())";
        } else {
            $query = "UPDATE site_content SET title = ?, content = ?, updated_at = NOW() 
                     WHERE page = 'home' AND section = 'about'";
        }
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $title, $content);
        $stmt->execute();
        
        $aboutId = empty($aboutContent) ? $conn->insert_id : $aboutContent['id'];
        
        // Handle about points
        if (isset($_POST['points'])) {
            $deleteQuery = "DELETE FROM about_points WHERE id = ?";
            $deleteStmt = $conn->prepare($deleteQuery);
            $deleteStmt->bind_param("i", $aboutId);
            $deleteStmt->execute();
            
            $insertQuery = "INSERT INTO about_points (id, point_text, display_order, has_icon, created_at) 
                           VALUES (?, ?, ?, ?, NOW())";
            $insertStmt = $conn->prepare($insertQuery);
            
            foreach ($_POST['points'] as $order => $point) {
                if (!empty($point['text'])) {
                    $hasIcon = isset($point['has_icon']) ? 1 : 0;
                    $insertStmt->bind_param("isii", $aboutId, $point['text'], $order, $hasIcon);
                    $insertStmt->execute();
                }
            }
        }
        
        $_SESSION['message'] = "About section updated successfully!";
        header("Location: manage_about.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage About Section | NARET Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container-fluid">
    <div class="row">
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Manage About Section</h1>
            </div>

            <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $_SESSION['message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['message']); endif; ?>

            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= $_SESSION['error_message']; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php unset($_SESSION['error_message']); endif; ?>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">About Title</label>
                                    <input type="text" name="title" class="form-control" 
                                           value="<?= htmlspecialchars($aboutContent['title'] ?? ''); ?>" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">About Content</label>
                                    <textarea name="content" class="form-control" rows="6" required><?= 
                                        htmlspecialchars($aboutContent['content'] ?? ''); ?></textarea>
                                </div>
                            </div>

                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                            <a href="manage_about_points.php" class="btn btn-outline-secondary ms-2">
                        <i class="fas fa-edit"></i> Manage About Points
                    </a>
                        </div>
                      
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    $('#add-point').click(function() {
        const index = Date.now();
        const pointHtml = `
            <div class="point-item mb-3 border p-3">
                <div class="mb-2">
                    <label class="form-label">Point Text</label>
                    <textarea name="points[${index}][text]" class="form-control" rows="2" required></textarea>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" 
                           name="points[${index}][has_icon]" id="point_${index}_icon" checked>
                    <label class="form-check-label" for="point_${index}_icon">
                        Show icon with this point
                    </label>
                </div>
                <button type="button" class="btn btn-sm btn-danger mt-2 remove-point">
                    <i class="fas fa-trash"></i> Remove
                </button>
            </div>
        `;
        $('#points-container').append(pointHtml);
    });

    $(document).on('click', '.remove-point', function() {
        $(this).closest('.point-item').remove();
    });

    setTimeout(function() {
        $('.alert').alert('close');
    }, 5000);
});
</script>
</body>
</html>

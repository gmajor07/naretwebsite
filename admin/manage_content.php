<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST['content'] as $section => $content) {
        $title = $_POST['titles'][$section] ?? null;
        
        $query = "INSERT INTO site_content (page, section, title, content) 
                 VALUES ('home', ?, ?, ?)
                 ON DUPLICATE KEY UPDATE title = VALUES(title), content = VALUES(content)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $section, $title, $content);
        $stmt->execute();
    }
    
    $success = "Content updated successfully!";
}

// Get current content
$content = getPageContent('home');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Content</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-4">
        <h2>Manage Vision & Mission Content</h2>
        
        <?php if (isset($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        
        <form method="POST">
            
            <div class="card mb-4">
                <div class="card-header">Vision & Mission</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Vision Text</label>
                        <textarea class="form-control" name="content[vision_text]" rows="3"><?php 
                            echo htmlspecialchars($content['vision_text'] ?? ''); 
                        ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mission Text</label>
                        <textarea class="form-control" name="content[mission_text]" rows="3"><?php 
                            echo htmlspecialchars($content['mission_text'] ?? ''); 
                        ?></textarea>
                    </div>
                </div>
            </div>
            
            
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
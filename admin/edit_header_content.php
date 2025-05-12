<?php
session_start();
require_once '../includes/db.php'; // Your MySQLi connection file
require_once '../includes/functions.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $heading = trim($_POST['heading']);
    $paragraph = trim($_POST['paragraph']);
    $section = 'main';
    
    try {
        // Check if record exists
        $checkStmt = $conn->prepare("SELECT id FROM header_content WHERE section_name = ?");
        $checkStmt->bind_param("s", $section);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        
        if ($checkResult->num_rows > 0) {
            // Update existing
            $stmt = $conn->prepare("UPDATE header_content 
                                  SET heading_text = ?, paragraph_text = ?
                                  WHERE section_name = ?");
            $stmt->bind_param("sss", $heading, $paragraph, $section);
        } else {
            // Insert new
            $stmt = $conn->prepare("INSERT INTO header_content 
                                  (section_name, heading_text, paragraph_text)
                                  VALUES (?, ?, ?)");
            $stmt->bind_param("sss", $section, $heading, $paragraph);
        }
        
        $stmt->execute();
        $_SESSION['success'] = "Header content updated successfully!";
    } catch (Exception $e) {
        $_SESSION['error'] = "Error updating header: " . $e->getMessage();
    }
    
    header("Location: edit_header_content.php");
    exit();
}

$headerContent = getHeaderContent('main');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Header Content</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
        <?php include 'navbar.php'; ?>

    <div class="container py-4">
        <h1>Edit Header Content</h1>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Heading Text</label>
                <textarea class="form-control" name="heading" rows="3" required><?= 
                    htmlspecialchars($headerContent['heading_text'] ?? '') 
                ?></textarea>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Paragraph Text</label>
                <textarea class="form-control" name="paragraph" rows="3" required><?= 
                    htmlspecialchars($headerContent['paragraph_text'] ?? '') 
                ?></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
</body>
</html>
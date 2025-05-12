<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get current CTA content
$ctaContent = getSectionContent('home', 'call_to_action');
$currentDetails = !empty($ctaContent['content']) ? json_decode($ctaContent['content'], true) : [
    'phone' => '(+255) 753995084<br>(+255) 754689775',
    'email' => 'naret@naret.co.tz',
    'address' => 'Dar es Salaam, Tanzania P.o.Box 6230<br>Kurasini shimo la udongo road opposite of Gates5 stand'
];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $title = trim($_POST['title']);
        $phone = trim($_POST['phone']);
        $email = trim($_POST['email']);
        $address = trim($_POST['address']);
        
        // Prepare JSON content
        $content = json_encode([
            'phone' => $phone,
            'email' => $email,
            'address' => $address
        ]);
        
        // Check if CTA section exists
        if (empty($ctaContent)) {
            // Insert new content
            $query = "INSERT INTO site_content (page, section, title, content, created_at, updated_at) 
                     VALUES ('home', 'call_to_action', ?, ?, NOW(), NOW())";
        } else {
            // Update existing content
            $query = "UPDATE site_content SET title = ?, content = ?, updated_at = NOW() 
                     WHERE page = 'home' AND section = 'call_to_action'";
        }
        
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $title, $content);
        $stmt->execute();
        
        $_SESSION['message'] = "CTA content updated successfully!";
        header("Location: manage_cta_content.php");
        exit;
    } catch (Exception $e) {
        $_SESSION['error'] = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage CTA Content</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-4">
        <h2>Manage Call-to-Action Content</h2>
        
        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success"><?= $_SESSION['message'] ?></div>
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
        
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" class="form-control" 
                               value="<?= htmlspecialchars($ctaContent['title'] ?? 'Welcome! 20% Off All Our Cleaning Services') ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Phone Numbers</label>
                        <textarea name="phone" class="form-control" rows="2" required><?= 
                            htmlspecialchars($currentDetails['phone'] ?? '') ?></textarea>
                        <small class="text-muted">You can use &lt;br&gt; for line breaks</small>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" 
                               value="<?= htmlspecialchars($currentDetails['email'] ?? '') ?>" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Address</label>
                        <textarea name="address" class="form-control" rows="3" required><?= 
                            htmlspecialchars($currentDetails['address'] ?? '') ?></textarea>
                        <small class="text-muted">You can use &lt;br&gt; for line breaks</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <a href="manage_cta_image.php" class="btn btn-outline-secondary ms-2">
                        <i class="fas fa-image"></i> Manage Image
                    </a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
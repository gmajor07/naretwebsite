<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Get stats for dashboard
$stats = [
    'services' => $conn->query("SELECT COUNT(*) FROM services")->fetch_row()[0],
    'carousel' => $conn->query("SELECT COUNT(*) FROM carousel_images")->fetch_row()[0],
    'works' => $conn->query("SELECT COUNT(*) FROM recent_works")->fetch_row()[0],
    'clients' => $conn->query("SELECT COUNT(*) FROM clients")->fetch_row()[0]    
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

    <div class="container mt-4">
        <h2>Dashboard</h2>
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Services</h5>
                        <p class="card-text display-4"><?php echo $stats['services']; ?></p>
                        <a href="manage_services.php" class="text-white">Manage Services</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Carousel Images</h5>
                        <p class="card-text display-4"><?php echo $stats['carousel']; ?></p>
                        <a href="manage_carousel.php" class="text-white">Manage Carousel</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Recent Works</h5>
                        <p class="card-text display-4"><?php echo $stats['works']; ?></p>
                        <a href="manage_works.php" class="text-white">Manage Works</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Clients</h5>
                        <p class="card-text display-4"><?php echo $stats['clients']; ?></p>
                        <a href="manage_client_images.php" class="text-white">Manage Clients</a>
                    </div>
                </div>
            </div>
           

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
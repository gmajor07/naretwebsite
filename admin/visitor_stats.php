<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

$visitors = $conn->query("SELECT * FROM website_visitors ORDER BY visit_time DESC LIMIT 100");
$totalVisits = $conn->query("SELECT COUNT(*) AS total FROM website_visitors")->fetch_assoc()['total'];
$uniqueVisitors = $conn->query("SELECT COUNT(DISTINCT ip_address) AS unique_visitors FROM website_visitors")->fetch_assoc()['unique_visitors'];
?>




    <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Statistics | NARET Admin</title>
    <link href="../assets/css/admin.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-4">
        <h2>Visitor Statistics</h2>
        
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="card text-white bg-primary">
                    <div class="card-body">
                        <h5 class="card-title">Total Visits</h5>
                        <p class="display-4"><?= number_format($totalVisits) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
    <div class="card text-white bg-success">
        <div class="card-body">
            <h5 class="card-title">Unique Visitors</h5>
            <p class="display-4"><?= number_format($uniqueVisitors) ?></p>
        </div>
    </div>
    </div>
            <div class="col-md-4">
                <div class="card text-white bg-info">
                    <div class="card-body">
                        <h5 class="card-title">Today's Visitors</h5>
                        <p class="display-4"><?= number_format(getTodayVisitors()) ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title">Recent Visits</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>IP Address</th>
                                <th>Device</th>
                                <th>Page</th>
                                <th>Visit Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($visitor = $visitors->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($visitor['ip_address']) ?></td>
                                <td><?= htmlspecialchars(substr($visitor['user_agent'], 0, 50)) ?>...</td>
                                <td><?= htmlspecialchars($visitor['page_visited']) ?></td>
                                <td><?= date('M j, Y g:i a', strtotime($visitor['visit_time'])) ?></td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
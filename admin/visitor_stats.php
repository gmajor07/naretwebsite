<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

// Handle clear history request
if (isset($_POST['clear_history'])) {
    $conn->query("TRUNCATE TABLE website_visitors");
    header("Location: visitors.php");
    exit;
}

// Date filtering
$start_date = $_GET['start_date'] ?? date('Y-m-01');
$end_date = $_GET['end_date'] ?? date('Y-m-d');

// Get visitor data
$visitors = $conn->query("SELECT * FROM website_visitors 
                         WHERE DATE(visit_time) BETWEEN '$start_date' AND '$end_date'
                         ORDER BY visit_time DESC LIMIT 100");

// Get statistics
$totalVisits = $conn->query("SELECT COUNT(*) AS total FROM website_visitors 
                            WHERE DATE(visit_time) BETWEEN '$start_date' AND '$end_date'")->fetch_assoc()['total'];
$uniqueVisitors = $conn->query("SELECT COUNT(DISTINCT ip_address) AS unique_visitors FROM website_visitors 
                              WHERE DATE(visit_time) BETWEEN '$start_date' AND '$end_date'")->fetch_assoc()['unique_visitors'];
$todayVisitors = getTodayVisitors();

// Get data for charts
$dailyData = $conn->query("SELECT DATE(visit_time) AS date, COUNT(*) AS visits 
                          FROM website_visitors 
                          WHERE DATE(visit_time) BETWEEN '$start_date' AND '$end_date'
                          GROUP BY DATE(visit_time) 
                          ORDER BY date");

$hourlyData = $conn->query("SELECT HOUR(visit_time) AS hour, COUNT(*) AS visits 
                           FROM website_visitors 
                           WHERE DATE(visit_time) BETWEEN '$start_date' AND '$end_date'
                           GROUP BY HOUR(visit_time) 
                           ORDER BY hour");

$pageData = $conn->query("SELECT page_visited, COUNT(*) AS visits 
                         FROM website_visitors 
                         WHERE DATE(visit_time) BETWEEN '$start_date' AND '$end_date'
                         GROUP BY page_visited 
                         ORDER BY visits DESC LIMIT 10");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visitor Statistics | NARET Admin</title>
    <link href="../assets/css/admin.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="container mt-4">
        <h2>Visitor Statistics</h2>
        
        <!-- Date Filter Form -->
        <div class="card mb-4">
            <div class="card-body">
                <form method="get" class="row g-3">
                    <div class="col-md-4">
                        <label for="start_date" class="form-label">From Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="<?= $start_date ?>">
                    </div>
                    <div class="col-md-4">
                        <label for="end_date" class="form-label">To Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="<?= $end_date ?>">
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                        <a href="visitors.php" class="btn btn-outline-secondary ms-2">Reset</a>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Stats Cards -->
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
                        <p class="display-4"><?= number_format($todayVisitors) ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Charts Section -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Daily Visits</h5>
                        <canvas id="dailyChart" height="250"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Hourly Traffic</h5>
                        <canvas id="hourlyChart" height="250"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Top Pages</h5>
                        <canvas id="pagesChart" height="150"></canvas>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Visits Table -->
        <div class="card shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Recent Visits</h5>
                <form method="post" onsubmit="return confirm('Are you sure you want to clear all visitor history?');">
                    <button type="submit" name="clear_history" class="btn btn-danger btn-sm">
                        <i class="bi bi-trash"></i> Clear History
                    </button>
                </form>
            </div>
            <div class="card-body">
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

    <script>
        // Daily Visits Chart
        const dailyCtx = document.getElementById('dailyChart').getContext('2d');
        const dailyChart = new Chart(dailyCtx, {
            type: 'line',
            data: {
                labels: [<?php 
                    $dailyData->data_seek(0);
                    while($row = $dailyData->fetch_assoc()) {
                        echo "'" . date('M j', strtotime($row['date'])) . "',";
                    }
                ?>],
                datasets: [{
                    label: 'Visits',
                    data: [<?php 
                        $dailyData->data_seek(0);
                        while($row = $dailyData->fetch_assoc()) {
                            echo $row['visits'] . ",";
                        }
                    ?>],
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 2,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
        
        // Hourly Traffic Chart
        const hourlyCtx = document.getElementById('hourlyChart').getContext('2d');
        const hourlyChart = new Chart(hourlyCtx, {
            type: 'bar',
            data: {
                labels: [<?php 
                    $hourlyData->data_seek(0);
                    for ($i=0; $i<24; $i++) {
                        echo "'$i:00',";
                    }
                ?>],
                datasets: [{
                    label: 'Visits',
                    data: [<?php 
                        $hours = array_fill(0, 24, 0);
                        $hourlyData->data_seek(0);
                        while($row = $hourlyData->fetch_assoc()) {
                            $hours[$row['hour']] = $row['visits'];
                        }
                        echo implode(',', $hours);
                    ?>],
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
        
        // Top Pages Chart
        const pagesCtx = document.getElementById('pagesChart').getContext('2d');
        const pagesChart = new Chart(pagesCtx, {
            type: 'doughnut',
            data: {
                labels: [<?php 
                    $pageData->data_seek(0);
                    while($row = $pageData->fetch_assoc()) {
                        echo "'" . htmlspecialchars($row['page_visited']) . "',";
                    }
                ?>],
                datasets: [{
                    data: [<?php 
                        $pageData->data_seek(0);
                        while($row = $pageData->fetch_assoc()) {
                            echo $row['visits'] . ",";
                        }
                    ?>],
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                        '#FF9F40', '#8AC24A', '#607D8B', '#E91E63', '#3F51B5'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });
    </script>
</body>
</html>
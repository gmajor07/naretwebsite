<?php
require_once '../includes/db.php';


// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch customer registrations
$customers = $conn->query("SELECT * FROM web_customers ORDER BY registered_at  DESC");

// Fetch email subscribers
$subscribers = $conn->query("SELECT * FROM newsletter  ORDER BY subscribed_at  DESC");
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Services | NARET Admin</title>
    <!-- Your Admin CSS -->
    <link href="../assets/css/admin.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="container py-5">
  <h2 class="mb-4">Registered Customers</h2>
  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead class="table-primary">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Phone</th>
          <th>Registered At</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($customers->num_rows > 0): $i = 1; ?>
          <?php while ($row = $customers->fetch_assoc()): ?>
            <tr>
              <td><?= $i++ ?></td>
              <td><?= htmlspecialchars($row['name']) ?></td>
              <td><?= htmlspecialchars($row['phone']) ?></td>
              <td><?= $row['registered_at'] ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="4" class="text-center">No customer records found</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

  <h2 class="mt-5 mb-4">Email Subscribers</h2>
  <div class="table-responsive">
    <table class="table table-bordered table-striped">
      <thead class="table-success">
        <tr>
          <th>#</th>
          <th>Email</th>
          <th>Subscribed At</th>
        </tr>
      </thead>
      <tbody>
        <?php if ($subscribers->num_rows > 0): $j = 1; ?>
          <?php while ($row = $subscribers->fetch_assoc()): ?>
            <tr>
              <td><?= $j++ ?></td>
              <td><?= htmlspecialchars($row['email']) ?></td>
              <td><?= $row['subscribed_at'] ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="3" class="text-center">No subscribers yet</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
</body>
</html>

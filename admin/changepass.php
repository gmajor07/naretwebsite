<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

$user_id = $_SESSION['admin_id'] ?? null;
$error = "";
$success = "";

// Fetch user details
$stmt = $conn->prepare("SELECT id, username, password_hash, full_name FROM admin_users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check which form was submitted
  if (isset($_POST['update_profile'])) {
    // Handle profile update (username and name)
    $new_username = trim($_POST['username']);
    $new_full_name = trim($_POST['full_name']);
    
    // Validate inputs
    if (empty($new_full_name)) {
        $error = "Full name cannot be empty.";
    } elseif (empty($new_username)) {
        $error = "Username cannot be empty.";
    } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $new_username)) {
        $error = "Username can only contain letters, numbers, and underscores.";
    } else {
        // Check if new username is different from current
        if ($new_username !== $user['username']) {
            // Verify username isn't already taken
            $check_stmt = $conn->prepare("SELECT id FROM admin_users WHERE username = ? AND id != ?");
            $check_stmt->bind_param("si", $new_username, $user_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            
            if ($check_result->num_rows > 0) {
                $error = "Username is already taken.";
                $check_stmt->close();
            } else {
                $check_stmt->close();
                
                // Update both username and full name
                $stmt = $conn->prepare("UPDATE admin_users SET username = ?, full_name = ? WHERE id = ?");
                $stmt->bind_param("ssi", $new_username, $new_full_name, $user_id);
                
                if ($stmt->execute()) {
                    $success = "Profile updated successfully!";
                    // Update the displayed values
                    $user['username'] = $new_username;
                    $user['full_name'] = $new_full_name;
                    // Update session username if needed
                    $_SESSION['username'] = $new_username;
                } else {
                    $error = "Error updating profile.";
                }
                $stmt->close();
            }
        } else {
            // Only update full name if username hasn't changed
            $stmt = $conn->prepare("UPDATE admin_users SET full_name = ? WHERE id = ?");
            $stmt->bind_param("si", $new_full_name, $user_id);
            
            if ($stmt->execute()) {
                $success = "Profile updated successfully!";
                // Update the displayed name
                $user['full_name'] = $new_full_name;
            } else {
                $error = "Error updating profile.";
            }
            $stmt->close();
        }
    }
}
    elseif (isset($_POST['update_password'])) {
        // Handle password change
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        // Verify old password
        if (!password_verify($current_password, $user['password_hash'])) {
            $error = "Current password is incorrect.";
        } elseif (strlen($new_password) < 6) {
            $error = "New password must be at least 6 characters long.";
        } elseif ($new_password !== $confirm_password) {
            $error = "New passwords do not match.";
        } else {
            // Hash new password and update in database
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("UPDATE admin_users SET password_hash = ? WHERE id = ?");
            $stmt->bind_param("si", $hashed_password, $user_id);

            if ($stmt->execute()) {
                $success = "Password updated successfully!";
            } else {
                $error = "Error updating password.";
            }
            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="cardstyle.css" rel="stylesheet">
    <style>
        .profile-section {
            margin-bottom: 30px;
            padding: 20px;
            border-radius: 5px;
            background-color: #f8f9fa;
        }
        .section-title {
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
        <?php include 'navbar.php'; ?>

<div class="container mt-4">
    <h2 class="text-center mb-4">User Profile</h2>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <?php if (!empty($success)): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-6 mx-auto">
            <!-- Profile Information Section -->
            <div class="profile-section">
                <h4 class="section-title">Profile Information</h4>
                <form method="POST">
                    <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" class="form-control" 
                   value="<?php echo htmlspecialchars($user['username']); ?>" required>
            <div class="form-text">Letters, numbers, and underscores only</div>
        </div>  
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control" 
                               value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                    </div>
                    <button type="submit" name="update_profile" class="btn btn-primary w-100">Update Profile</button>
                </form>
            </div>

            <!-- Change Password Section -->
            <div class="profile-section">
                <h4 class="section-title">Change Password</h4>
                <form method="POST">
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>
                    <button type="submit" name="update_password" class="btn btn-primary w-100">Update Password</button>
                </form>
            </div>
        </div>
    </div>
</div>
</body>
</html>

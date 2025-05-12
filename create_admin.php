<?php
require_once 'includes/db.php';

// Secure admin creation
$adminUsername = 'admin';
$adminPassword = 'naretforlife'; // Change to a strong password!
$fullName = 'Admin User'; // Store in variable first

// Generate secure hash
$passwordHash = password_hash($adminPassword, PASSWORD_BCRYPT);

if ($passwordHash === false) {
    die("❌ Password hashing failed");
}

// Prepare statement
$query = "INSERT INTO admin_users (username, password_hash, full_name) VALUES (?, ?, ?)";
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("❌ Prepare failed: " . htmlspecialchars($conn->error));
}

// Bind parameters correctly
$stmt->bind_param("sss", $adminUsername, $passwordHash, $fullName);

// Execute and verify
if ($stmt->execute()) {
    echo "✅ Admin user created successfully!<br>";
    echo "Username: " . htmlspecialchars($adminUsername) . "<br>";
    echo "Password: [hidden]";
    
    // Security recommendation:
    echo "<div style='background:#ffebee;padding:15px;margin-top:20px;border-radius:5px;'>";
    echo "<strong>Security Notice:</strong> Delete this file immediately after use!";
    echo "</div>";
} else {
    if ($conn->errno === 1062) {
        die("⚠️ Admin user already exists");
    } else {
        die("❌ Creation failed: " . htmlspecialchars($conn->error));
    }
}

$stmt->close();
$conn->close();

// Auto-delete script for security (uncomment after verifying it works)
// unlink(__FILE__);
?>
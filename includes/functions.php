<?php
function getPageContent($page) {
    global $conn;
    $content = [];
    
    $query = "SELECT * FROM site_content WHERE page = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $page);
    $stmt->execute();
    $result = $stmt->get_result();
    
    while ($row = $result->fetch_assoc()) {
        $content[$row['section']] = $row['content'];
        if (!empty($row['title'])) {
            $content[$row['section'].'_title'] = $row['title'];
        }
    }
    
    return $content;
}

function getCarouselImages() {
    global $conn;
    $images = [];
    
    $query = "SELECT * FROM carousel_images WHERE is_active = TRUE ORDER BY display_order";
    $result = $conn->query($query);
    
    while ($row = $result->fetch_assoc()) {
        $images[] = $row;
    }
    
    return $images;
}

function getFeaturedServices() {
    global $conn;
    $services = [];
    
    $query = "SELECT * FROM services ORDER BY created_at DESC LIMIT 32";
    $result = $conn->query($query);
    
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
    
    return $services;
}



// Admin authentication functions
function adminLogin($username, $password) {
    global $conn;
    
    $query = "SELECT * FROM admin_users WHERE username = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();
        if (password_verify($password, $admin['password_hash'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_username'] = $admin['username'];
            
            // Update last login
            $updateQuery = "UPDATE admin_users SET last_login = NOW() WHERE id = ?";
            $updateStmt = $conn->prepare($updateQuery);
            $updateStmt->bind_param("i", $admin['id']);
            $updateStmt->execute();
            
            return true;
        }
    }
    
    return false;
}

function getSectionContent($page, $section) {
    global $conn;
    
    $query = "SELECT title, content FROM site_content 
              WHERE page = ? AND section = ? 
              LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $page, $section);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc() ?? [];
}

function getSectionContentDeci($page, $section) {
    global $conn;
    
    $query = "SELECT title, content, description FROM deci WHERE page = ? AND section = ? LIMIT 1";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $page, $section);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $data = $result->fetch_assoc() ?? [];
    
    // Use description if content is empty
    if (empty($data['content']) && !empty($data['description'])) {
        $data['content'] = $data['description'];
    }
    
    return $data;
}



function getAboutPoints($aboutId = null) {
    global $conn;
    $points = [];
    
    try {
        // Modified query to actually use $aboutId if needed
        $query = "SELECT point_text, has_icon 
                 FROM about_points 
                 " . ($aboutId ? "WHERE about_id = ?" : "") . "
                 ORDER BY display_order";
        
        $stmt = $conn->prepare($query);
        
        // Only bind parameter if $aboutId is used
        if ($aboutId) {
            $stmt->bind_param("i", $aboutId);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        
        while ($row = $result->fetch_assoc()) {
            $points[] = $row;
        }
    } catch (Exception $e) {
        // Log error and return fallback content
        error_log("Error getting about points: " . $e->getMessage());
    }
    
    // Fallback content if no points found
    if (empty($points)) {
        $points = [
            [
                'point_text' => "Naret company limited satisfies client freight forwarding customs, example\nDry bag clearance consultation services, and Tax and accounting services", 
                'has_icon' => 1
            ],
            [
                'point_text' => "It's the only company you can rely on to do what you want\nno matter how big or small your cargo is, we as NARET COMPANY", 
                'has_icon' => 1
            ],
            [
                'point_text' => "LIMITED offers comprehensive local, east, and African services for import and export. so no matter whether you require freight forwarding customs", 
                'has_icon' => 1
            ]
        ];
    }
    
    return $points;
}


function getDetailedServices() {
    global $conn;
    $services = [];
    
    $query = "SELECT * FROM services_detail WHERE is_active = TRUE ORDER BY display_order";
    $result = $conn->query($query);
    
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
    
    return $services;
}

function getDetailedServicesFumigation() {
    global $conn;
    $services = [];
    
    $query = "SELECT * FROM fumigation WHERE is_active = TRUE ORDER BY display_order";
    $result = $conn->query($query);
    
    while ($row = $result->fetch_assoc()) {
        $services[] = $row;
    }
    
    return $services;
}
function getRecentWorks() {
    global $conn;
    $works = [];
    
    $query = "SELECT * FROM recent_works WHERE is_active = TRUE ORDER BY display_order LIMIT 6";
    $result = $conn->query($query);
    
    while ($row = $result->fetch_assoc()) {
        $works[] = $row;
    }
    
    return $works;
}

function getClients() {
    global $conn;
    $clients = [];
    
    $query = "SELECT * FROM clients WHERE is_active = TRUE ORDER BY display_order LIMIT 3";
    $result = $conn->query($query);
    
    while ($row = $result->fetch_assoc()) {
        $clients[] = $row;
    }
    
    return $clients;
}

function getAllAboutImages() {
    global $conn;
    $result = $conn->query("SELECT image_path FROM about_images ORDER BY uploaded_at DESC");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function getAllCtaImages() {
    global $conn;
    $result = $conn->query("SELECT image_path FROM cta_images ORDER BY uploaded_at DESC");
    return $result->fetch_all(MYSQLI_ASSOC);
}

function handleImageUpload($inputName, $targetDir) {
    // Check if file was uploaded without errors
    if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("File upload failed or no file selected");
    }

    // Validate image type
    $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
    $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
    $mimeType = finfo_file($fileInfo, $_FILES[$inputName]['tmp_name']);
    
    if (!in_array($mimeType, $allowedTypes)) {
        throw new Exception("Only JPG, PNG, and WEBP images are allowed");
    }

    // Create directory if it doesn't exist
    if (!file_exists($targetDir)) {
        mkdir($targetDir, 0755, true);
    }

    // Generate unique filename
    $extension = pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION);
    $filename = 'carousel_' . uniqid() . '.' . $extension;
    $targetPath = $targetDir . $filename;

    // Move uploaded file
    if (!move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetPath)) {
        throw new Exception("Failed to move uploaded file");
    }

    // Return web-accessible path (without ../)
    return str_replace('../', '', $targetPath); // Returns "assets/img/carousel/filename.jpg"
}


function trackVisitor() {
    global $conn;
    
    // Get visitor information
    $ip = $_SERVER['REMOTE_ADDR'];
    $userAgent = substr($_SERVER['HTTP_USER_AGENT'], 0, 255);
    $page = $_SERVER['REQUEST_URI'];
    
    // Check if this IP visited in the last hour (to avoid multiple counts)
    $stmt = $conn->prepare("SELECT id FROM website_visitors 
                           WHERE ip_address = ? 
                           AND visit_time > NOW() - INTERVAL 1 HOUR
                           LIMIT 1");
    $stmt->bind_param("s", $ip);
    $stmt->execute();
    
    if ($stmt->get_result()->num_rows === 0) {
        // Record new visit
        $insert = $conn->prepare("INSERT INTO website_visitors 
                                 (ip_address, user_agent, page_visited) 
                                 VALUES (?, ?, ?)");
        $insert->bind_param("sss", $ip, $userAgent, $page);
        $insert->execute();
    }
}

function getTotalVisitors() {
    global $conn;
    $result = $conn->query("SELECT COUNT(DISTINCT ip_address) AS total_visitors FROM website_visitors");
    return $result->fetch_assoc()['total_visitors'];
}
function getTodayVisitors() {
    global $conn;
    $result = $conn->query("SELECT COUNT(DISTINCT ip_address) AS today_visitors 
                           FROM website_visitors 
                           WHERE DATE(visit_time) = CURDATE()");
    return $result->fetch_assoc()['today_visitors'];
}

function getHeaderContent($section = 'main') {
    global $conn; // Using your existing MySQLi connection
    
    $defaultContent = [
        'heading_text' => 'Find A <span class="text-primary">Best Services</span> In Miscellaneous Port Services',
        'paragraph_text' => 'The company is owned by Tanzania with good history, knowledge, experienced staff, and expertise in all operation.'
    ];
    
    try {
        $query = "SELECT heading_text, paragraph_text FROM header_content WHERE section_name = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $section);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        return $defaultContent;
    } catch (Exception $e) {
        error_log("Error getting header content: " . $e->getMessage());
        return $defaultContent;
    }
}



function getFeaturedVideo() {
    global $conn;
    $sql = "SELECT * FROM videos WHERE is_featured = TRUE ORDER BY created_at DESC LIMIT 1";
    $result = $conn->query($sql);
    return $result->fetch_assoc();
}

function getVideos() {
    global $conn;
    $sql = "SELECT * FROM videos ORDER BY is_featured DESC, created_at DESC";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}
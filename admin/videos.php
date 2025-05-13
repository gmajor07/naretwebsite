<?php
session_start();
require_once '../includes/db.php';
require_once '../includes/functions.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}


// In your form processing code, replace the validation with this:
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title']);
    $youtube_url = trim($_POST['youtube_id']);
    $description = trim($_POST['description']);
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    
    // Extract YouTube ID from various URL formats
    $youtube_id = '';
    if (preg_match('/^(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]{11})/', $youtube_url, $matches)) {
        $youtube_id = $matches[1];
    } elseif (preg_match('/^[a-zA-Z0-9_-]{11}$/', $youtube_url)) {
        $youtube_id = $youtube_url; // Already just an ID
    }
    
    if (!empty($youtube_id)) {
        // Add video to database
        $stmt = $conn->prepare("INSERT INTO videos (title, youtube_id, description, is_featured) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $title, $youtube_id, $description, $is_featured);
        
        if ($stmt->execute()) {
            $_SESSION['message'] = "Video added successfully!";
            if ($is_featured) {
                $conn->query("UPDATE videos SET is_featured = 0 WHERE id != " . $stmt->insert_id);
            }
        } else {
            $_SESSION['error'] = "Error adding video: " . $conn->error;
        }
    } else {
        $_SESSION['error'] = "Invalid YouTube URL or ID. Please use formats like: https://www.youtube.com/watch?v=VIDEO_ID or just VIDEO_ID";
    }
    
    header("Location: videos.php");
    exit;
}


// Handle video deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM videos WHERE id = $id");
    $_SESSION['message'] = "Video deleted successfully!";
    header("Location: videos.php");
    exit;
}

// Handle feature toggle
if (isset($_GET['feature'])) {
    $id = intval($_GET['feature']);
    $conn->query("UPDATE videos SET is_featured = 0"); // Unfeature all
    $conn->query("UPDATE videos SET is_featured = 1 WHERE id = $id"); // Feature selected
    $_SESSION['message'] = "Featured video updated!";
    header("Location: videos.php");
    exit;
}

$videos = getVideos();
?>

<?php include 'navbar.php'; ?>

<div class="container my-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2>Manage Videos</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addVideoModal">
            <i class="fas fa-plus"></i> Add Video
        </button>
    </div>

    <!-- Display messages -->
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-success"><?= $_SESSION['message'] ?></div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <div class="row g-4">
        <?php if (count($videos) > 0): ?>
            <?php foreach ($videos as $video): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100">
                    <div class="ratio ratio-16x9">
                        <iframe src="https://www.youtube.com/embed/<?= htmlspecialchars($video['youtube_id']) ?>" 
                                frameborder="0" 
                                allowfullscreen></iframe>
                    </div>
                    <a href="https://www.youtube.com/watch?v=aArTMaD0i8o" >play</a>
                    <div class="card-body">
                        <h5 class="card-title">
                            <?= htmlspecialchars($video['title']) ?>
                            <?php if ($video['is_featured']): ?>
                                <span class="badge bg-warning text-dark">Featured</span>
                            <?php endif; ?>
                        </h5>
                        <p class="card-text"><?= htmlspecialchars($video['description']) ?></p>
                    </div>
                    <div class="card-footer bg-transparent d-flex justify-content-between">
                        <a href="https://youtube.com/watch?v=<?= htmlspecialchars($video['youtube_id']) ?>" 
                           target="_blank" 
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-external-link-alt"></i> YouTube
                        </a>
                        <div>
                            <a href="videos.php?feature=<?= $video['id'] ?>" 
                               class="btn btn-sm <?= $video['is_featured'] ? 'btn-warning' : 'btn-outline-warning' ?>"
                               title="<?= $video['is_featured'] ? 'Featured' : 'Make Featured' ?>">
                                <i class="fas fa-star">Featured</i>
                            </a>
                            <a href="videos.php?delete=<?= $video['id'] ?>" 
                               class="btn btn-sm btn-outline-danger"
                               onclick="return confirm('Are you sure you want to delete this video?')">
                                <i class="fas fa-trash">Delete</i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="alert alert-info">No videos added yet.</div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Add Video Modal -->
<div class="modal fade" id="addVideoModal" tabindex="-1" aria-labelledby="addVideoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST">
                <div class="modal-header">
                    <h5 class="modal-title" id="addVideoModalLabel">Add New Video</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="youtube_id" class="form-label">YouTube URL or ID</label>
                        <input type="text" class="form-control" id="youtube_id" name="youtube_id" required>
                        <div class="form-text">Example: https://youtu.be/dQw4w9WgXcQ or just dQw4w9WgXcQ</div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured">
                        <label class="form-check-label" for="is_featured">Feature this video</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Video</button>
                </div>
            </form>
        </div>
    </div>
</div>

   <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
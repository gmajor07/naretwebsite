<?php
// Start session and include database connection
require_once 'includes/db.php';
require_once 'includes/functions.php';


// Track this visit
trackVisitor();

// Get visitor counts
$totalVisitors = getTotalVisitors();
$todayVisitors = getTodayVisitors();

// Get page content from database
$homeContent = getPageContent('home');
$carouselImages = getCarouselImages();
$services = getFeaturedServices();
$recentWorks = getRecentWorks();
$clients = getClients();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>NARET COMPANY LIMITED</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">
    
    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <!-- Navbar Start -->
        <div class="container-fluid nav-bar bg-transparent">
            <nav class="navbar navbar-expand-lg bg-white navbar-light py-0 px-4">
               <a href="index.php" class="navbar-brand d-flex align-items-center text-center flex-wrap">
    <div class="icon p-2 me-2">
        <img class="img-fluid" src="img/icon-deal.png" alt="Icon" style="width: 30px; height: 30px;">
    </div>
    <span class="text-primary fw-bold" style="font-size: 2rem;">NARET COMPANY LIMITED</span>
</a>

<br>
                <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarCollapse">
                    <div class="navbar-nav ms-auto">
                        <a href="index.php" class="nav-item nav-link active">Home</a>
                        <a href="about.php" class="nav-item nav-link">About</a>
                      
                        <div class="nav-item dropdown">
                            <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">Services</a>
                            <div class="dropdown-menu rounded-0 m-0">
                                <a href="miscellaneous.php" class="dropdown-item">Miscellaneous Port</a>
                                <a href="fumigation.php" class="dropdown-item">Fumigation</a>
                            </div>
                        </div>
                        <a href="deci.php" class="nav-item nav-link">Container Desiccants</a>
                        <a href="contact.php" class="nav-item nav-link">Contact</a>

                    </div>
                </div>
            </nav>
        </div>
        <!-- Navbar End -->


  <!-- CSS Styles -->
<style>
  .hero-section {
    position: relative;
    height: 700px; /* Adjust height as needed */
    overflow: hidden;
  }
  
  .hero-carousel .carousel-item {
    height: 100%;
    width: 100%;
  }
  
  .hero-carousel img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  
  .hero-content {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 2rem 0;
    text-align: center;
    color: white;
    z-index: 2;
  }
  
  .hero-overlay {
    background-color: rgba(0,0,0,0.7);
    padding: 2rem;
    border-radius: 0.5rem 0.5rem 0 0;
    max-width: 100%;
    margin: 0 auto;
    backdrop-filter: blur(2px);
  }
</style>

<!-- HTML Structure -->
<div class="hero-section">
  <!-- Carousel -->
  <div id="heroCarousel" class="carousel slide hero-carousel" data-bs-ride="carousel">
    <!-- Carousel as background -->
    <div class="owl-carousel header-carousel" style="height: 700px; width: 100%;">
        <?php foreach ($carouselImages as $image): ?>
        <div class="owl-carousel-item" style="
            height: 700px;
            background-image: url('<?= htmlspecialchars($image['image_path']) ?>');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        "></div>
        <?php endforeach; ?>
    </div>
  </div>
  
  <!-- Bottom Content Box -->
  <div class="hero-content">
    <div class="container">
      <div class="hero-overlay">
        <?php $headerContent = getHeaderContent('main'); ?>
        <h1 class="display-5 mb-3"><?= $headerContent['heading_text'] ?></h1>
        <p class="mb-4"><?= htmlspecialchars($headerContent['paragraph_text']) ?></p>
        <a href="#" class="btn btn-primary btn-lg px-5 py-2" data-bs-toggle="modal" data-bs-target="#registerModal">
          Get Started
        </a>
      </div>
    </div>
  </div>
</div>




<br>
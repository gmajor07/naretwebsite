
   <!-- Services Section - Dynamic -->
   <div class="container-xxl py-5">
            <div class="container">
     <?php
$servicesContent = getSectionContent('home', 'services');
?>

<div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
    <h1 class="mb-3"><?php echo htmlspecialchars($servicesContent['title'] ?? 'We Provide Best In Miscellaneous Port Services'); ?></h1>
    <p><?php echo htmlspecialchars($servicesContent['content'] ?? 'We store to offer our customers the lowest possible price the best available selection and all most convenience.'); ?></p>
</div>
          <div class="row g-3">
    <?php 
    $features = $conn->query("SELECT * FROM service_features ORDER BY display_order")->fetch_all(MYSQLI_ASSOC);
    foreach ($features as $feature): ?>
    <div class="col-lg-3 col-sm-6 wow fadeInUp" data-wow-delay="0.1s">
        <a class="cat-item d-block bg-light text-center rounded p-3" href="#">
            <div class="rounded p-4">
                <div class="icon mb-3">
                    <img class="img-fluid" 
                         src="<?= htmlspecialchars($feature['image_path']) ?>" 
                         alt="<?= htmlspecialchars($feature['title']) ?>">
                </div>
                <h6><?= htmlspecialchars($feature['title']) ?></h6>
                <span><?= htmlspecialchars($feature['description']) ?></span>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>
                
            </div>
        </div>
        <!-- Services End -->
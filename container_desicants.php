<!-- Services Section - Dynamic -->
<div class="container-xxl py-5">
    <div class="container">
        <?php
$servicesContent = $conn->query("SELECT title, content FROM services_content  LIMIT 1")->fetch_assoc();
?>

        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 900px;">
    <h1 class="mb-3"><?php echo htmlspecialchars($servicesContent['title'] ?? 'We Provide Best In Miscellaneous Port Services'); ?></h1>
    <p><?php echo htmlspecialchars($servicesContent['content'] ?? 'We store to offer our customers the lowest possible price the best available selection and all most convenience.'); ?></p>
</div>

        
        <div class="row g-4">
            <?php 
            $features = $conn->query("SELECT * FROM deci ORDER BY display_order")->fetch_all(MYSQLI_ASSOC);
            foreach ($features as $feature): ?>
            <div class="col-lg-6 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="service-item d-flex flex-column bg-light rounded overflow-hidden h-100">
                    <div class="position-relative">
                        <img class="img-fluid w-100" 
                             src="<?= htmlspecialchars($feature['image_path']) ?>" 
                             style="height: 450px; object-fit: cover;">
                    </div>
                    <div class="text-center p-4">
                        
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<!-- Services End -->
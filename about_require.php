
<!-- About Start -->
<div class="container-xxl py-5">
    <div class="container">
        <?php 
        $aboutContent = getSectionContent('home', 'about');
        $aboutImage = getAboutImage(); // Get image from our new simple system
        ?>
        
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                <div class="about-img position-relative overflow-hidden p-3 rounded-4 shadow-sm">
                    <img loading="lazy" class="img-fluid rounded-4 shadow" 
                         src="<?php echo htmlspecialchars($aboutImage); ?>" 
                         alt="About Image">
                </div>
            </div>

            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                <h1 class="mb-4"><?php echo htmlspecialchars($aboutContent['title'] ?? '#1 Top Leading Miscellaneous Port Services'); ?></h1>
                <p class="mb-4"><?php echo htmlspecialchars($aboutContent['content'] ?? 'NARET COMPANY LIMITED was established in 2017, under the company (cap 2022) with the objectives of providing the best services in Miscellaneous port services, surveyor, Fumigation, and general cleanness. The company is owned by Tanzania with good history, knowledge, experienced staff, and expertise in all operation.'); ?></p>
                
                <?php 
            $aboutPoints = getAboutPoints($aboutContent['id'] ?? null);
            ?>

            <?php foreach ($aboutPoints as $point): ?>
                <p class="mb-2">
                    <?php if ($point['has_icon']): ?>
                        <i class="fa fa-check text-primary me-3"></i>
                    <?php endif; ?>
                    <?php echo nl2br(htmlspecialchars($point['point_text'])); ?>
                </p>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<!-- About End -->
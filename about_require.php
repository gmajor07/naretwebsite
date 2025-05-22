<!-- About Start -->
<div class="container-xxl py-5">
    <div class="container">
        <?php 
        $aboutContent = getSectionContent('home', 'about');
        // Get ALL about images instead of just one
        $aboutImages = getAllAboutImages(); // You'll need to create this function
        ?>
        
        <div class="row g-5 align-items-center">
            <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                <?php if (!empty($aboutImages)): ?>
                <div class="about-img position-relative overflow-hidden p-3 rounded-4 shadow-sm">
                    <!-- Image Slider -->
                    <div id="aboutImageCarousel" class="carousel slide" data-bs-ride="carousel">
                        <!-- Indicators -->
                        <div class="carousel-indicators">
                            <?php foreach ($aboutImages as $index => $image): ?>
                                <button type="button" data-bs-target="#aboutImageCarousel" 
                                        data-bs-slide-to="<?= $index ?>" 
                                        class="<?= $index === 0 ? 'active' : '' ?>"
                                        aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                                        aria-label="Slide <?= $index + 1 ?>"></button>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Slides -->
                        <div class="carousel-inner rounded-4 shadow">
                            <?php foreach ($aboutImages as $index => $image): ?>
                                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                    <img loading="lazy" class="d-block w-100 img-fluid" 
                                         src="<?= htmlspecialchars($image['image_path']) ?>" 
                                         alt="About Image <?= $index + 1 ?>">
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Controls -->
                        <button class="carousel-control-prev" type="button" data-bs-target="#aboutImageCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#aboutImageCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
                <?php else: ?>
                    <div class="alert alert-warning">No images available for about section</div>
                <?php endif; ?>
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
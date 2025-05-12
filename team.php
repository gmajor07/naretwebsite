
  <!-- Team Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <h1 class="mb-3">Recent Works</h1>
        </div>
        <div class="row g-4">
            <?php 
            $recentWorks = getRecentWorks();
            foreach ($recentWorks as $work): 
                $socialLinks = json_decode($work['social_links'] ?? '[]', true);
            ?>
            <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="team-item rounded overflow-hidden">
                    <div class="position-relative">
                        <img class="img-fluid" src="<?php echo htmlspecialchars($work['image_path']); ?>" alt="<?php echo htmlspecialchars($work['title']); ?>">
                        <div class="position-absolute start-50 top-100 translate-middle d-flex align-items-center">
                            <?php if (in_array('facebook', $socialLinks)): ?>
                            <a class="btn btn-square mx-1" href=""><i class="fab fa-facebook-f"></i></a>
                            <?php endif; ?>
                            <?php if (in_array('twitter', $socialLinks)): ?>
                            <a class="btn btn-square mx-1" href=""><i class="fab fa-twitter"></i></a>
                            <?php endif; ?>
                            <?php if (in_array('instagram', $socialLinks)): ?>
                            <a class="btn btn-square mx-1" href=""><i class="fab fa-instagram"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="text-center p-4 mt-3">
                        <h5 class="fw-bold mb-0"><?php echo htmlspecialchars($work['title']); ?></h5>
                        <small><?php echo htmlspecialchars($work['description']); ?></small>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<!-- Team End -->
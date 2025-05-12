<!-- Testimonial Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
            <h1 class="mb-3">Our Clients</h1>
            <p>And we've formed more than just working relationships with them; we have formed true friendships.</p>
        </div>
        <div class="owl-carousel testimonial-carousel wow fadeInUp" data-wow-delay="0.1s">
            <?php 
            $clients = getClients();
            foreach ($clients as $client): 
            ?>
            <div class="testimonial-item bg-light rounded p-3">
                <div class="bg-white border rounded p-4">
                    <div class="d-flex align-items-center">
                        <img class="img-fluid flex-shrink-0 rounded" 
                             src="<?php echo htmlspecialchars($client['image_path']); ?>" 
                             style="width: 145px; height: 145px;"
                             loading="lazy">
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<!-- Testimonial End -->
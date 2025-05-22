<!-- Call to Action Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="bg-light rounded p-3">
            <div class="bg-white rounded p-4" style="border: 1px dashed rgba(66, 133, 244, .3)">
                <?php 
                $ctaContent = getSectionContent('home', 'call_to_action');
                $ctaDetails = json_decode($ctaContent['content'] ?? '{}', true);
                $ctaImages = getAllCtaImages(); // Get all CTA images from database
                ?>
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                        <?php if (!empty($ctaImages)): ?>
                        <!-- CTA Image Slider -->
                        <div id="ctaImageCarousel" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-indicators">
                                <?php foreach ($ctaImages as $index => $image): ?>
                                    <button type="button" data-bs-target="#ctaImageCarousel" 
                                            data-bs-slide-to="<?= $index ?>" 
                                            class="<?= $index === 0 ? 'active' : '' ?>"
                                            aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
                                            aria-label="Slide <?= $index + 1 ?>"></button>
                                <?php endforeach; ?>
                            </div>
                            
                            <div class="carousel-inner rounded">
                                <?php foreach ($ctaImages as $index => $image): ?>
                                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                        <img class="d-block w-100 img-fluid" 
                                             src="<?= htmlspecialchars($image['image_path']) ?>" 
                                             alt="Call to Action <?= $index + 1 ?>"
                                             loading="lazy">
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            
                            <button class="carousel-control-prev" type="button" data-bs-target="#ctaImageCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#ctaImageCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>
                        <?php else: ?>
                            <div class="alert alert-warning">No CTA images available</div>
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-6 wow fadeIn" data-wow-delay="0.5s">
                        <div class="mb-4">
                            <h1 class="mb-3"><?php echo htmlspecialchars($ctaContent['title'] ?? 'Welcome! 20% Off All Our Cleaning Services'); ?></h1>
                            <h5 class="text-primary mb-3">Need Help Call Us</h5>
                            <p><?php echo $ctaDetails['phone'] ?? '(+255) 753995084<br>(+255) 754689775'; ?></p>
                            <h5 class="text-primary mb-3">Send Your Mail To Us</h5>
                            <p><?php echo htmlspecialchars($ctaDetails['email'] ?? 'naret@naret.co.tz'); ?></p>
                            <h5 class="text-primary mb-3">Office Address</h5>
                            <p><?php echo $ctaDetails['address'] ?? 'Dar es Salaam, Tanzania P.o.Box 6230<br>Kurasini shimo la udongo road opposite of Gates5 stand'; ?></p>
                        </div>
                        <a href="tel:<?= htmlspecialchars(str_replace(['(', ')', ' ', '-'], '', $ctaDetails['phone'] ?? '+255753995084')) ?>" class="btn btn-primary py-3 px-4 me-2">
                            <i class="fa fa-phone-alt me-2"></i>Make A Call
                        </a>
                        <a href="#appointment" class="btn btn-dark py-3 px-4">
                            <i class="fa fa-calendar-alt me-2"></i>Get Appointment
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Map Section Start -->
        <div class="mt-5 wow fadeIn" data-wow-delay="0.1s">
            <div class="bg-light rounded p-3">
                <div class="bg-white rounded p-4" style="border: 1px dashed rgba(66, 133, 244, .3)">
                    <h2 class="mb-4 text-primary">Our Location</h2>
                    <div class="map-container" style="height: 400px; width: 100%;">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3962.041888030778!2d39.20657831532762!3d-6.822377295059482!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x185c4f0e5e5e5e5f%3A0x5e5e5e5e5e5e5e5e!2sKurasini%2C%20Dar%20es%20Salaam%2C%20Tanzania!5e0!3m2!1sen!2sus!4v1620000000000!5m2!1sen!2sus" 
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy">
                        </iframe>
                    </div>
                    <div class="text-center mt-3">
                        <a href="https://www.google.com/maps/dir//Kurasini,+Dar+es+Salaam,+Tanzania/@-6.822382,39.208767,16z/data=!4m8!4m7!1m0!1m5!1m1!1s0x185c4f0e5e5e5e5f:0x5e5e5e5e5e5e5e5e!2m2!1d39.2087669!2d-6.8223823" 
                           class="btn btn-primary" 
                           target="_blank">
                           <i class="fa fa-directions me-2"></i>Get Directions
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Map Section End -->
    </div>
</div>
<!-- Call to Action End -->


<script>
// Optional: Customize carousel behavior
document.addEventListener('DOMContentLoaded', function() {
    var ctaCarousel = new bootstrap.Carousel(document.getElementById('ctaImageCarousel'), {
        interval: 5000, // Rotate every 5 seconds
        pause: 'hover', // Pause on hover
        wrap: true      // Continuously cycle
    });
});
</script>
<!-- Call to Action Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="bg-light rounded p-3">
            <div class="bg-white rounded p-4" style="border: 1px dashed rgba(66, 133, 244, .3)">
                <?php 
                $ctaContent = getSectionContent('home', 'call_to_action');
                $ctaDetails = json_decode($ctaContent['content'] ?? '{}', true);
                $ctaImage = getCtaImage(); // Get image from our new simple system
                ?>
                <div class="row g-5 align-items-center">
                    <div class="col-lg-6 wow fadeIn" data-wow-delay="0.1s">
                        <img class="img-fluid rounded w-100" 
                             src="<?php echo htmlspecialchars($ctaImage); ?>" 
                             alt="Call to Action"
                             loading="lazy">
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
                        <a href="" class="btn btn-primary py-3 px-4 me-2"><i class="fa fa-phone-alt me-2"></i>Make A Call</a>
                        <a href="" class="btn btn-dark py-3 px-4"><i class="fa fa-calendar-alt me-2"></i>Get Appoinment</a>
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
                </div>
            </div>
        </div>
        <!-- Map Section End -->
        
    </div>
</div>
<!-- Call to Action End -->

<a href="https://www.google.com/maps/dir//Kurasini,+Dar+es+Salaam,+Tanzania/@-6.822382,39.208767,16z/data=!4m8!4m7!1m0!1m5!1m1!1s0x185c4f0e5e5e5e5f:0x5e5e5e5e5e5e5e5e!2m2!1d39.2087669!2d-6.8223823" 
   class="btn btn-primary mt-3" 
   target="_blank">
   <i class="fa fa-directions me-2"></i>Get Directions
</a>


<style>
    .map-container {
        position: relative;
        width: 100%;
        height: 400px;
        overflow: hidden;
    }
    .map-container iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    @media (max-width: 768px) {
    .map-container {
        height: 300px;
    }
}

</style>
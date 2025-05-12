
<!-- Call to Action Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="bg-light rounded p-3">
            <div class="bg-white rounded p-4" style="border: 1px dashed rgba(0, 185, 142, .3)">
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
        </div>
    </div>
</div>
<!-- Call to Action End -->
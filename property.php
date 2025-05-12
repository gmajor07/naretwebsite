      <!-- Property List Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-0 gx-5 align-items-end">
            <div class="col-lg-6">
                <div class="text-start mx-auto mb-5 wow slideInLeft" data-wow-delay="0.1s">
                    <?php $servicesHeader = getSectionContent('home', 'services_header'); ?>
                    <h1 class="mb-3"><?php echo htmlspecialchars($servicesHeader['title'] ?? 'Best Services'); ?></h1>
                    <p><?php echo htmlspecialchars($servicesHeader['content'] ?? 'WE deals with ports services'); ?></p>
                </div>
            </div>
        </div>
        
        <div class="tab-content">
            <div id="tab-1" class="tab-pane fade show p-0 active">
                <div class="row g-4">
                    <?php 
                    $services = getDetailedServices();
                    foreach ($services as $service): 
                    ?>
                    <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="property-item rounded overflow-hidden">
                            <div class="position-relative overflow-hidden">
                                <a href=""><img class="img-fluid" src="<?php echo htmlspecialchars($service['image_path']); ?>" alt="<?php echo htmlspecialchars($service['title']); ?>"></a>
                                <?php if ($service['badge_text']): ?>
                                <div class="bg-primary rounded text-white position-absolute start-0 top-0 m-4 py-1 px-3"><?php echo htmlspecialchars($service['badge_text']); ?></div>
                                <?php endif; ?>
                                <?php if ($service['category']): ?>
                                <div class="bg-white rounded-top text-primary position-absolute start-0 bottom-0 mx-4 pt-1 px-3"><?php echo htmlspecialchars($service['category']); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="p-4 pb-0">
                                <h5 class="text-primary mb-3"><?php echo htmlspecialchars($service['title']); ?></h5>
                                <p><i class="fa fa-map-marker-alt text-primary me-2"></i><?php echo htmlspecialchars($service['description']); ?></p>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    
                    <div class="col-12 text-center wow fadeInUp" data-wow-delay="0.1s">
                        <a class="btn btn-primary py-3 px-5" href="">Browse More Property</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Property List End -->
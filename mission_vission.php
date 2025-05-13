       <!-- Search Start -->
        <style>
            .vision, .mission {
                font-size: 18px;
                font-weight: bold;
                padding: 20px;
                text-align: center;
                color: #fff;
            }
        </style>
        
       <!-- Vision/Mission Section - Enhanced Styling -->
<div class="container-fluid mb-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="vision-mission-container bg-primary-gradient py-5">
        <div class="container">
            <div class="row g-4 align-items-stretch">
                <!-- Vision Column -->
                <div class="col-lg-6">
                    <div class="vision-card h-100 p-4 p-lg-5 rounded-4 shadow-sm">
                        <div class="d-flex align-items-center mb-4">
                            <div class="icon-box bg-primary text-white rounded-circle me-3">
                                <i class="fas fa-eye fa-2x"></i>
                            </div>
                            <h2 class="h1 mb-0 text-primary">OUR VISION</h2>
                        </div>
                        <div class="vision-content">
                            <p class="lead mb-0"><?= htmlspecialchars($homeContent['vision_text'] ?? 'To be the leading company that prioritizes customer satisfaction through excellence in port services') ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Mission Column -->
                <div class="col-lg-6">
                    <div class="mission-card h-100 p-4 p-lg-5 rounded-4 shadow-sm">
                        <div class="d-flex align-items-center mb-4">
                            <div class="icon-box bg-secondary text-white rounded-circle me-3">
                                <i class="fas fa-bullseye fa-2x"></i>
                            </div>
                            <h2 class="h1 mb-0 text-sucess">OUR MISSION</h2>
                        </div>
                        <div class="mission-content">
                            <p class="lead mb-0"><?= htmlspecialchars($homeContent['mission_text'] ?? 'Our goal is to offer customers the best prices, with quality and efficient services in all our operations') ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom Styles */
    .vision-mission-container {
        background: linear-gradient(135deg,rgb(34, 76, 227) 0%,rgb(72, 43, 238) 100%);
    }
    
    .vision-card, .mission-card {
        background: rgba(255, 255, 255, 0.95);
        transition: all 0.3s ease;
        border-left: 5px solid;
    }
    
    .vision-card {
       border-left-color:rgb(129, 134, 131);
    }
    
    .mission-card {
        border-left-color:rgb(129, 134, 131);
    }
    
    .vision-card:hover, .mission-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .icon-box {
        width: 60px;
        height: 60px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    @media (max-width: 991.98px) {
        .vision-card, .mission-card {
            margin-bottom: 20px;
        }
    }
</style>

<?php
require_once __DIR__ . '/includes/functions.php';
$about = getAboutContent();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - TRIPLE K PROPERTIES</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .about-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            transition: transform 0.3s ease;
        }
        .about-image img:hover {
            transform: scale(1.02);
        }
        .about-image {
            background: none !important;
            min-height: 400px;
            overflow: hidden;
            border-radius: var(--border-radius);
            position: relative;
        }
        .about-image .image-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.7));
            padding: 30px;
            color: white;
            text-align: center;
        }
        .about-image .image-overlay span {
            font-size: 1.2rem;
            font-weight: 600;
            letter-spacing: 2px;
        }
        .about-image .image-overlay i {
            margin-right: 10px;
            color: var(--secondary);
        }
        @media (max-width: 768px) {
            .about-image {
                min-height: 280px;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="page-wrapper page-about">
        <!-- Page Header -->
        <section class="page-header">
            <div class="container">
                <h1>About Triple K Properties</h1>
                <p>Your trusted partner in real estate across Kenya</p>
            </div>
        </section>

        <!-- About Content -->
        <section class="about-section">
            <div class="container">
                <div class="about-grid">
                    <div class="about-image">
                        <!-- Professional Building Image from Unsplash -->
                        <img src="https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&h=600&fit=crop&crop=center" 
                             alt="Triple K Properties - Modern Office Building" 
                             loading="lazy"
                             onerror="this.onerror=null; this.parentElement.innerHTML = '<i class=\'fas fa-building\' style=\'font-size: 5rem; color: var(--primary);\'></i><p style=\'color: var(--text-light); margin-top: 15px;\'>Triple K Properties</p>'">
                        <div class="image-overlay">
                            <span><i class="fas fa-home"></i> Triple K Properties - Premier Real Estate</span>
                        </div>
                    </div>
                    <div class="about-content">
                        <?php if ($about): ?>
                            <h2><?php echo htmlspecialchars($about['title']); ?></h2>
                            <p><?php echo nl2br(htmlspecialchars($about['content'])); ?></p>
                        <?php else: ?>
                            <h2>About Triple K Properties</h2>
                            <p>Triple K Properties is a premier real estate agency dedicated to helping you find your dream property in Kenya. With years of experience in the industry, we pride ourselves on providing exceptional service and expert guidance to our clients.</p>
                        <?php endif; ?>
                        
                        <div class="mission-vision">
                            <div class="mv-card">
                                <h3><i class="fas fa-bullseye"></i> Our Mission</h3>
                                <p><?php echo $about ? nl2br(htmlspecialchars($about['mission'])) : 'To provide transparent, efficient, and personalized real estate services that exceed our clients expectations.'; ?></p>
                            </div>
                            <div class="mv-card">
                                <h3><i class="fas fa-eye"></i> Our Vision</h3>
                                <p><?php echo $about ? nl2br(htmlspecialchars($about['vision'])) : 'To be the most trusted and innovative real estate company in East Africa.'; ?></p>
                            </div>
                        </div>
                        
                        <div style="display: flex; flex-wrap: wrap; gap: 10px; margin-top: 20px;">
                            <a href="contact.php" class="btn btn-primary">Get In Touch</a>
                            <a href="properties.php" class="btn btn-outline">View Properties</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Our Values -->
        <section style="background: white; padding: 60px 0;">
            <div class="container">
                <div class="section-title">
                    <h2>Our Core Values</h2>
                </div>
                <div class="values-grid">
                    <div class="value-card">
                        <i class="fas fa-handshake"></i>
                        <h4>Integrity</h4>
                        <p>We operate with honesty and transparency in all our dealings.</p>
                    </div>
                    <div class="value-card">
                        <i class="fas fa-star"></i>
                        <h4>Excellence</h4>
                        <p>We strive for excellence in service delivery and client satisfaction.</p>
                    </div>
                    <div class="value-card">
                        <i class="fas fa-users"></i>
                        <h4>Teamwork</h4>
                        <p>We collaborate to provide the best solutions for our clients.</p>
                    </div>
                    <div class="value-card">
                        <i class="fas fa-lightbulb"></i>
                        <h4>Innovation</h4>
                        <p>We embrace technology to enhance the real estate experience.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Our Team -->
        <section style="padding: 60px 0;">
            <div class="container">
                <div class="section-title">
                    <h2>Meet Our Team</h2>
                </div>
                <div class="team-grid">
                    <div class="team-card">
                        <div class="avatar"><i class="fas fa-user-tie"></i></div>
                        <h4>John Kamau</h4>
                        <p class="role">CEO & Founder</p>
                        <p class="experience">15 years experience</p>
                    </div>
                    <div class="team-card">
                        <div class="avatar"><i class="fas fa-user"></i></div>
                        <h4>Mary Wanjiru</h4>
                        <p class="role">Senior Agent</p>
                        <p class="experience">10 years experience</p>
                    </div>
                    <div class="team-card">
                        <div class="avatar"><i class="fas fa-user"></i></div>
                        <h4>Peter Ochieng</h4>
                        <p class="role">Property Manager</p>
                        <p class="experience">8 years experience</p>
                    </div>
                    <div class="team-card">
                        <div class="avatar"><i class="fas fa-user"></i></div>
                        <h4>Sarah Akinyi</h4>
                        <p class="role">Client Relations</p>
                        <p class="experience">6 years experience</p>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/chatbot.php'; ?>
    
    <script src="assets/js/script.js"></script>
</body>
</html>
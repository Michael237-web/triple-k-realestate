<?php
require_once __DIR__ . '/includes/functions.php';

// Get properties from database (6 featured)
$dbProperties = getProperties(6, true);

// Get all properties for display
$allProperties = getProperties();

$testimonials = getTestimonials();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TRIPLE K PROPERTIES - Premier Real Estate in Kenya</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="icon" type="images/png" href="images/logo.png">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="page-wrapper page-home">
        <!-- Hero Section with Animated Image Slideshow -->
        <section class="hero">
            <!-- Background Slideshow -->
            <div class="hero-slideshow">
                <!-- Slide 1 - From Right -->
                <div class="slide slide-right" style="background-image: url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=1920&h=1080&fit=crop&crop=center');">
                    <div class="slide-direction-label">From Right</div>
                </div>
                
                <!-- Slide 2 - From Bottom (Active) -->
                <div class="slide slide-bottom active" style="background-image: url('https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=1920&h=1080&fit=crop&crop=center');">
                    <div class="slide-direction-label">From Bottom</div>
                </div>
                
                <!-- Slide 3 - From Left -->
                <div class="slide slide-left" style="background-image: url('https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=1920&h=1080&fit=crop&crop=center');">
                    <div class="slide-direction-label">From Left</div>
                </div>
                
                <!-- Slide 4 - From Top-Right -->
                <div class="slide slide-top-right" style="background-image: url('https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=1920&h=1080&fit=crop&crop=center');">
                    <div class="slide-direction-label">From Top-Right</div>
                </div>
                
                <!-- Slide 5 - From Bottom-Left -->
                <div class="slide slide-bottom-left" style="background-image: url('https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=1920&h=1080&fit=crop&crop=center');">
                    <div class="slide-direction-label">From Bottom-Left</div>
                </div>
                
                <!-- Dark Overlay -->
                <div class="hero-overlay"></div>
            </div>
            
            <div class="hero-container">
                <div class="hero-content">
                    <h1 id="animatedHeading">
                        <!-- Words will be populated by JavaScript -->
                    </h1>
                    <p>Discover premium real estate in Kenya. From prime land to luxury homes, we have the perfect property for you.</p>
                    <div class="hero-buttons">
                        <a href="properties.php" class="btn btn-primary">Browse Properties</a>
                        <a href="contact.php" class="btn btn-secondary">Contact Us</a>
                    </div>
                </div>
                <div class="hero-image-wrapper">
                    <div class="hero-image-container">
                        <div class="animated-shape" id="animatedShape">
                            <img src="https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&h=800&fit=crop&crop=center" alt="Luxury Apartment" class="shape-image shape-image-oval">
                            <img src="https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800&h=800&fit=crop&crop=center" alt="Modern Building" class="shape-image shape-image-square">
                             
                            <div class="shape-overlay">
                                <div class="shape-overlay-content">
                                    <i class="fas fa-home"></i>
                                    <span>TRIPLE K</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Search Section -->
        <section class="search-section">
            <div class="container">
                <form id="searchForm" class="search-form">
                    <div class="form-group">
                        <label for="searchLocation"><i class="fas fa-map-marker-alt"></i> Location</label>
                        <input type="text" id="searchLocation" placeholder="Search by city or area">
                    </div>
                    <div class="form-group">
                        <label for="propertyType"><i class="fas fa-building"></i> Property Type</label>
                        <select id="propertyType">
                            <option value="all">All Types</option>
                            <option value="land">Land</option>
                            <option value="house">House</option>
                            <option value="apartment">Apartment</option>
                            <option value="commercial">Commercial</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="minPrice"><i class="fas fa-arrow-up"></i> Min Price (Ksh)</label>
                        <input type="number" id="minPrice" placeholder="Min">
                    </div>
                    <div class="form-group">
                        <label for="maxPrice"><i class="fas fa-arrow-down"></i> Max Price (Ksh)</label>
                        <input type="number" id="maxPrice" placeholder="Max">
                    </div>
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
        </section>

        <!-- Featured Properties -->
        <section class="properties-section">
            <div class="container">
                <div class="section-title">
                    <h2>Featured Properties</h2>
                    <p>Explore our handpicked selection of premium properties across Kenya</p>
                </div>
                <div class="property-grid">
                    <?php 
                    // Get featured properties
                    $featuredProperties = array_filter($allProperties, function($p) {
                        return isset($p['featured']) && $p['featured'] == 1;
                    });
                    // Limit to 6 featured properties
                    $featuredProperties = array_slice($featuredProperties, 0, 6);
                    
                    foreach ($featuredProperties as $property): 
                    ?>
                    <div class="property-card">
                        <div class="property-image">
                            <?php if (!empty($property['image_url'])): ?>
                                <img src="<?php echo htmlspecialchars($property['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($property['title']); ?>"
                                     onerror="this.src='assets/images/default-property.jpg'">
                            <?php else: ?>
                                <img src="assets/images/default-property.jpg" 
                                     alt="<?php echo htmlspecialchars($property['title']); ?>">
                            <?php endif; ?>
                            <span class="property-badge">Featured</span>
                            <?php if (isset($property['property_type'])): ?>
                            <span class="property-badge property-type-badge">
                                <?php echo ucfirst($property['property_type']); ?>
                            </span>
                            <?php endif; ?>
                        </div>
                        <div class="property-info">
                            <h3><?php echo htmlspecialchars($property['title']); ?></h3>
                            <p class="property-location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($property['location']); ?></p>
                            <div class="property-price">
                                <span class="price-ksh">Ksh <?php echo number_format($property['price_ksh']); ?></span>
                                <span class="price-usd">$<?php echo number_format($property['price_usd']); ?></span>
                            </div>
                            <div class="property-details">
                                <?php if (!empty($property['bedrooms']) && $property['bedrooms'] > 0): ?>
                                <span><i class="fas fa-bed"></i> <?php echo $property['bedrooms']; ?></span>
                                <?php endif; ?>
                                <?php if (!empty($property['bathrooms']) && $property['bathrooms'] > 0): ?>
                                <span><i class="fas fa-bath"></i> <?php echo $property['bathrooms']; ?></span>
                                <?php endif; ?>
                                <?php if (!empty($property['size_sqft']) && $property['size_sqft'] > 0): ?>
                                <span><i class="fas fa-vector-square"></i> <?php echo number_format($property['size_sqft']); ?> sqft</span>
                                <?php endif; ?>
                            </div>
                            <a href="property-details.php?id=<?php echo $property['id']; ?>" class="btn btn-outline" style="margin-top:15px;width:100%;">View Details</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <!-- All Properties Section -->
                <div class="section-title" style="margin-top: 80px;">
                    <h2>All Properties</h2>
                    <p>Browse our complete collection of apartments, land, and commercial properties in Kenya</p>
                </div>
                <div class="property-grid">
                    <?php 
                    // Display all properties
                    $displayProperties = array_slice($allProperties, 0, 20);
                    foreach ($displayProperties as $property): 
                    ?>
                    <div class="property-card">
                        <div class="property-image">
                            <?php if (!empty($property['image_url'])): ?>
                                <img src="<?php echo htmlspecialchars($property['image_url']); ?>" 
                                     alt="<?php echo htmlspecialchars($property['title']); ?>"
                                     onerror="this.src='assets/images/default-property.jpg'">
                            <?php else: ?>
                                <img src="assets/images/default-property.jpg" 
                                     alt="<?php echo htmlspecialchars($property['title']); ?>">
                            <?php endif; ?>
                            <?php if (isset($property['property_type'])): ?>
                            <span class="property-badge property-type-badge">
                                <?php echo ucfirst($property['property_type']); ?>
                            </span>
                            <?php endif; ?>
                        </div>
                        <div class="property-info">
                            <h3><?php echo htmlspecialchars($property['title']); ?></h3>
                            <p class="property-location"><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($property['location']); ?></p>
                            <div class="property-price">
                                <span class="price-ksh">Ksh <?php echo number_format($property['price_ksh']); ?></span>
                                <span class="price-usd">$<?php echo number_format($property['price_usd']); ?></span>
                            </div>
                            <div class="property-details">
                                <?php if (!empty($property['bedrooms']) && $property['bedrooms'] > 0): ?>
                                <span><i class="fas fa-bed"></i> <?php echo $property['bedrooms']; ?></span>
                                <?php endif; ?>
                                <?php if (!empty($property['bathrooms']) && $property['bathrooms'] > 0): ?>
                                <span><i class="fas fa-bath"></i> <?php echo $property['bathrooms']; ?></span>
                                <?php endif; ?>
                                <?php if (!empty($property['size_sqft']) && $property['size_sqft'] > 0): ?>
                                <span><i class="fas fa-vector-square"></i> <?php echo number_format($property['size_sqft']); ?> sqft</span>
                                <?php endif; ?>
                            </div>
                            <a href="property-details.php?id=<?php echo $property['id']; ?>" class="btn btn-outline" style="margin-top:15px;width:100%;">View Details</a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div style="text-align: center; margin-top: 30px;">
                    <a href="properties.php" class="btn btn-primary">View All Properties</a>
                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section class="testimonials">
            <div class="container">
                <div class="section-title">
                    <h2>What Our Clients Say</h2>
                    <p>Real experiences from real people who found their dream properties with us</p>
                </div>
                <div class="testimonial-grid">
                    <?php foreach ($testimonials as $testimonial): ?>
                    <div class="testimonial-card">
                        <div class="rating">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                            <i class="fas fa-star <?php echo $i <= $testimonial['rating'] ? '' : 'far'; ?>"></i>
                            <?php endfor; ?>
                        </div>
                        <blockquote>"<?php echo htmlspecialchars($testimonial['testimonial']); ?>"</blockquote>
                        <p class="client-name">- <?php echo htmlspecialchars($testimonial['client_name']); ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </div>

    <div class="floating-contact">
    <a href="https://wa.me/254746674121?text=Hello%20MichaelStore%2C%20I%20need%20assistance..." 
       class="contact-item whatsapp" 
       target="_blank" 
       rel="noopener noreferrer"
       aria-label="Chat on WhatsApp">
        <span class="label">💬 Chat on WhatsApp</span>
        <span class="icon-wrapper">
            💬
            <span class="pulse-dot"></span>
        </span>
    </a>

    <a href="tel:+254746674121" 
       class="contact-item phone" 
       aria-label="Call us">
        <span class="label">📞 Call Us Now</span>
        <span class="icon-wrapper">
            📞
            <span class="pulse-dot"></span>
        </span>
    </a>
</div>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/chatbot.php'; ?>
    
    <script src="assets/js/script.js"></script>
</body>
</html>
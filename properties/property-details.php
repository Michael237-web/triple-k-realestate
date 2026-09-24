<?php
require_once __DIR__ . '/includes/functions.php';

$id = $_GET['id'] ?? 0;
$property = getPropertyById($id);

if (!$property) {
    header('Location: properties.php');
    exit;
}

/**
 * Get a single property image from Unsplash based on property type
 */
function getPropertyImageUrl($property) {
    $type = strtolower($property['property_type'] ?? 'apartment');
    $id = $property['id'] ?? 1;
    
    // Use property ID to select a consistent image
    $themeIndex = $id % 8;
    
    $images = [
        0 => 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?w=800&h=600&fit=crop&crop=center',
        1 => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800&h=600&fit=crop&crop=center',
        2 => 'https://images.unsplash.com/photo-1580587771525-78b9dba3b914?w=800&h=600&fit=crop&crop=center',
        3 => 'https://images.unsplash.com/photo-1545324418-cc1a3fa10c00?w=800&h=600&fit=crop&crop=center',
        4 => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800&h=600&fit=crop&crop=center',
        5 => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800&h=600&fit=crop&crop=center',
        6 => 'https://images.unsplash.com/photo-1564013799919-ab600027ffc6?w=800&h=600&fit=crop&crop=center',
        7 => 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?w=800&h=600&fit=crop&crop=center'
    ];
    
    // If property has its own image, use it
    if (!empty($property['image_url']) && filter_var($property['image_url'], FILTER_VALIDATE_URL)) {
        return $property['image_url'];
    }
    
    return $images[$themeIndex];
}

// Get single image for this property
$propertyImage = getPropertyImageUrl($property);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($property['title']); ?> - TRIPLE K PROPERTIES</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="page-wrapper page-details">
        <section class="property-detail">
            <div class="container">
                <div class="detail-card">
                    <!-- Single Property Image -->
                    <div class="detail-image-container">
                        <img src="<?php echo htmlspecialchars($propertyImage); ?>" 
                             alt="<?php echo htmlspecialchars($property['title']); ?>"
                             onerror="this.onerror=null; this.src='https://via.placeholder.com/800x600/2c3e50/ffffff?text=<?php echo urlencode($property['title']); ?>';">
                        <?php if (isset($property['property_type'])): ?>
                        <span class="property-badge-large">
                            <i class="fas fa-<?php echo getPropertyTypeIcon($property['property_type']); ?>"></i>
                            <?php echo ucfirst($property['property_type']); ?>
                        </span>
                        <?php endif; ?>
                        <span class="property-status-badge">
                            <i class="fas fa-circle" style="color: <?php echo $property['status'] === 'available' ? '#4caf50' : '#e53e3e'; ?>;"></i>
                            <?php echo ucfirst($property['status']); ?>
                        </span>
                    </div>
                    
                    <div class="detail-body">
                        <h1><?php echo htmlspecialchars($property['title']); ?></h1>
                        <div class="detail-location">
                            <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($property['location']); ?>
                        </div>
                        
                        <div class="detail-price-group">
                            <div>
                                <span class="detail-price-label">Price (Ksh)</span>
                                <span class="detail-price-ksh">Ksh <?php echo number_format($property['price_ksh']); ?></span>
                            </div>
                            <div>
                                <span class="detail-price-label">Price (USD)</span>
                                <span class="detail-price-usd">$<?php echo number_format($property['price_usd']); ?></span>
                            </div>
                        </div>
                        
                        <div class="detail-meta-grid">
                            <?php if ($property['property_type']): ?>
                            <span><i class="fas fa-tag"></i> <?php echo ucfirst($property['property_type']); ?></span>
                            <?php endif; ?>
                            <?php if ($property['bedrooms'] && $property['bedrooms'] > 0): ?>
                            <span><i class="fas fa-bed"></i> <?php echo $property['bedrooms']; ?> Bedrooms</span>
                            <?php endif; ?>
                            <?php if ($property['bathrooms'] && $property['bathrooms'] > 0): ?>
                            <span><i class="fas fa-bath"></i> <?php echo $property['bathrooms']; ?> Bathrooms</span>
                            <?php endif; ?>
                            <?php if ($property['size_sqft'] && $property['size_sqft'] > 0): ?>
                            <span><i class="fas fa-vector-square"></i> <?php echo number_format($property['size_sqft']); ?> sqft</span>
                            <?php endif; ?>
                            <span><i class="fas fa-calendar"></i> Listed: <?php echo date('M d, Y', strtotime($property['created_at'])); ?></span>
                        </div>
                        
                        <div class="detail-description">
                            <h3>Description</h3>
                            <p><?php echo nl2br(htmlspecialchars($property['description'] ?? 'No description available.')); ?></p>
                        </div>
                        
                        <div class="detail-actions">
                            <a href="contact?property=<?php echo urlencode($property['title']); ?>" class="btn btn-primary">
                                <i class="fas fa-phone"></i> Inquire Now
                            </a>
                            <a href="properties" class="btn btn-outline">
                                <i class="fas fa-arrow-left"></i> Back to Properties
                            </a>
                        </div>
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
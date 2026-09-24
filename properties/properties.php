<?php
require_once __DIR__ . '/includes/functions.php';

// Get filter parameters
$location = $_GET['location'] ?? '';
$type = $_GET['type'] ?? '';
$minPrice = $_GET['min_price'] ?? '';
$maxPrice = $_GET['max_price'] ?? '';

// Build query
$sql = "SELECT * FROM t_properties WHERE status = 'available'";
$params = [];

if ($location) {
    $sql .= " AND (title LIKE ? OR location LIKE ?)";
    $params[] = "%$location%";
    $params[] = "%$location%";
}

if ($type && $type !== 'all') {
    $sql .= " AND property_type = ?";
    $params[] = $type;
}

if ($minPrice) {
    $sql .= " AND price_ksh >= ?";
    $params[] = $minPrice;
}

if ($maxPrice) {
    $sql .= " AND price_ksh <= ?";
    $params[] = $maxPrice;
}

$sql .= " ORDER BY created_at DESC";

try {
    $db = Database::getInstance();
    $stmt = $db->query($sql, $params);
    $properties = $stmt->fetchAll();
} catch (Exception $e) {
    $properties = [];
}

// Get property types for filter
$propertyTypes = ['land', 'house', 'apartment', 'commercial'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Properties - TRIPLE K PROPERTIES</title>
    <link rel="stylesheet" href="assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <div class="page-wrapper page-properties">
        <!-- Page Header -->
        <section class="page-header">
            <div class="container">
                <h1>Our Properties</h1>
                <p>Discover your dream property from our extensive collection</p>
            </div>
        </section>

        <!-- Filter Section -->
        <section class="filter-section">
            <div class="container" style="padding: 0;">
                <form method="GET" class="filter-form" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; align-items: end;">
                    <div class="form-group">
                        <label for="location"><i class="fas fa-map-marker-alt"></i> Location</label>
                        <input type="text" id="location" name="location" value="<?php echo htmlspecialchars($location); ?>" placeholder="Search by location">
                    </div>
                    <div class="form-group">
                        <label for="type"><i class="fas fa-building"></i> Property Type</label>
                        <select id="type" name="type">
                            <option value="all">All Types</option>
                            <?php foreach ($propertyTypes as $pt): ?>
                            <option value="<?php echo $pt; ?>" <?php echo $type === $pt ? 'selected' : ''; ?>>
                                <?php echo ucfirst($pt); ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="min_price"><i class="fas fa-arrow-up"></i> Min Price (Ksh)</label>
                        <input type="number" id="min_price" name="min_price" value="<?php echo htmlspecialchars($minPrice); ?>" placeholder="Min">
                    </div>
                    <div class="form-group">
                        <label for="max_price"><i class="fas fa-arrow-down"></i> Max Price (Ksh)</label>
                        <input type="number" id="max_price" name="max_price" value="<?php echo htmlspecialchars($maxPrice); ?>" placeholder="Max">
                    </div>
                    <button type="submit" class="btn btn-primary" style="height: 48px;">Search Properties</button>
                </form>
            </div>
        </section>

        <!-- Properties Grid -->
        <section style="padding: 20px 0 60px;">
            <div class="container">
                <?php if (count($properties) > 0): ?>
                    <div style="margin-bottom: 20px; color: var(--text-light);">
                        <i class="fas fa-building"></i> <strong><?php echo count($properties); ?></strong> properties found
                    </div>
                    <div class="property-grid">
                        <?php foreach ($properties as $property): ?>
                        <div class="property-card">
                            <div class="property-image">
                                <?php if (!empty($property['image_url'])): ?>
                                    <img src="<?php echo htmlspecialchars($property['image_url']); ?>" 
                                         alt="<?php echo htmlspecialchars($property['title']); ?>"
                                         onerror="this.src='assets/images/default-property.jpg'">
                                <?php else: ?>
                                    <div class="property-image-placeholder">
                                        <i class="fas fa-building"></i>
                                    </div>
                                <?php endif; ?>
                                <?php if (isset($property['property_type'])): ?>
                                <span class="property-badge" style="background: var(--primary); right: auto; left: 15px; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 1px;">
                                    <?php echo ucfirst($property['property_type']); ?>
                                </span>
                                <?php endif; ?>
                            </div>
                            <div class="property-info">
                                <h3><?php echo htmlspecialchars($property['title']); ?></h3>
                                <div class="property-location">
                                    <i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($property['location']); ?>
                                </div>
                                <div class="property-price">
                                    <span class="price-ksh">Ksh <?php echo number_format($property['price_ksh']); ?></span>
                                    <span class="price-usd">$<?php echo number_format($property['price_usd']); ?></span>
                                </div>
                                <div class="property-details">
                                    <?php if (!empty($property['bedrooms']) && $property['bedrooms'] > 0): ?>
                                    <span><i class="fas fa-bed"></i> <?php echo $property['bedrooms']; ?> bed</span>
                                    <?php endif; ?>
                                    <?php if (!empty($property['bathrooms']) && $property['bathrooms'] > 0): ?>
                                    <span><i class="fas fa-bath"></i> <?php echo $property['bathrooms']; ?> bath</span>
                                    <?php endif; ?>
                                    <?php if (!empty($property['size_sqft']) && $property['size_sqft'] > 0): ?>
                                    <span><i class="fas fa-vector-square"></i> <?php echo number_format($property['size_sqft']); ?> sqft</span>
                                    <?php endif; ?>
                                </div>
                                <a href="property-details.php?id=<?php echo $property['id']; ?>" class="btn btn-outline" style="margin-top:15px;width:100%;text-align:center;display:block;">View Details</a>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-properties">
                        <i class="fas fa-home"></i>
                        <h3>No properties found</h3>
                        <p>Try adjusting your search filters or browse all properties.</p>
                        <a href="properties.php" class="btn btn-primary" style="margin-top: 20px;">View All Properties</a>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </div>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/chatbot.php'; ?>
    
    <script src="assets/js/script.js"></script>
</body>
</html>
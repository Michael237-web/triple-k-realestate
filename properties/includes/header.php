<?php
// Detect current page for active link highlighting
$currentPage = basename($_SERVER['PHP_SELF'], '.php');
$currentPage = ($currentPage === '' || $currentPage === 'header') ? 'index' : $currentPage;

// Detect property type from query string (for dropdown highlighting)
$currentType = isset($_GET['type']) ? strtolower($_GET['type']) : '';

// Determine which top-level nav item should be "active"
$isHome       = ($currentPage === 'index');
$isAbout      = ($currentPage === 'about');
$isProperties = ($currentPage === 'properties' || $currentPage === 'property-details');
$isContact    = ($currentPage === 'contact');
?>

<header class="header">
    <div class="container">
        <nav class="navbar">
            <div class="logo">
                <img src="images/logo.png" alt="TRIPLE K PROPERTIES" class="logo-image">
            </div>

            <button class="hamburger" id="hamburger" aria-label="Toggle navigation">
                <i class="fas fa-bars"></i>
            </button>

            <ul class="nav-menu" id="navMenu">
                <li>
                    <a href="index" class="<?php echo $isHome ? 'active' : ''; ?>">Home</a>
                </li>
                <li>
                    <a href="about" class="<?php echo $isAbout ? 'active' : ''; ?>">About</a>
                </li>
                <li class="dropdown">
                    <a href="properties" class="<?php echo $isProperties ? 'active' : ''; ?>">
                        Properties <i class="fas fa-chevron-down"></i>
                    </a>
                    <ul class="dropdown-content">
                        <li><a href="properties?type=land"       class="<?php echo ($currentType === 'land')       ? 'active' : ''; ?>">Land</a></li>
                        <li><a href="properties?type=house"      class="<?php echo ($currentType === 'house')      ? 'active' : ''; ?>">Houses</a></li>
                        <li><a href="properties?type=apartment"  class="<?php echo ($currentType === 'apartment')  ? 'active' : ''; ?>">Apartments</a></li>
                        <li><a href="properties?type=commercial" class="<?php echo ($currentType === 'commercial') ? 'active' : ''; ?>">Commercial</a></li>
                    </ul>
                </li>
                <li>
                    <a href="contact" class="<?php echo $isContact ? 'active' : ''; ?>">Contact</a>
                </li>
            </ul>
        </nav>
    </div>
</header>
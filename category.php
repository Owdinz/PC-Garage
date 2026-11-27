
<?php
include 'db.php';

// ==========================================
// GET FILTER VALUES
// ==========================================

$category = $_GET['category'] ?? '';

$selectedCategories = $_GET['cat'] ?? [];
$selectedBrands = $_GET['brand'] ?? [];

$maxPrice = isset($_GET['max_price']) && is_numeric($_GET['max_price'])
    ? max(0, min(500000, (float) $_GET['max_price']))
    : 500000;

$sort = $_GET['sort'] ?? 'featured';


// ==========================================
// VALIDATE FILTER VALUES
// ==========================================

$allowedCategories = ['GPU', 'CPU', 'RAM', 'Storage', 'PC Case', 'PSU', 'Motherboard', 'Laptop', 'Peripheral'];
$allowedBrands = ['ASUS', 'AMD', 'Kingston', 'Samsung', 'Corsair', 'NZXT', 'Logitech'];

if (!is_array($selectedCategories)) {
    $selectedCategories = [];
}

if (!is_array($selectedBrands)) {
    $selectedBrands = [];
}

$selectedCategories = array_values(array_unique(array_intersect($selectedCategories, $allowedCategories)));

$selectedBrands = array_values(array_unique(array_intersect($selectedBrands, $allowedBrands)));


// ==========================================
// BUILD SQL QUERY
// ==========================================

$sql = "SELECT * FROM products WHERE 1=1";

$params = [];
$types = "";


// ORIGINAL SINGLE CATEGORY FILTER

if (!empty($category)) {
    $sql .= " AND category = ?";
    $params[] = $category;
    $types .= "s";
}


// MULTIPLE CATEGORY FILTER

if (!empty($selectedCategories)) {

    $placeholders = implode(
        ',',
        array_fill(0, count($selectedCategories), '?')
    );

    $sql .= " AND category IN ($placeholders)";

    foreach ($selectedCategories as $cat) {
        $params[] = $cat;
        $types .= "s";
    }
}


// BRAND FILTER

if (!empty($selectedBrands)) {

    $placeholders = implode(
        ',',
        array_fill(0, count($selectedBrands), '?')
    );

    $sql .= " AND brand IN ($placeholders)";

    foreach ($selectedBrands as $brand) {
        $params[] = $brand;
        $types .= "s";
    }
}


// PRICE FILTER

if (isset($_GET['max_price']) && is_numeric($_GET['max_price'])) {

    $sql .= " AND price <= ?";

    $params[] = $maxPrice;
    $types .= "d";
}


// ==========================================
// SORTING
// ==========================================

switch ($sort) {

    case 'price-asc':
        $sql .= " ORDER BY price ASC";
        break;

    case 'price-desc':
        $sql .= " ORDER BY price DESC";
        break;

    case 'featured':
    default:
        $sql .= " ORDER BY product_id DESC";
        $sort = 'featured';
        break;
}


// ==========================================
// EXECUTE QUERY
// ==========================================

$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Query preparation failed: " . $conn->error);
}

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();

$result = $stmt->get_result();

$totalResults = $result->num_rows;

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>PC-Garage | Filtered Products</title>

    <link rel="stylesheet" href="stylesheet.css" />

</head>

<body class="shop-filtered-page">

    <!-- HEADER -->

    <header class="site-header">

        <a class="brand" href="home.php" aria-label="PC-Garage home">

            <span class="brand-mark">◆</span>

            <span class="brand-name">PC-GARAGE</span>

            <small>PC COMPONENTS</small>

        </a>

        <nav class="main-nav" aria-label="Primary navigation">

            <details class="menu-dropdown">

                <summary class="menu-toggle">Menu</summary>

                <div class="menu-links">

                    <a href="home.php">Home</a>
                    <a href="category.php">Main Shop</a>
                    <a href="category.php" class="active">Filtered Shop</a>
                    <a href="custom.php">Custom Build</a>
                    <a href="about.php">About Us</a>
                    <a href="buy-sell.php">Buy &amp; Sell</a>
                    <a href="cart.php">Cart</a>
                    <a href="profile.php">Profile</a>

                </div>

            </details>

        </nav>

    </header>


    <!-- FILTERED PRODUCTS -->

    <section class="shop-content">

        <h2 style="margin-bottom:20px;">
            Filtered Products
        </h2>

        <main class="shop-layout">

            <!-- FILTER SIDEBAR -->

            <aside class="shop-sidebar">

                <h2>Filter Parts</h2>

                <form method="GET" action="category.php" id="filterForm">

                    <!-- CATEGORY FILTER -->

                    <div class="filter-group">

                        <h3>Category</h3>

                        <label>
                            <input
                                type="checkbox"
                                name="cat[]"
                                value="GPU"
                                <?= in_array('GPU', $selectedCategories, true) ? 'checked' : '' ?>
                            />
                            Graphics Cards
                        </label>

                        <label>
                            <input
                                type="checkbox"
                                name="cat[]"
                                value="CPU"
                                <?= in_array('CPU', $selectedCategories, true) ? 'checked' : '' ?>
                            />
                            Processors
                        </label>

                        <label>
                            <input
                                type="checkbox"
                                name="cat[]"
                                value="RAM"
                                <?= in_array('RAM', $selectedCategories, true) ? 'checked' : '' ?>
                            />
                            Memory (RAM)
                        </label>

                        <label>
                            <input
                                type="checkbox"
                                name="cat[]"
                                value="Storage"
                                <?= in_array('Storage', $selectedCategories, true) ? 'checked' : '' ?>
                            />
                            SSDs &amp; Storage
                        </label>

                        <label>
                            <input
                                type="checkbox"
                                name="cat[]"
                                value="PC Case"
                                <?= in_array('PC Case', $selectedCategories, true) ? 'checked' : '' ?>
                            />
                            PC Cases
                        </label>

                        <label><input type="checkbox" name="cat[]" value="PSU" <?= in_array('PSU', $selectedCategories, true) ? 'checked' : '' ?> /> Power Supplies</label>
                        <label><input type="checkbox" name="cat[]" value="Motherboard" <?= in_array('Motherboard', $selectedCategories, true) ? 'checked' : '' ?> /> Motherboards</label>
                        <label><input type="checkbox" name="cat[]" value="Laptop" <?= in_array('Laptop', $selectedCategories, true) ? 'checked' : '' ?> /> Laptops</label>
                        <label><input type="checkbox" name="cat[]" value="Peripheral" <?= in_array('Peripheral', $selectedCategories, true) ? 'checked' : '' ?> /> Peripherals</label>
                    </div>


                    <!-- PRICE FILTER -->

                    <div class="filter-group">

                        <h3>Max Price (₱)</h3>

                        <input
                            type="range"
                            name="max_price"
                            id="priceRange"
                            min="0"
                            max="500000"
                            step="5000"
                            value="<?= htmlspecialchars((string)$maxPrice) ?>"
                        />

                        <div>
                            Under ₱<span id="priceVal"><?= number_format($maxPrice) ?></span>
                        </div>

                    </div>


                    <!-- BRAND FILTER -->
                    <div class="filter-group">
                        <h3>Brand</h3>
                        <label><input type="checkbox" name="brand[]" value="ASUS" <?= in_array('ASUS', $selectedBrands, true) ? 'checked' : '' ?> /> ASUS</label>
                        <label><input type="checkbox" name="brand[]" value="AMD" <?= in_array('AMD', $selectedBrands, true) ? 'checked' : '' ?> /> AMD</label>
                        <label><input type="checkbox" name="brand[]" value="Kingston" <?= in_array('Kingston', $selectedBrands, true) ? 'checked' : '' ?> /> Kingston</label>
                        <label><input type="checkbox" name="brand[]" value="Samsung" <?= in_array('Samsung', $selectedBrands, true) ? 'checked' : '' ?> /> Samsung</label>
                        <label><input type="checkbox" name="brand[]" value="Corsair" <?= in_array('Corsair', $selectedBrands, true) ? 'checked' : '' ?> /> Corsair</label>
                        <label><input type="checkbox" name="brand[]" value="NZXT" <?= in_array('NZXT', $selectedBrands, true) ? 'checked' : '' ?> /> NZXT</label>
                        <label><input type="checkbox" name="brand[]" value="Logitech" <?= in_array('Logitech', $selectedBrands, true) ? 'checked' : '' ?> /> Logitech</label>
                    </div>

                    <!-- PRESERVE SORTING -->

                    <input
                        type="hidden"
                        name="sort"
                        value="<?= htmlspecialchars($sort) ?>"
                    />


                    <!-- APPLY FILTERS -->

                    <button
                        type="submit"
                        class="home-button"
                        style="width:100%; text-align:center; margin-top:10px;"
                    >
                        Apply Filters
                    </button>


                    <!-- RESET FILTERS -->

                    <a
                        href="category.php"
                        class="home-button"
                        style="display:block; text-align:center; margin-top:10px;"
                    >
                        Reset Filters
                    </a>

                </form>

            </aside>


            <!-- PRODUCT GRID CONTENT -->

            <section class="shop-content">

                <!-- SHOP BAR -->

                <div class="shop-bar">

                    <span>
                        Showing
                        <strong><?= $totalResults ?></strong>
                        filtered results
                    </span>

                    <!-- SORTING -->

                    <select class="sort-select" id="sortSelect">

                        <option
                            value="featured"
                            <?= $sort == 'featured' ? 'selected' : '' ?>
                        >
                            Sort by: Featured
                        </option>

                        <option
                            value="price-asc"
                            <?= $sort == 'price-asc' ? 'selected' : '' ?>
                        >
                            Price: Low to High
                        </option>

                        <option
                            value="price-desc"
                            <?= $sort == 'price-desc' ? 'selected' : '' ?>
                        >
                            Price: High to Low
                        </option>

                    </select>

                </div>


                <!-- PRODUCT CARDS -->

                <div class="product-cards-grid">

                    <?php if ($totalResults > 0): ?>

                        <?php while ($row = $result->fetch_assoc()): ?>

                            <article class="part-card">

                                <img
                                    src="<?= htmlspecialchars(
                                        !empty($row['image_url'])
                                            ? $row['image_url']
                                            : 'placeholder.jpg'
                                    ) ?>"
                                    alt="<?= htmlspecialchars($row['product_name']) ?>"
                                />

                                <div class="part-details">

                                    <span class="part-category">
                                        <?= htmlspecialchars($row['category']) ?>
                                    </span>

                                    <h4>
                                        <?= htmlspecialchars($row['product_name']) ?>
                                    </h4>

                                    <p class="part-desc">
                                        <?= !empty($row['description'])
                                            ? htmlspecialchars($row['description'])
                                            : 'No description available.' ?>
                                    </p>

                                    <div class="part-bottom">

                                        <span class="part-price">
                                            ₱<?= number_format($row['price'], 2) ?>
                                        </span>

                                        <a
                                            href="product.php?id=<?= urlencode($row['product_id']) ?>"
                                            class="home-button"
                                        >
                                            View Details
                                        </a>

                                    </div>

                                </div>

                            </article>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <p class="no-products">
                            No products found matching your filters.
                            Try adjusting your filters.
                        </p>

                    <?php endif; ?>

                </div>

            </section>

        </main>

    </section>


    <!-- FOOTER -->

    <footer class="site-footer">

        <div class="footer-brand">

            <h2>PCGarage</h2>

            <p>
                Premium custom gaming rigs and PC components built for enthusiasts.
            </p>

        </div>

        <div>

            <h3>Shop</h3>

            <a href="home.php">Home</a>
            <a href="category.php">Products</a>
            <a href="custom.php">Custom PC Builder</a>

        </div>

        <div>

            <h3>Support</h3>

            <a href="about.php">About Us</a>
            <a href="login.php">Login / Sign-up</a>
            <a href="cart.php">Cart</a>

        </div>

    </footer>


    <!-- EXTERNAL JAVASCRIPT -->

    <script src="filter.js"></script>

</body>

</html>
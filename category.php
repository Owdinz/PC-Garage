 <!-- php section -->
<?php
include 'db.php';

$category = isset($_GET['category'])
    ? $_GET['category']
    : '';

$sql = "SELECT * FROM products";

if (!empty($category)) {
    $stmt = $conn->prepare(
        "SELECT * FROM products WHERE category = ?"
    );
    $stmt->bind_param("s", $category);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query($sql);
}
?>

 <!-- html seection -->
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PC-Garage | Filtered Products</title>
  <link rel="stylesheet" href="stylesheet.css" />
</head>
<body class="shop-filtered-page">
  <header class="site-header">
    <a class="brand" href="home.html" aria-label="PC-Garage home">
      <span class="brand-mark">◆</span>
      <span class="brand-name">PC-GARAGE</span>
      <small>PC COMPONENTS</small>
    </a>

    <nav class="main-nav" aria-label="Primary navigation">
      <details class="menu-dropdown">
        <summary class="menu-toggle">Menu</summary>
        <div class="menu-links">
          <a href="home.php">Home</a>
          <a href="product.html">Main Shop</a>
          <a href="category.php" class="active">Filtered Shop</a>
          <a href="custom.html">Custom Build</a>
          <a href="about.html">About Us</a>
          <a href="buy-sell.html">Buy &amp; Sell</a>
          <a href="cart.html">Cart</a>
          <a href="profile.html">Profile</a>
        </div>
      </details>
    </nav>
  </header>

<!-- inserted -->
<aside class="shop-sidebar">
...
</aside>

<h2 style="margin-bottom:20px;">
    <?php echo htmlspecialchars($category); ?> Products
</h2>

<!-- Product Grid Content -->
<section class="shop-content">

  <main class="shop-layout">
    <!-- Filter Sidebar -->
    <aside class="shop-sidebar">
      <h2>Filter Parts</h2>

      <div class="filter-group">
        <h3>Category</h3>
        <label><input type="checkbox" name="cat" value="gpu" checked /> Graphics Cards</label>
        <label><input type="checkbox" name="cat" value="cpu" checked /> Processors</label>
        <label><input type="checkbox" name="cat" value="ram" /> Memory (RAM)</label>
        <label><input type="checkbox" name="cat" value="storage" /> SSDs &amp; Storage</label>
        <label><input type="checkbox" name="cat" value="cases" /> PC Cases</label>
      </div>

      <div class="filter-group">
        <h3>Max Price (₱)</h3>
        <input type="range" id="priceRange" min="5000" max="250000" step="5000" value="100000" oninput="document.getElementById('priceVal').innerText = Number(this.value).toLocaleString('en-PH')" />
        <div>Under ₱<span id="priceVal">100,000</span></div>
      </div>

      <div class="filter-group">
        <h3>Brand</h3>
        <label><input type="checkbox" name="brand" value="nvidia" checked /> NVIDIA</label>
        <label><input type="checkbox" name="brand" value="amd" checked /> AMD</label>
        <label><input type="checkbox" name="brand" value="intel" /> Intel</label>
        <label><input type="checkbox" name="brand" value="asus" /> ASUS</label>
      </div>

      <button class="home-button" style="width:100%; text-align:center; margin-top:10px;">Apply Filters</button>
    </aside>

    <!-- Product Grid Content -->
    <section class="shop-content">
      <div class="shop-bar">
        <span>
    Showing <strong><?php echo mysqli_num_rows($result); ?></strong>
    filtered results
</span>
        <select class="sort-select">
          <option value="featured">Sort by: Featured</option>
          <option value="price-asc">Price: Low to High</option>
          <option value="price-desc">Price: High to Low</option>
          <option value="rating">Highest Rated</option>
        </select>
      </div>

      <div class="product-cards-grid">

<!-- php section again -->    
<?php while($row = mysqli_fetch_assoc($result)) { ?>

  <article class="part-card">

    <img
    src="<?php echo !empty($row['image_url']) ? $row['image_url'] : 'placeholder.jpg'; ?>"
    alt="<?php echo htmlspecialchars($row['product_name']); ?>"
/>

    <div class="part-details">

      <span class="part-category">
        <?php echo htmlspecialchars($row['category']); ?>
      </span>

      <h4>
        <?php echo htmlspecialchars($row['product_name']); ?>
      </h4>

      <p class="part-desc">
        <?php
        echo !empty($row['description'])
          ? htmlspecialchars($row['description'])
          : 'No description available.';
        ?>
      </p>

      <div class="part-bottom">

        <span class="part-price">
          ₱<?php echo number_format($row['price'], 2); ?>
        </span>

        <a href="product.html" class="home-button">
          View Details
        </a>

      </div>

    </div>

  </article>

<?php } ?>

</div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="footer-brand">
      <h2>PCGarage</h2>
      <p>Premium custom gaming rigs and PC components built for enthusiasts.</p>
    </div>
    <div>
      <h3>Shop</h3>
      <a href="home.php">Home</a>
      <a href="product.html">Products</a>
      <a href="custom.html">Custom PC Builder</a>
    </div>
    <div>
      <h3>Support</h3>
      <a href="about.html">About Us</a>
      <a href="login.html">Login / Sign-up</a>
      <a href="cart.html">Cart</a>
    </div>
  </footer>
</body>
</html>
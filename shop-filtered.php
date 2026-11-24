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
          <a href="product.php">Main Shop</a>
          <a href="shop-filtered.php" class="active">Filtered Shop</a>
          <a href="custom.php">Custom Build</a>
          <a href="about.php">About Us</a>
          <a href="buy-sell.php">Buy &amp; Sell</a>
          <a href="cart.php">Cart</a>
          <a href="profile.php">Profile</a>
        </div>
      </details>
    </nav>
  </header>

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
        <span>Showing <strong>3</strong> filtered results</span>
        <select class="sort-select">
          <option value="featured">Sort by: Featured</option>
          <option value="price-asc">Price: Low to High</option>
          <option value="price-desc">Price: High to Low</option>
          <option value="rating">Highest Rated</option>
        </select>
      </div>

      <div class="product-cards-grid">
        <!-- Card 1 -->
        <article class="part-card">
          <img src="https://images.unsplash.com/photo-1587202372775-e229f172b9d7?w=500&amp;auto=format&amp;fit=crop&amp;q=60" alt="RTX 4080 Super" />
          <div class="part-details">
            <span class="part-category">GPU</span>
            <h4>NVIDIA GeForce RTX 4080 Super 16GB</h4>
            <p class="part-desc">Extreme 4K gaming performance with DLSS 3.5 ray reconstruction technology.</p>
            <div class="part-bottom">
              <span class="part-price">₱62,500.00</span>
              <a href="product.php" class="home-button">View Details</a>
            </div>
          </div>
        </article>

        <!-- Card 2 -->
        <article class="part-card">
          <img src="https://images.unsplash.com/photo-1591799264318-7e6ef8ddb7ea?w=500&amp;auto=format&amp;fit=crop&amp;q=60" alt="AMD Ryzen 7 7800X3D" />
          <div class="part-details">
            <span class="part-category">CPU</span>
            <h4>AMD Ryzen 7 7800X3D 8-Core Processor</h4>
            <p class="part-desc">The undisputed king of desktop gaming CPUs featuring 3D V-Cache.</p>
            <div class="part-bottom">
              <span class="part-price">₱22,500.00</span>
              <a href="product.php" class="home-button">View Details</a>
            </div>
          </div>
        </article>

        <!-- Card 3 -->
        <article class="part-card">
          <img src="https://images.unsplash.com/photo-1562976540-1502c2145186?w=500&amp;auto=format&amp;fit=crop&amp;q=60" alt="Corsair Vengeance DDR5" />
          <div class="part-details">
            <span class="part-category">RAM</span>
            <h4>Corsair Vengeance 32GB (2x16GB) DDR5 6000MHz</h4>
            <p class="part-desc">Optimized high-bandwidth memory kit for AMD EXPO and Intel XMP 3.0.</p>
            <div class="part-bottom">
              <span class="part-price">₱7,500.00</span>
              <a href="product.php" class="home-button">View Details</a>
            </div>
          </div>
        </article>
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
      <a href="product.php">Products</a>
      <a href="custom.php">Custom PC Builder</a>
    </div>
    <div>
      <h3>Support</h3>
      <a href="about.php">About Us</a>
      <a href="login.php">Login / Sign-up</a>
      <a href="cart.php">Cart</a>
    </div>
  </footer>
</body>
</html>
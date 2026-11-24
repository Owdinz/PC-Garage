<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>PC-Garage | Custom Build Breakdown & Summary</title>
  <link rel="stylesheet" href="stylesheet.css" />
</head>
<body class="custom-summary-page">
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
          <a href="product.php">Products</a>
          <a href="shop-filtered.php">Filtered Shop</a>
          <a href="custom.php">Custom Builder</a>
          <a href="custom-summary.php" class="active">Build Summary</a>
          <a href="about.php">About Us</a>
          <a href="buy-sell.php">Buy and Sell</a>
          <a href="cart.php">Cart</a>
          <a href="profile.php">Profile</a>
        </div>
      </details>
    </nav>
  </header>

  <main class="summary-container">
    <div class="summary-header">
      <div>
        <h1>CUSTOM BUILD BREAKDOWN</h1>
        <p>Review hardware compatibility, thermal margins, and total system cost before adding to cart.</p>
      </div>
      <a href="custom.php" class="home-button" style="background:#444;">← Edit Builder Parts</a>
    </div>

    <!-- Compatibility Status Box -->
    <div class="status-banner success">
      <div class="status-icon">✔</div>
      <div>
        <strong>System Compatibility Verified: No Socket or Physical Clearance Issues Detected.</strong>
        <p>Estimated Total Power Consumption: <strong>520 Watts</strong> (Recommended PSU: 750W or higher).</p>
      </div>
    </div>

    <!-- Parts Table -->
    <table class="summary-table">
      <thead>
        <tr>
          <th>Component Type</th>
          <th>Selected Hardware Item</th>
          <th>Estimated Wattage</th>
          <th style="text-align:right;">Price</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><strong>Processor (CPU)</strong></td>
          <td>AMD Ryzen 7 7800X3D (AM5 Socket)</td>
          <td>120 W</td>
          <td class="price-col">₱22,500.00</td>
        </tr>
        <tr>
          <td><strong>Graphics Card (GPU)</strong></td>
          <td>NVIDIA GeForce RTX 4070 Ti Super 16GB</td>
          <td>285 W</td>
          <td class="price-col">₱48,900.00</td>
        </tr>
        <tr>
          <td><strong>Motherboard</strong></td>
          <td>ASUS ROG Strix B650-E Gaming WiFi (AM5)</td>
          <td>45 W</td>
          <td class="price-col">₱15,500.00</td>
        </tr>
        <tr>
          <td><strong>RAM / Memory</strong></td>
          <td>32GB (2x16GB) G.Skill Trident Z5 DDR5 6000MHz</td>
          <td>15 W</td>
          <td class="price-col">₱7,990.00</td>
        </tr>
        <tr>
          <td><strong>Storage</strong></td>
          <td>2TB Samsung 990 PRO NVMe PCIe 4.0 M.2 SSD</td>
          <td>10 W</td>
          <td class="price-col">₱10,500.00</td>
        </tr>
        <tr>
          <td><strong>Power Supply</strong></td>
          <td>Corsair RM750x 750W 80+ Gold Fully Modular</td>
          <td>—</td>
          <td class="price-col">₱7,200.00</td>
        </tr>
        <tr>
          <td><strong>Case &amp; Cooling</strong></td>
          <td>Lian Li O11 Dynamic EVO + NZXT Kraken 360 RGB</td>
          <td>40 W</td>
          <td class="price-col">₱18,500.00</td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="total-row">
          <td colspan="3"><strong>Total System Price</strong></td>
          <td class="price-col"><strong>₱131,090.00</strong></td>
        </tr>
      </tfoot>
    </table>

    <div class="summary-actions">
      <button class="home-button" onclick="saveBuild()">Save / Share Build Link</button>
      <button class="home-button" style="background:#169de0;" onclick="addToCartAndCheckout()">Add Entire Rig to Cart →</button>
    </div>
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
      <a href="cart.php">Cart</a>
      <a href="login.php">Login / Register</a>
    </div>
  </footer>

  <script src="cart-manager.js" defer></script>
<script>
  function saveBuild() {
    navigator.clipboard.writeText(window.location.href);
    alert("Build configuration link copied to your clipboard!");
  }

  function addToCartAndCheckout() {
    const customRig = [
      { name: "AMD Ryzen 7 7800X3D (AM5)", price: 22500.00, quantity: 1 },
      { name: "NVIDIA GeForce RTX 4070 Ti Super 16GB", price: 48900.00, quantity: 1 },
      { name: "ASUS ROG Strix B650-E Gaming WiFi", price: 15500.00, quantity: 1 },
      { name: "32GB G.Skill Trident Z5 DDR5 6000MHz", price: 7990.00, quantity: 1 },
      { name: "2TB Samsung 990 PRO NVMe SSD", price: 10500.00, quantity: 1 },
      { name: "Corsair RM750x 750W 80+ Gold PSU", price: 7200.00, quantity: 1 },
      { name: "Lian Li O11 Dynamic EVO + NZXT Kraken 360", price: 18500.00, quantity: 1 }
    ];

    customRig.forEach(item => {
      CartManager.addItem(item.name, item.price, item.quantity);
    });

    alert("Custom Build configuration added to your shopping cart!");
    window.location.href = "cart.php";
  }
</script>
</body>
</html>
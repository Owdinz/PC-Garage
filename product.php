<?php
include 'db.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare(
    "SELECT * FROM products WHERE product_id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

$product = $result->fetch_assoc();

if (!$product) {
    die("Product not found.");
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>
PCGarage |
<?php echo htmlspecialchars($product['product_name']); ?>
</title>
  <link rel="stylesheet" href="stylesheet.css" />
  <script src="cart-manager.js" defer></script>
</head>
<body>
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
          <a href="category.php">Products</a>
          <a href="category.php">Filtered Shop</a>
          <a href="custom.php">Custom Build</a>
          <a href="about.php">About Us</a>
          <a href="buy-sell.php">Buy and Sell</a>
          <a href="cart.php">Cart</a>
          <a class="profile-button" href="profile.php">Profile</a>
        </div>
      </details>
    </nav>
  </header>

  <main>
    <section class="product-detail" aria-labelledby="product-title">
      <div class="hero-image">
  <img
      src="<?php echo !empty($product['image_url']) ? $product['image_url'] : 'placeholder.jpg'; ?>"
      alt="<?php echo htmlspecialchars($product['product_name']); ?>"
  />
</div>
      <div class="product-info">
        <h1 id="product-title">
    <?php echo htmlspecialchars($product['product_name']); ?>
</h1>

<p class="category">
    <?php echo htmlspecialchars($product['category']); ?>
</p>

<p class="price">
    ₱<?php echo number_format($product['price'], 2); ?>
</p>

<p class="description">
    <?php
    echo !empty($product['description'])
        ? htmlspecialchars($product['description'])
        : 'No description available.';
    ?>
</p>
        <button type="button">Add to cart</button>
        <p class="note">Includes 2-Year ASUS Global Warranty &amp; Free ROG Gaming Backpack.</p>
      </div>
    </section>

    <!-- Other products section -->
    <section class="products" id="products" aria-labelledby="products-title">
      <h2 id="products-title">Other products</h2>
      <div class="product-grid">
        <article class="card">
          <div class="card-image">
            <img src="https://benson.ph/cdn/shop/files/laptop-alienware-ac16250-gallery-1.webp?v=1772515719&amp;width=1920" alt="Dell Alienware 16 Aurora" />
          </div>
          <h3>Dell Alienware 16 Aurora</h3>
          <p>Intel i9, RTX 4080, Legend 3 cooling design &amp; customizable RGB.</p>
          <strong>₱145,000.00</strong>
        </article>

        <article class="card">
          <div class="card-image">
            <img src="https://laptopmedia.com/wp-content/uploads/2025/01/PREDATOR-HELIOS-18-AI_PH18-73-01-e1736510435924.jpg" alt="Acer Predator Helios" />
          </div>
          <h3>Acer Predator Helios</h3>
          <p>14th Gen Intel i7, RTX 4070, and 5th Gen AeroBlade 3D cooling fans.</p>
          <strong>₱112,500.00</strong>
        </article>

        <article class="card">
          <div class="card-image">
            <img src="https://rog.asus.com/media/1735958275836.jpg" alt="ASUS ROG RTX 5090 Astral" />
          </div>
          <h3>ASUS ROG RTX 5090 Astral</h3>
          <p>Flagship graphics card featuring 32GB GDDR7 memory and quad-fan axial design.</p>
          <strong>₱449,995.00</strong>
        </article>

        <article class="card">
          <div class="card-image">
            <img src="https://geekawhat.com/wp-content/uploads/2025/03/FI_ASUS-TUF-Gaming-RX-9070-XT-OC.jpg" alt="Radeon RX 9070 TUF" />
          </div>
          <h3>Radeon RX 9070 TUF</h3>
          <p>Military-grade components, dual-ball bearings, and optimized 1440p/4K performance.</p>
          <strong>₱48,500.00</strong>
        </article>

        <article class="card">
          <div class="card-image">
            <img src="https://gameone.ph/media/catalog/product/mpiowebpcache/d378a0f20f83637cdb1392af8dc032a2/l/o/logitech_g304_x_superlight_lightspeed-mixpic1.webp" alt="Logitech G304 mouse" />
          </div>
          <h3>Logitech G304</h3>
          <p>LIGHTSPEED wireless mouse with HERO 12K sensor and 250-hour battery life.</p>
          <strong>₱1,490.00</strong>
        </article>

        <article class="card">
          <div class="card-image">
            <img src="https://s24.q4cdn.com/131595232/files/doc_multimedia/Low_Resolution_JPG-G435_MC_Key_Visual_03_16x9_Poster.jpg" alt="Logitech G435 headset" />
          </div>
          <h3>Logitech G435</h3>
          <p>Ultra-lightweight wireless gaming headset with low-latency Bluetooth and beamforming mics.</p>
          <strong>₱2,990.00</strong>
        </article>
      </div>
    </section>
  </main>

  <footer class="site-footer">
    <div class="footer-brand">
      <h2>PCGarage</h2>
      <p>Premium custom gaming rigs and<br />PC components built for<br />enthusiasts.</p>
      <div class="socials">● ◉ ▣ ◎</div>
    </div>
    <div>
      <h3>Shop</h3>
      <a href="custom.php">Custom Gaming PCs</a>
      <a href="category.php">Pre-Built Workstations</a>
      <a href="category.php">PC Components</a>
    </div>
    <div>
      <h3>Support</h3>
      <a href="about.php">Help Center &amp; FAQs</a>
      <a href="deliver.php">Track Your Order</a>
      <a href="about.php">Shipping &amp; Returns</a>
    </div>
    <div>
      <h3>Company</h3>
      <a href="about.php">About PCGarage</a>
      <a href="about.php">Careers</a>
      <a href="about.php">Contact Us</a>
    </div>
  </footer>
  <div class="legal">© 2026 PCGarage. All rights reserved. | Privacy Policy | Terms of Service | Cookie Preferences</div>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const addToCartBtn = document.querySelector('.product-info button');
      if (addToCartBtn) {
        addToCartBtn.addEventListener('click', () => {
          const title = document.getElementById('product-title')?.textContent.trim();

const price = <?php echo $product['price']; ?>;
          CartManager.addItem(title, price, 1);
          alert(`"${title}" added to your shopping cart!`);
        });
      }
    });
  </script>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PC-Garage | Custom PC Builder</title>
  <link rel="stylesheet" href="stylesheet.css">
  <script src="custom-pc-set.js" defer></script>
</head>
<body>

  <header class="site-header">
    <a class="brand" href="home.php" aria-label="PC-GARAGE home">
      <span class="brand-mark">&#9670;</span>
      <span class="brand-name">PC-GARAGE</span>
      <small>SINCE 2026</small>
    </a>
    <nav class="main-nav" aria-label="Primary navigation">
      <details class="menu-dropdown">
        <summary class="menu-toggle">Menu</summary>
        <div class="menu-links">
          <a href="home.php">Home</a>
          <a href="product.php">Products</a>
          <a href="shop-filtered.php">Filtered Shop</a>
          <a href="custom.php" class="active">Custom Build</a>
          <a href="about.php">About Us</a>
          <a href="buy-sell.php">Buy and Sell</a>
          <a href="cart.php">Cart</a>
          <a href="profile.php">Profile</a>
        </div>
      </details>
    </nav>
  </header>

  <main class="builder-container">

    <header class="builder-intro">
      <h1>CUSTOM PC BUILDER</h1>
      <p class="builder-description">
        Use the Preset to get recommended builds, or submit a Custom Request and our experts will configure your perfect rig.
      </p>

      <div class="mode-toggle" role="group" aria-label="Builder mode">
        <button type="button" class="mode-btn active" data-mode="preset">PRESET</button>
        <button type="button" class="mode-btn" data-mode="custom">CUSTOM REQUEST</button>
      </div>
    </header>

    <!-- DEFAULT VIEW: PRESET BUILDER -->
    <div id="preset-view">
      <section class="builder-section" aria-labelledby="budget-title">
        <h2 id="budget-title" class="section-label">YOUR BUDGET</h2>
        <div class="budget-slider-wrapper">
          <input type="range" id="budget-range" min="20000" max="250000" step="5000" value="175000" aria-label="Your Budget Slider">
          <div class="slider-marks">
            <span>₱20,000</span>
            <span id="current-budget-display">₱175,000</span>
            <span>₱250,000</span>
          </div>
        </div>
      </section>

      <section class="builder-section" aria-labelledby="use-title">
        <h2 id="use-title" class="section-label">PRIMARY USE</h2>
        <div class="use-options-grid">
          <button type="button" class="use-card active" data-use="fps">FPS COMPETITIVE</button>
          <button type="button" class="use-card" data-use="4k">4K GAMING</button>
          <button type="button" class="use-card" data-use="streaming">STREAMING AND GAMING</button>
          <button type="button" class="use-card" data-use="editing">3D / VIDEO EDITING</button>
        </div>
      </section>

      <section class="preset-cards-grid" aria-label="Preset Build Options">

        <article class="preset-card" data-tier="entry">
          <span class="preset-category">BUDGET BUILD</span>
          <h3 class="preset-name">ENTRY</h3>
          <p class="preset-price">₱35,000 - ₱48,000</p>
          <ul class="preset-specs">
            <li>AMD Ryzen 5 5600</li>
            <li>NVIDIA RTX 4060 8GB</li>
            <li>16GB DDR4 3600MHz</li>
            <li>B550M WiFi Motherboard</li>
            <li>1TB NVMe Gen4 SSD</li>
            <li>650W 80+ Bronze PSU</li>
          </ul>
          <div class="preset-footer">
            <span class="target-label">TARGET PERFORMANCE</span>
            <strong class="target-value">1080p / 240+ FPS (Esports)</strong>
          </div>
        </article>

        <article class="preset-card active" data-tier="mid">
          <span class="preset-category">MID - RANGE BUILD</span>
          <h3 class="preset-name">MID</h3>
          <p class="preset-price">₱80,000 - ₱105,000</p>
          <ul class="preset-specs">
            <li>AMD Ryzen 7 7800X3D</li>
            <li>NVIDIA RTX 4070 Super 12GB</li>
            <li>32GB DDR5 6000MHz CL30</li>
            <li>B650M WiFi Motherboard</li>
            <li>2TB Gen4 NVMe SSD</li>
            <li>750W 80+ Gold PSU</li>
          </ul>
          <div class="preset-footer">
            <span class="target-label">TARGET PERFORMANCE</span>
            <strong class="target-value">1080p &amp; 1440p / 360+ FPS</strong>
          </div>
        </article>

        <article class="preset-card" data-tier="extremist">
          <span class="preset-category">HIGH-END BUILD</span>
          <h3 class="preset-name">HIGH-END</h3>
          <p class="preset-price">₱165,000 - ₱210,000</p>
          <ul class="preset-specs">
            <li>AMD Ryzen 7 9800X3D</li>
            <li>NVIDIA RTX 5080 16GB</li>
            <li>64GB DDR5 6400MHz CL30</li>
            <li>X870E Gaming Motherboard</li>
            <li>2TB Gen5 NVMe SSD</li>
            <li>1000W 80+ Platinum PSU</li>
          </ul>
          <div class="preset-footer">
            <span class="target-label">TARGET PERFORMANCE</span>
            <strong class="target-value">1440p / 480+ FPS Ultra-Low Latency</strong>
          </div>
        </article>

      </section>

      <section class="configured-components-section" aria-label="Configured Components">
        <div class="component-list" id="component-list">
          <!-- Populated dynamically by custom-pc-set.js -->
        </div>

        <div class="builder-summary">
          <p><strong>ESTIMATED TOTAL:</strong> <span id="build-total-display">₱0.00</span></p>
        </div>

        <div class="builder-actions">
          <a href="checkout.php" class="payment-button">PAYMENT</a>
        </div>
      </section>
    </div>

    <!-- CUSTOM REQUEST VIEW -->
    <section id="custom-request-view" class="trade-form-card" hidden>
      <h2 class="section-label">SUBMIT A CUSTOM BUILD REQUEST</h2>

      <form id="custom-request-form">
        <div class="form-group">
          <label for="req-name">Full Name</label>
          <input type="text" id="req-name" placeholder="Juan Dela Cruz" required>
        </div>

        <div class="form-group">
          <label for="req-contact">Email or Mobile Number</label>
          <input type="text" id="req-contact" placeholder="juan@gmail.com / 09171234567" required>
        </div>

        <div class="form-group">
          <label for="req-budget">Target Budget (₱)</label>
          <input type="number" id="req-budget" min="20000" step="1000" value="100000" required>
        </div>

        <div class="form-group">
          <label for="req-use">Primary Use</label>
          <select id="req-use" required>
            <option value="FPS Competitive">FPS Competitive</option>
            <option value="4K Gaming">4K Gaming</option>
            <option value="Streaming and Gaming">Streaming and Gaming</option>
            <option value="3D / Video Editing">3D / Video Editing</option>
          </select>
        </div>

        <div class="form-group">
          <label for="req-specs">Preferred Parts, Case Style, or Special Notes</label>
          <textarea id="req-specs" rows="4" placeholder="e.g. White ATX case, Ryzen 7 7800X3D, RTX 4070 Super, 32GB RAM..." required></textarea>
        </div>

        <div class="builder-actions">
          <button type="submit" class="payment-button">SUBMIT REQUEST</button>
        </div>
      </form>
    </section>

  </main>

  <footer class="site-footer">
    <div class="footer-about">
      <a href="home.php" class="footer-brand">PCGarage</a>
      <p>Premium custom gaming rigs and<br>PC components built for<br>enthusiasts.</p>
      <div class="social-links" aria-label="Social platforms">
        <span aria-label="Facebook">f</span>
        <span aria-label="LinkedIn">in</span>
        <span aria-label="YouTube">▶</span>
        <span aria-label="Instagram">◎</span>
      </div>
    </div>
    <div class="footer-right">
      <div class="footer-links">
        <section>
          <h2>Shop</h2>
          <a href="custom.php">Custom Gaming PCs</a>
          <a href="product.php">Pre-Built Workstations</a>
          <a href="shop-filtered.php">PC Components</a>
        </section>
        <section>
          <h2>Support</h2>
          <a href="about.php">Help Center &amp; FAQs</a>
          <a href="deliver.php">Track Your Order</a>
          <a href="about.php">Shipping &amp; Returns</a>
        </section>
        <section>
          <h2>Company</h2>
          <a href="about.php">About PCGarage</a>
          <a href="about.php">Careers</a>
          <a href="about.php">Contact Us</a>
        </section>
      </div>
      <p class="copyright">© 2026 PCGarage. All rights reserved. <span>| Privacy Policy | Terms of Service | Cookie Preferences</span></p>
    </div>
  </footer>

  <script>
    document.addEventListener("DOMContentLoaded", () => {
      const presetView = document.getElementById("preset-view");
      const customView = document.getElementById("custom-request-view");

      document.querySelectorAll(".mode-btn").forEach((btn) => {
        btn.addEventListener("click", () => {
          const isCustom = btn.dataset.mode === "custom";
          presetView.hidden = isCustom;
          customView.hidden = !isCustom;
        });
      });

      document.getElementById("custom-request-form").addEventListener("submit", (e) => {
        e.preventDefault();
        const name = document.getElementById("req-name").value.trim();
        alert(`Thank you, ${name}! Your custom PC request has been submitted to our builders.`);
        e.target.reset();
        document.querySelector('.mode-btn[data-mode="preset"]').click();
      });
    });
  </script>

</body>
</html>
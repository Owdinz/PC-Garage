<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}

if (empty($_SESSION['cart_csrf'])) {
  $_SESSION['cart_csrf'] = bin2hex(random_bytes(32));
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PC-Garage | Checkout</title>
  <link rel="stylesheet" href="stylesheet.css">
  <script src="auth-guard.js" defer></script>
  <script src="cart-manager.js" defer></script>
  <script src="checkout.js" defer></script>

  <meta name="cart-csrf-token" content="<?= htmlspecialchars(
    $_SESSION['cart_csrf'],
    ENT_QUOTES,
    'UTF-8'
  ) ?>">

</head>

<body>
  <header class="site-header">
    <a class="brand" href="home.php" aria-label="PC-Garage home">
      <span class="brand-mark">&#9670;</span>
      <span class="brand-name">PC-GARAGE</span>
      <small>SINCE 2020</small>
    </a>
    <nav class="main-nav" aria-label="Primary navigation">
      <details class="menu-dropdown">
        <summary class="menu-toggle">Menu</summary>
        <div class="menu-links">
          <a href="home.php">Home</a>
          <a href="category.php">Products</a>
          <a href="shop-filtered.php">Filtered Shop</a>
          <a href="custom.php">Custom Build</a>
          <a href="about.php">About Us</a>
          <a href="buy-sell.php">Buy and Sell</a>
          <a href="cart.php">Cart</a>
          <a href="profile.php">Profile</a>
        </div>
      </details>
    </nav>
  </header>

  <main class="checkout">
    <h1>Checkout</h1>
    <form id="checkout-form">
      <div class="checkout-columns">
        <div class="customer-column">
          <section aria-labelledby="customer-title">
            <h2 id="customer-title">Customer Information</h2>
            <div class="input-panel">
              <label for="full-name">FULL NAME</label>
              <input id="full-name" name="fullName" autocomplete="name" placeholder="John Doe" required>

              <label for="contact">CONTACT NUMBER</label>
              <input id="contact" name="contact" type="tel" autocomplete="tel" placeholder="09145125123" required>

              <label for="email">EMAIL ADDRESS</label>
              <input id="email" name="email" type="email" autocomplete="email" placeholder="john.doe@gmail.com"
                required>
            </div>
          </section>

          <section aria-labelledby="shipping-title">
            <h2 id="shipping-title">Shipping Info</h2>
            <div class="input-panel">
              <label for="address">COMPLETE ADDRESS</label>
              <input id="address" name="address" autocomplete="street-address"
                placeholder="14 BATUMBAKAL ST. MANILA PHILIPPINES" required>

              <label for="postal">POSTAL CODE</label>
              <input id="postal" name="postalCode" autocomplete="postal-code" inputmode="numeric" placeholder="1000"
                required>

              <label class="notes-label" for="notes">DELIVERY NOTES</label>
              <textarea id="notes" name="notes" rows="2" placeholder="e.g. Leave with guard if unavailable"></textarea>
            </div>
          </section>
        </div>

        <div class="methods-column">
          <fieldset>
            <legend>Shipping Method</legend>
            <div class="option-grid shipping-options">
              <label class="option"><input type="radio" name="shippingMethod" value="standard" checked><span>Standard
                  Delivery</span></label>
              <label class="option"><input type="radio" name="shippingMethod" value="express"><span>Express
                  Delivery</span></label>
              <label class="option" style="opacity:0.5;">
                <input type="radio" name="shippingMethod" value="pickup" disabled>
                <span>Store Pickup <small>Coming Soon</small></span>
              </label>
            </div>
          </fieldset>


          <fieldset>
            <legend>Payment Method</legend>

            <div class="option-grid payment-options">
              <label class="option">
                <input type="radio" name="paymentMethod" value="cod" checked required>
                <span>
                  Cash On Delivery
                  <small>Pay when your order is delivered</small>
                </span>
              </label>

              <label class="option" style="opacity:0.5;">
                <input type="radio" name="paymentMethod" value="gcash" disabled>
                <span>GCash / Maya <small>Coming Soon</small></span>
              </label>

              <label class="option" style="opacity:0.5;">
                <input type="radio" name="paymentMethod" value="card" disabled>
                <span>Debit / Credit Card <small>Coming Soon</small></span>
              </label>

              <label class="option" style="opacity:0.5;">
                <input type="radio" name="paymentMethod" value="wallet" disabled>
                <span>PCGarage Wallet <small>Coming Soon</small></span>
              </label>
            </div>
          </fieldset>

        </div>
      </div>

      <section class="orders" aria-labelledby="orders-title">
        <h2 id="orders-title">YOUR ORDERS</h2>

        <div class="order-list">
          <p class="empty-cart">
            Loading your shopping cart...
          </p>
        </div>

        <div
          style="border: 1px solid #666; border-radius: 6px; padding: 16px; margin-top: 16px; font-family: monospace;">
          <div style="display: flex; justify-content: space-between; font-weight: bold; font-size: 16px;">
            <span>TOTAL ORDER AMOUNT:</span>
            <span id="checkout-total-display">₱0.00</span>
          </div>
        </div>

        <div class="checkout-actions">
          <a class="action-button" href="cart.php">← GO BACK</a>
          <button class="action-button" type="submit" id="secure-order">SECURE ORDER</button>

          <button type="button" id="check-previous-order" class="action-button" hidden style="margin-top: 12px;">
            CHECK PREVIOUS ORDER
          </button>

        </div>
        <p id="order-status" role="status" hidden></p>
      </section>
    </form>
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
      <p class="copyright">© 2026 PCGarage. All rights reserved. <span>| Privacy Policy | Terms of Service | Cookie
          Preferences</span></p>
    </div>
  </footer>
  <!-- Toast Undo Notification -->
  <div id="toast-notification" class="toast-notification" role="status" aria-live="polite" hidden>
    <span id="toast-message">Item removed from cart.</span>
    <button type="button" id="undo-button" class="undo-button">UNDO</button>
  </div>
</body>

</html>
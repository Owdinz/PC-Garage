<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PC-Garage | Profile Settings</title>
    <script src="auth-guard.js" defer></script>
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>

    <header class="site-header">
        <a href="home.php" class="brand" aria-label="PC-Garage home">
            <span class="brand-mark">&#9670;</span>
            <span class="brand-name">PC-GARAGE</span>
            <small>SINCE 2020</small>
        </a>
        <nav class="main-nav" aria-label="Primary navigation">
            <details class="menu-dropdown">
                <summary class="menu-toggle">Menu</summary>
                <div class="menu-links">
                    <a href="home.php">Home</a>
                    <a href="product.php">Products</a>
                    <a href="shop-filtered.php">Filtered Shop</a>
                    <a href="custom.php">Custom Build</a>
                    <a href="about.php">About Us</a>
                    <a href="buy-sell.php">Buy and Sell</a>
                    <a href="cart.php">Cart</a>
                    <a href="profile.php" class="active">Profile</a>
                </div>
            </details>
        </nav>
    </header>

    <main style="max-width: 650px; margin: 36px auto;">
        <div style="margin-bottom: 20px;">
            <a href="profile.php" style="font-size: 12px; color: #000; text-decoration: none; font-weight: bold;">← Back to Profile</a>
        </div>

        <h1 style="font-size: 28px; margin-bottom: 24px;">Profile Settings</h1>

        <form action="profile.php" method="GET" style="display: flex; flex-direction: column; gap: 20px;">
            
            <div style="border: 1px solid var(--line); border-radius: 6px; padding: 20px;">
                <h2 style="font-size: 16px; margin-bottom: 16px;">Personal Details</h2>
                
                <p style="margin-bottom: 4px;"><label for="full-name" style="font-weight: bold; font-size: 12px;">Full Name</label></p>
                <input type="text" id="full-name" value="Tech Gamer" required style="width: 100%; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 14px; font-size: 12px;">

                <p style="margin-bottom: 4px;"><label for="email" style="font-weight: bold; font-size: 12px;">Email Address</label></p>
                <input type="email" id="email" value="gamer@pcgarage.com" required style="width: 100%; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 14px; font-size: 12px;">

                <p style="margin-bottom: 4px;"><label for="phone" style="font-weight: bold; font-size: 12px;">Phone Number</label></p>
                <input type="text" id="phone" value="+639234567891" required style="width: 100%; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 12px;">
            </div>

            <div style="border: 1px solid var(--line); border-radius: 6px; padding: 20px;">
                <h2 style="font-size: 16px; margin-bottom: 16px;">Default Shipping Address</h2>
                
                <p style="margin-bottom: 4px;"><label for="address" style="font-weight: bold; font-size: 12px;">Street Address</label></p>
                <input type="text" id="address" value="123 Gaming Ave, Tech City" required style="width: 100%; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 14px; font-size: 12px;">

                <p style="margin-bottom: 4px;"><label for="city" style="font-weight: bold; font-size: 12px;">City / Province</label></p>
                <input type="text" id="city" value="Metro Manila" required style="width: 100%; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 12px;">
            </div>

            <div style="border: 1px solid var(--line); border-radius: 6px; padding: 20px;">
                <h2 style="font-size: 16px; margin-bottom: 16px;">Change Password</h2>
                
                <p style="margin-bottom: 4px;"><label for="curr-pass" style="font-weight: bold; font-size: 12px;">Current Password</label></p>
                <input type="password" id="curr-pass" placeholder="••••••••" style="width: 100%; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 14px; font-size: 12px;">

                <p style="margin-bottom: 4px;"><label for="new-pass" style="font-weight: bold; font-size: 12px;">New Password</label></p>
                <input type="password" id="new-pass" placeholder="••••••••" style="width: 100%; padding: 8px 12px; border: 1px solid #ccc; border-radius: 4px; font-size: 12px;">
            </div>

            <div style="display: flex; gap: 12px; align-items: center;">
                <button type="submit" style="width: auto; padding: 10px 24px; background: #000; color: #fff; border-radius: 4px; border: none; font-size: 12px; font-weight: bold; cursor: pointer;">Save Changes</button>
                <a href="profile.php" style="font-size: 12px; color: #666; text-decoration: none;">Cancel</a>
            </div>

        </form>
    </main>

    <footer class="site-footer">
        <div>
            <h2>PCGarage</h2>
            <p>Premium custom gaming rigs and PC components built for enthusiasts.</p>
        </div>
        <div>
            <h3>Shop</h3>
            <a href="custom.php">Custom Gaming PCs</a>
            <a href="product.php">Pre-Built Workstations</a>
            <a href="shop-filtered.php">PC Components</a>
        </div>
        <div>
            <h3>Support</h3>
            <a href="about.php">Help Center & FAQs</a>
            <a href="deliver.php">Track Your Order</a>
            <a href="about.php">Shipping & Returns</a>
        </div>
        <div>
            <h3>Company</h3>
            <a href="about.php">About PCGarage</a>
            <a href="about.php">Careers</a>
            <a href="about.php">Contact Us</a>
        </div>
    </footer>
    <div class="legal">&copy; 2026 PCGarage. All rights reserved. | Privacy Policy | Terms of Service | Cookie Preferences</div>

</body>
</html>
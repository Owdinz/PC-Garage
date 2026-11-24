<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PC-Garage | Login & Sign-up</title>
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
                    <a class="profile-button" href="login.php">Login / Sign-up</a>
                </div>
            </details>
        </nav>
    </header>

    <div
        style="position: relative; width: 100%; min-height: 520px; background: url('https://images.unsplash.com/photo-1550745165-9bc0b252726f?w=1200') center/cover no-repeat; display: flex; justify-content: center; align-items: center; padding: 40px 20px;">
        <div style="position: absolute; inset: 0; background: rgba(255, 255, 255, 0.4);"></div>

        <div
            style="position: relative; z-index: 1; background: #ffffff; border: 2px solid #000000; border-radius: 16px; padding: 35px 30px; width: 100%; max-width: 380px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.15);">

            <h1
                style="font-family: 'Georgia', serif; font-size: 22px; font-weight: bold; margin-bottom: 4px; color: #000;">
                WELCOME TO PC GARAGE</h1>
            <p style="font-family: 'Cursive', sans-serif; font-size: 13px; color: #333; margin-bottom: 25px;">Build.
                Buy. Sell. Upgrade</p>

            <!-- Sign In Form -->
            <form id="auth-signin" novalidate action="login-handler.php" method="POST">
                <input type="email" name="email" placeholder="EMAIL..." required
                    style="width: 100%; padding: 10px 14px; margin-bottom: 12px; border: 1px solid #888; border-radius: 4px; font-size: 11px; background: #fff;">
                <input type="password" name="password" placeholder="PASSWORD..." required
                    style="width: 100%; padding: 10px 14px; margin-bottom: 20px; border: 1px solid #888; border-radius: 4px; font-size: 11px; background: #fff;">

                <p id="login-message" role="alert" aria-live="polite"
                    style="display:none; color:#b91c1c; margin:12px 0;">
                </p>

                <div style="display: flex; justify-content: center; gap: 10px;">
                    <button type="submit"
                        style="width: auto; padding: 5px 15px; border: 1px solid #000; background: #fff; color: #000; font-size: 10px; border-radius: 4px; cursor: pointer;">SIGN
                        IN</button>
                    <button type="button" onclick="showForm('signup')"
                        style="width: auto; padding: 5px 15px; border: 1px solid #000; background: #fff; color: #000; font-size: 10px; border-radius: 4px; cursor: pointer;">SIGN
                        UP</button>
                </div>

            </form>


            <!-- Sign Up Form -->
            <form id="auth-signup" style="display: none;">

                <!-- Full Name -->
                <input type="text" name="full_name" placeholder="FULL NAME..." autocomplete="name" required
                    style="width: 100%; padding: 10px 14px; margin-bottom: 12px; border: 1px solid #888; border-radius: 4px; font-size: 11px; background: #fff;">

                <!-- Email -->
                <input type="email" name="email" placeholder="EMAIL..." autocomplete="email" required
                    style="width: 100%; padding: 10px 14px; margin-bottom: 12px; border: 1px solid #888; border-radius: 4px; font-size: 11px; background: #fff;">

                <!-- Password -->
                <input type="password" name="password" placeholder="PASSWORD..." autocomplete="new-password"
                    minlength="8" required
                    style="width: 100%; padding: 10px 14px; margin-bottom: 12px; border: 1px solid #888; border-radius: 4px; font-size: 11px; background: #fff;">

                <!-- Registration Message -->
                <p id="signup-message" role="status" aria-live="polite"
                    style="display: none; font-size: 11px; margin-bottom: 15px;">
                </p>

                <!-- Buttons -->
                <div style="display: flex; justify-content: center; gap: 10px;">

                    <button type="button" onclick="showForm('signin')"
                        style="width: auto; padding: 5px 15px; border: 1px solid #000; background: #fff; color: #000; font-size: 10px; border-radius: 4px; cursor: pointer;">
                        RETURN
                    </button>

                    <button type="submit"
                        style="width: auto; padding: 5px 15px; border: 1px solid #000; background: #fff; color: #000; font-size: 10px; border-radius: 4px; cursor: pointer;">
                        SIGN UP
                    </button>

                </div>
            </form>


        </div>
    </div>

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
    <div class="legal">&copy; 2026 PCGarage. All rights reserved. | Privacy Policy | Terms of Service | Cookie
        Preferences</div>

    <script>
        function showForm(formType) {
            const signinForm = document.getElementById('auth-signin');
            const signupForm = document.getElementById('auth-signup');
            if (formType === 'signup') {
                signinForm.style.display = 'none';
                signupForm.style.display = 'block';
            } else {
                signinForm.style.display = 'block';
                signupForm.style.display = 'none';
            }
        }

        //Sign In Script

        const loginForm = document.getElementById("auth-signin");
        const loginMessage = document.getElementById("login-message");

        loginForm.addEventListener("submit", async function (event) {
            event.preventDefault();

            loginMessage.style.display = "none";
            loginMessage.textContent = "";

            const emailInput = loginForm.querySelector('[name="email"]');
            const passwordInput = loginForm.querySelector('[name="password"]');

            const email = emailInput.value.trim();
            const password = passwordInput.value;

            // Check for empty fields.
            if (!email && !password) {
                loginMessage.textContent =
                    "Please enter your email and password first.";
                loginMessage.style.display = "block";
                emailInput.focus();
                return;
            }

            if (!email) {
                loginMessage.textContent =
                    "Please enter your email address first.";
                loginMessage.style.display = "block";
                emailInput.focus();
                return;
            }

            if (!password.trim()) {
                loginMessage.textContent =
                    "Please enter your password first.";
                loginMessage.style.display = "block";
                passwordInput.focus();
                return;
            }

            // Validate email format.
            if (!emailInput.validity.valid) {
                loginMessage.textContent =
                    "Please enter a valid email address.";
                loginMessage.style.display = "block";
                emailInput.focus();
                return;
            }

            // Submit credentials to PHP.
            try {
                const response = await fetch("login-handler.php", {
                    method: "POST",
                    body: new FormData(loginForm),
                    credentials: "same-origin"
                });

                if (!response.ok) {
                    throw new Error("Server request failed");
                }

                const data = await response.json();

                if (data.success) {
                    window.location.href = "profile.php";
                    return;
                }

                loginMessage.textContent = data.message;
                loginMessage.style.display = "block";

            } catch (error) {
                loginMessage.textContent =
                    "Unable to connect to the server. Please try again.";
                loginMessage.style.display = "block";
                console.error("Login error:", error);
            }
        });


        //Sign Up Script
        const signupForm = document.getElementById("auth-signup");
        const signupMessage = document.getElementById("signup-message");

        signupForm.addEventListener("submit", async function (event) {
            event.preventDefault();

            signupMessage.style.display = "none";
            signupMessage.textContent = "";

            try {
                const response = await fetch("register.php", {
                    method: "POST",
                    body: new FormData(signupForm)
                });

                const data = await response.json();

                if (data.success) {
                    signupMessage.style.color = "green";
                    signupMessage.textContent = data.message;
                    signupMessage.style.display = "block";

                    signupForm.reset();
                } else {
                    signupMessage.style.color = "#b91c1c";
                    signupMessage.textContent = data.message;
                    signupMessage.style.display = "block";
                }

            } catch (error) {
                signupMessage.style.color = "#b91c1c";
                signupMessage.textContent =
                    "Unable to connect to the server. Please try again.";
                signupMessage.style.display = "block";

                console.error("Registration error:", error);
            }
        });

    </script>
</body>

</html>
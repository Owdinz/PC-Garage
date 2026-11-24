<?php
session_start();

header("Cache-Control: no-store");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "db.php";

$userId = (int) $_SESSION["user_id"];
$error = "";
$success = "";

// Create a CSRF token for secure form submission.
if (empty($_SESSION["profile_csrf"])) {
    $_SESSION["profile_csrf"] = bin2hex(random_bytes(32));
}

function loadUser($conn, $userId)
{
    $stmt = $conn->prepare(
        "SELECT user_id, full_name, email, phone,
                address, city, password_hash
         FROM users WHERE user_id = ? LIMIT 1"
    );
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $user;
}

function e($value)
{
    return htmlspecialchars(
        (string) ($value ?? ""),
        ENT_QUOTES,
        "UTF-8"
    );
}

$user = loadUser($conn, $userId);

if (!$user) {
    $_SESSION = [];
    session_destroy();
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (
        !hash_equals(
            $_SESSION["profile_csrf"],
            $_POST["csrf_token"] ?? ""
        )
    ) {
        http_response_code(403);
        $error = "Invalid request. Please refresh and try again.";
    } else {

        $fullName = trim($_POST["full_name"] ?? "");
        $email = trim($_POST["email"] ?? "");
        $phone = trim($_POST["phone"] ?? "");
        $address = trim($_POST["address"] ?? "");
        $city = trim($_POST["city"] ?? "");
        $currentPassword = $_POST["current_password"] ?? "";
        $newPassword = $_POST["new_password"] ?? "";

        if ($fullName === "" || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid name and email address.";
        } elseif (
            strlen($fullName) > 100 ||
            strlen($email) > 100 ||
            strlen($phone) > 20 ||
            strlen($city) > 100
        ) {
            $error = "One or more fields exceed the allowed length.";
        } elseif (
            $phone !== "" &&
            !preg_match('/^\+?[0-9 ()-]{7,20}$/', $phone)
        ) {
            $error = "Please enter a valid phone number.";

        } elseif (
            $currentPassword !== "" &&
            $newPassword === ""
        ) {
            $error = "Please enter your new password.";

        } elseif (
            $newPassword !== "" &&
            $currentPassword === ""
        ) {
            $error = "Please enter your current password.";

        } elseif (
            $newPassword !== "" &&
            !password_verify($currentPassword, $user["password_hash"])
        ) {
            $error = "Your current password is incorrect.";

        } elseif (
            $newPassword !== "" &&
            strlen($newPassword) < 8
        ) {
            $error = "New password must contain at least 8 characters.";

        } elseif (
            $newPassword !== "" &&
            password_verify($newPassword, $user["password_hash"])
        ) {
            $error = "Your new password must be different from your current password.";

        } else {


            // Check if the email belongs to another account.
            $stmt = $conn->prepare(
                "SELECT user_id FROM users
                 WHERE email = ? AND user_id <> ?
                 LIMIT 1"
            );
            $stmt->bind_param("si", $email, $userId);
            $stmt->execute();
            $duplicate = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if ($duplicate) {
                $error = "This email is already registered.";
            } else {
                try {
                    if ($newPassword !== "") {
                        $hash = password_hash(
                            $newPassword,
                            PASSWORD_DEFAULT
                        );

                        $stmt = $conn->prepare(
                            "UPDATE users
                             SET full_name = ?, email = ?,
                                 phone = ?, address = ?,
                                 city = ?, password_hash = ?
                             WHERE user_id = ?"
                        );
                        $stmt->bind_param(
                            "ssssssi",
                            $fullName,
                            $email,
                            $phone,
                            $address,
                            $city,
                            $hash,
                            $userId
                        );
                    } else {
                        $stmt = $conn->prepare(
                            "UPDATE users
                             SET full_name = ?, email = ?,
                                 phone = ?, address = ?, city = ?
                             WHERE user_id = ?"
                        );
                        $stmt->bind_param(
                            "sssssi",
                            $fullName,
                            $email,
                            $phone,
                            $address,
                            $city,
                            $userId
                        );
                    }

                    $stmt->execute();
                    $stmt->close();

                    $_SESSION["full_name"] = $fullName;
                    $user = loadUser($conn, $userId);
                    $success = "Profile updated successfully!";

                    // Rotate token after successful save.
                    $_SESSION["profile_csrf"] =
                        bin2hex(random_bytes(32));

                } catch (mysqli_sql_exception $ex) {
                    error_log("Profile update failed: " . $ex->getMessage());
                    $error = "Unable to save changes. Please try again.";
                }
            }
        }
    }
}

$conn->close();
?>

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
            <a href="profile.php" style="font-size: 12px; color: #000; text-decoration: none; font-weight: bold;">← Back
                to Profile</a>
        </div>

        <h1 style="font-size: 28px; margin-bottom: 24px;">Profile Settings</h1>


        <?php if ($error !== ""): ?>
            <p role="alert" style="color:#b91c1c; background:#fee2e2; padding:12px; margin-bottom:16px; border-radius:4px;">
                <?= e($error) ?>
            </p>
        <?php endif; ?>

        <?php if ($success !== ""): ?>
            <p role="status"
                style="color:#166534; background:#dcfce7; padding:12px; margin-bottom:16px; border-radius:4px;">
                <?= e($success) ?>
            </p>
        <?php endif; ?>

        <form action="profile-settings.php" method="POST" style="display:flex; flex-direction:column; gap:20px;">

            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['profile_csrf']) ?>">

            <div style="border:1px solid var(--line); border-radius:6px; padding:20px;">
                <h2 style="font-size:16px; margin-bottom:16px;">Personal Details</h2>

                <label for="full-name">Full Name</label>
                <input type="text" id="full-name" name="full_name" value="<?= e($user['full_name']) ?>" maxlength="100"
                    required style="width:100%; padding:8px 12px; margin:6px 0 14px;">

                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?= e($user['email']) ?>" maxlength="100" required
                    style="width:100%; padding:8px 12px; margin:6px 0 14px;">

                <label for="phone">Phone Number</label>
                <input type="tel" id="phone" name="phone" value="<?= e($user['phone']) ?>" maxlength="20"
                    style="width:100%; padding:8px 12px; margin:6px 0;">
            </div>

            <div style="border:1px solid var(--line); border-radius:6px; padding:20px;">
                <h2 style="font-size:16px; margin-bottom:16px;">Default Shipping Address</h2>

                <label for="address">Street Address</label>
                <input type="text" id="address" name="address" value="<?= e($user['address']) ?>"
                    style="width:100%; padding:8px 12px; margin:6px 0 14px;">

                <label for="city">City / Province</label>
                <input type="text" id="city" name="city" value="<?= e($user['city']) ?>" maxlength="100"
                    style="width:100%; padding:8px 12px; margin:6px 0;">
            </div>

            <div style="border:1px solid var(--line); border-radius:6px; padding:20px;">
                <h2 style="font-size:16px; margin-bottom:16px;">Change Password</h2>

                <p style="font-size:11px; color:#666; margin-bottom:14px;">
                    Leave both password fields empty if you don't want to change your password.
                </p>

                <label for="curr-pass">Current Password</label>
                <input type="password" id="curr-pass" name="current_password" autocomplete="current-password"
                    style="width:100%; padding:8px 12px; margin:6px 0 14px;">

                <label for="new-pass">New Password</label>
                <input type="password" id="new-pass" name="new_password" autocomplete="new-password" minlength="8"
                    style="width:100%; padding:8px 12px; margin:6px 0;">
            </div>

            <div style="display:flex; gap:12px; align-items:center;">
                <button type="submit"
                    style="padding:10px 24px; background:#000; color:#fff; border-radius:4px; border:none; font-size:12px; font-weight:bold; cursor:pointer;">
                    Save Changes
                </button>

                <a href="profile.php" style="font-size:12px; color:#666; text-decoration:none;">
                    Cancel
                </a>
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
    <div class="legal">&copy; 2026 PCGarage. All rights reserved. | Privacy Policy | Terms of Service | Cookie
        Preferences</div>

</body>

</html>
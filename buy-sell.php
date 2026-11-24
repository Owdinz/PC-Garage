
<?php
session_start();

header("Cache-Control: no-store");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "db.php";

$userId = (int) $_SESSION["user_id"];

// Create CSRF token for trade submissions.
if (empty($_SESSION["trade_csrf"])) {
    $_SESSION["trade_csrf"] = bin2hex(random_bytes(32));
}

// Escape database values before displaying them.
function tradeEscape($value)
{
    return htmlspecialchars(
        (string) ($value ?? ""),
        ENT_QUOTES,
        "UTF-8"
    );
}

// Retrieve trade requests belonging to the logged-in seller.
$stmt = $conn->prepare(
    "SELECT trade_id, category, part_name,
            item_condition, expected_price,
            status, submitted_at
     FROM trade_requests
     WHERE user_id = ?
     ORDER BY submitted_at DESC, trade_id DESC"
);

$stmt->bind_param("i", $userId);
$stmt->execute();

$tradeRequests = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>PC-Garage | Buy, Sell & Trade-In</title>

    <link rel="stylesheet" href="stylesheet.css">
    <script src="auth-guard.js" defer></script>

    <style>
        /* Additional styling for dynamic trade requests */

        .trade-request-link {
            display: inline-block;
            margin-top: 10px;
            padding: 7px 12px;
            border: 1px solid #111;
            border-radius: 4px;
            color: #111;
            background: #fff;
            font-size: 11px;
            font-weight: bold;
            text-decoration: none;
            transition: 0.2s;
        }

        .trade-request-link:hover {
            background: #111;
            color: #fff;
        }

        .trade-empty {
            padding: 25px 15px;
            text-align: center;
            color: #666;
            font-size: 12px;
            border: 1px dashed #ccc;
            border-radius: 6px;
        }

        .trade-empty strong {
            display: block;
            color: #111;
            font-size: 14px;
            margin-bottom: 8px;
        }

        .trade-info strong {
            display: block;
            overflow-wrap: anywhere;
        }

        .trade-info small {
            display: block;
            margin-top: 6px;
            line-height: 1.6;
        }

        .trade-price {
            font-weight: bold;
            white-space: nowrap;
        }

        .trade-status-card {
            min-width: 0;
        }

        .trade-list {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .trade-item {
            overflow-wrap: anywhere;
        }

        .trade-status-label {
            display: inline-block;
            padding: 4px 9px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            background: #eee;
            color: #111;
        }

        .trade-status-label.approved {
            background: #dcfce7;
            color: #166534;
        }

        .trade-status-label.rejected {
            background: #fee2e2;
            color: #991b1b;
        }

        @media (max-width: 600px) {
            .trade-item {
                flex-wrap: wrap;
                gap: 12px;
            }

            .trade-price {
                white-space: normal;
            }
        }
    </style>
</head>

<body class="trade-in-page">

    <!-- HEADER -->
    <header class="site-header">

        <a class="brand" href="home.php" aria-label="PC-Garage home">
            <span class="brand-mark">&#9670;</span>
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
                    <a href="custom.php">Custom Build</a>
                    <a href="about.php">About Us</a>
                    <a href="buy-sell.php" class="active">Buy &amp; Sell</a>
                    <a href="cart.php">Cart</a>
                    <a href="profile.php">Profile</a>
                </div>

            </details>

        </nav>

    </header>

    <!-- MAIN CONTENT -->
    <main class="trade-container">

        <!-- HERO -->
        <section class="trade-hero">

            <h1>TRADE-IN &amp; SELL YOUR HARDWARE</h1>

            <p>
                Upgrade your gaming rig by trading in your old GPUs,
                CPUs, and RAM for store credit or instant payouts.
            </p>

        </section>

        <div class="trade-grid">

            <!-- HARDWARE SUBMISSION FORM -->
            <section class="trade-form-card">

                <h2>Submit Hardware Details</h2>

                <form
                    id="tradeForm"
                    action="trade-submit.php"
                    method="POST"
                >

                    <!-- CSRF PROTECTION -->
                    <input
                        type="hidden"
                        name="csrf_token"
                        value="<?= tradeEscape($_SESSION['trade_csrf']) ?>"
                    >

                    <!-- COMPONENT CATEGORY -->
                    <div class="form-group">

                        <label for="category">
                            Component Category
                        </label>

                        <select
                            id="category"
                            name="category"
                            required
                        >
                            <option value="">
                                Select Category...
                            </option>

                            <option value="gpu">
                                Graphics Card (GPU)
                            </option>

                            <option value="cpu">
                                Processor (CPU)
                            </option>

                            <option value="ram">
                                RAM / Memory
                            </option>

                            <option value="storage">
                                SSD / Storage
                            </option>

                            <option value="motherboard">
                                Motherboard
                            </option>

                            <option value="power">
                                Power Supply (PSU)
                            </option>

                        </select>

                    </div>

                    <!-- HARDWARE MODEL -->
                    <div class="form-group">

                        <label for="partName">
                            Exact Model Name
                        </label>

                        <input
                            type="text"
                            id="partName"
                            name="part_name"
                            maxlength="255"
                            placeholder="e.g., NVIDIA RTX 3070 8GB Founders Edition"
                            required
                        >

                    </div>

                    <!-- HARDWARE CONDITION -->
                    <div class="form-group">

                        <label for="condition">
                            Hardware Condition
                        </label>

                        <select
                            id="condition"
                            name="item_condition"
                            required
                        >

                            <option value="like-new">
                                Like New (With Original Packaging)
                            </option>

                            <option value="good">
                                Good (Lightly Used, Fully Functional)
                            </option>

                            <option value="fair">
                                Fair (Visible Wear, Fully Functional)
                            </option>

                        </select>

                    </div>

                    <!-- ESTIMATED VALUE -->
                    <div class="form-group">

                        <label for="expectedPrice">
                            Estimated Value (₱)
                        </label>

                        <input
                            type="number"
                            id="expectedPrice"
                            name="expected_price"
                            min="500"
                            max="9999999999.99"
                            step="0.01"
                            placeholder="8500"
                            required
                        >

                    </div>

                    <!-- ADDITIONAL INFORMATION -->
                    <div class="form-group">

                        <label for="notes">
                            Additional Information
                        </label>

                        <textarea
                            id="notes"
                            name="notes"
                            rows="3"
                            maxlength="5000"
                            placeholder="Provide hardware details, working status, benchmark scores, or other relevant information..."
                        ></textarea>

                    </div>

                    <!-- SUBMIT BUTTON -->
                    <button
                        type="submit"
                        class="home-button"
                    >
                        Submit for Appraisal
                    </button>

                </form>

            </section>

            <!-- ACTIVE TRADE REQUESTS -->
            <section class="trade-status-card">

                <h2>Your Active Trade Requests</h2>

                <div class="trade-list" id="tradeList">

                    <?php if (empty($tradeRequests)): ?>

                        <!-- EMPTY STATE -->
                        <div class="trade-empty">

                            <strong>No Trade Requests Yet</strong>

                            <p>
                                Your submitted hardware will appear here
                                after you send an appraisal request.
                            </p>

                        </div>

                    <?php else: ?>

                        <?php foreach ($tradeRequests as $trade): ?>

                            <?php
                                $status = $trade["status"] ?: "Under Review";

                                $statusClass = "pending";

                                if (in_array(
                                    strtolower($status),
                                    ["accepted", "approved"],
                                    true
                                )) {
                                    $statusClass = "approved";
                                } elseif (in_array(
                                    strtolower($status),
                                    ["rejected", "declined"],
                                    true
                                )) {
                                    $statusClass = "rejected";
                                }

                                $submittedDate = date(
                                    "M d, Y",
                                    strtotime($trade["submitted_at"])
                                );
                            ?>

                            <div class="trade-item">

                                <div class="trade-info">

                                    <strong>
                                        <?= tradeEscape($trade["part_name"]) ?>
                                    </strong>

                                    <small>
                                        Submitted on
                                        <?= tradeEscape($submittedDate) ?>
                                        &bull; Status:
                                        <span class="trade-status-label <?= $statusClass ?>">
                                            <?= tradeEscape($status) ?>
                                        </span>
                                    </small>

                                    <a
                                        class="trade-request-link"
                                        href="trade-preview.php?id=<?= (int) $trade['trade_id'] ?>"
                                    >
                                        View Details
                                    </a>

                                </div>

                                <div class="trade-price">
                                    ₱<?= number_format(
                                        (float) $trade["expected_price"],
                                        2
                                    ) ?>
                                </div>

                            </div>

                        <?php endforeach; ?>

                    <?php endif; ?>

                </div>

            </section>

        </div>

    </main>

    <!-- FOOTER -->
    <footer class="site-footer">

        <div class="footer-brand">

            <h2>PCGarage</h2>

            <p>
                Premium custom gaming rigs and PC components
                built for enthusiasts.
            </p>

        </div>

        <div>

            <h3>Shop</h3>

            <a href="custom.php">Custom Gaming PCs</a>
            <a href="product.php">Pre-Built Workstations</a>
            <a href="shop-filtered.php">PC Components</a>

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

    <div class="legal">
        &copy; 2026 PCGarage. All rights reserved.
        | Privacy Policy | Terms of Service | Cookie Preferences
    </div>

</body>
</html>


<?php
session_start();

header("Cache-Control: no-store");

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

require_once "db.php";

$userId = (int) $_SESSION["user_id"];

// Validate the trade request ID.
$tradeId = filter_input(
    INPUT_GET,
    "id",
    FILTER_VALIDATE_INT,
    ["options" => ["min_range" => 1]]
);

if (!$tradeId) {
    http_response_code(400);
    exit("Invalid trade request ID.");
}

// Retrieve only a trade request belonging to the logged-in user.
$stmt = $conn->prepare(
    "SELECT trade_id, category, part_name,
            item_condition, expected_price,
            notes, status, submitted_at
     FROM trade_requests
     WHERE trade_id = ? AND user_id = ?
     LIMIT 1"
);

$stmt->bind_param("ii", $tradeId, $userId);
$stmt->execute();

$trade = $stmt->get_result()->fetch_assoc();

$stmt->close();
$conn->close();

if (!$trade) {
    http_response_code(404);
    exit("Trade request not found.");
}

// Escape database content to prevent HTML injection.
function tradeText($value) {
    return htmlspecialchars(
        (string) ($value ?? ""),
        ENT_QUOTES,
        "UTF-8"
    );
}

// Human-readable category labels.
$categories = [
    "gpu" => "Graphics Card (GPU)",
    "cpu" => "Processor (CPU)",
    "ram" => "RAM / Memory",
    "storage" => "SSD / Storage",
    "motherboard" => "Motherboard",
    "power" => "Power Supply (PSU)"
];

// Human-readable condition labels.
$conditions = [
    "like-new" => "Like New (With Original Packaging)",
    "good" => "Good (Lightly Used, Fully Functional)",
    "fair" => "Fair (Visible Wear, Fully Functional)"
];

$categoryLabel = $categories[$trade["category"]]
    ?? $trade["category"];

$conditionLabel = $conditions[$trade["item_condition"]]
    ?? $trade["item_condition"];

$status = $trade["status"] ?: "Under Review";

$submitted = date(
    "M d, Y - h:i A",
    strtotime($trade["submitted_at"])
);

$tradeNumber = "TR-" . str_pad(
    (string) $trade["trade_id"],
    4,
    "0",
    STR_PAD_LEFT
);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>PC-Garage | Trade Request Preview</title>

    <link rel="stylesheet" href="stylesheet.css">

    <style>
        .preview-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 24px 60px;
        }

        .preview-back {
            display: inline-block;
            margin-bottom: 22px;
            color: #111;
            font-size: 13px;
            font-weight: bold;
            text-decoration: none;
        }

        .preview-back:hover {
            text-decoration: underline;
        }

        .preview-title {
            font-size: clamp(26px, 4vw, 38px);
            font-weight: bold;
            margin-bottom: 10px;
        }

        .preview-subtitle {
            color: #555;
            font-size: 14px;
            margin-bottom: 35px;
        }

        .preview-grid {
            display: grid;
            grid-template-columns: 0.9fr 1.6fr;
            gap: 25px;
            align-items: stretch;
        }

        .preview-image-card,
        .preview-details,
        .preview-notice {
            background: #fff;
            border: 1px solid #999;
            border-radius: 12px;
            overflow: hidden;
        }

        .preview-image-card {
            padding: 16px;
            min-height: 380px;
        }

        .preview-image-placeholder {
            height: 100%;
            min-height: 345px;
            border-radius: 8px;
            background: #f4f4f4;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            gap: 22px;
            color: #333;
            text-align: center;
        }

        .preview-image-placeholder svg {
            width: 105px;
            height: 105px;
        }

        .preview-details-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            padding: 20px 25px;
            background: #fafafa;
            border-bottom: 1px solid #ccc;
        }

        .preview-request-id {
            font-size: 15px;
            font-weight: bold;
        }

        .preview-status {
            display: inline-block;
            background: #eee;
            color: #111;
            border-radius: 24px;
            padding: 8px 18px;
            font-size: 12px;
            font-weight: bold;
        }

        .preview-details-body {
            padding: 26px;
        }

        .preview-product-name {
            font-size: 28px;
            margin-bottom: 8px;
            overflow-wrap: anywhere;
        }

        .preview-meta {
            color: #555;
            font-size: 13px;
            line-height: 1.6;
        }

        .preview-divider {
            border: 0;
            border-top: 1px solid #ddd;
            margin: 24px 0;
        }

        .preview-data-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            padding: 10px 0;
            font-size: 13px;
        }

        .preview-data-row span {
            overflow-wrap: anywhere;
        }

        .preview-notes-title {
            font-size: 16px;
            margin-bottom: 12px;
        }

        .preview-notes {
            font-size: 13px;
            line-height: 1.7;
            white-space: pre-wrap;
            overflow-wrap: anywhere;
        }

        .preview-notice {
            margin-top: 30px;
            padding: 24px 28px;
            background: #fafafa;
        }

        .preview-notice h3 {
            font-size: 16px;
            margin-bottom: 8px;
        }

        .preview-notice p {
            font-size: 13px;
            color: #444;
            line-height: 1.7;
        }

        .preview-actions {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            margin-top: 30px;
        }

        .preview-button {
            display: inline-block;
            padding: 14px 25px;
            border: 1px solid #111;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: bold;
            background: #fff;
            color: #111;
            transition: 0.2s;
        }

        .preview-button:hover {
            background: #eee;
        }

        .preview-button.primary {
            background: #000;
            color: #fff;
        }

        .preview-button.primary:hover {
            background: #333;
        }

        @media (max-width: 750px) {
            .preview-grid {
                grid-template-columns: 1fr;
            }

            .preview-image-card {
                min-height: 260px;
            }

            .preview-image-placeholder {
                min-height: 250px;
            }

            .preview-data-row {
                grid-template-columns: 1fr;
                gap: 5px;
            }

            .preview-details-body {
                padding: 20px;
            }

            .preview-actions {
                flex-direction: column;
            }

            .preview-button {
                text-align: center;
            }
        }
    </style>
</head>

<body class="trade-in-page">

    <!-- HEADER -->
    <header class="site-header">

        <a class="brand"
           href="home.php"
           aria-label="PC-Garage home">

            <span class="brand-mark">&#9670;</span>
            <span class="brand-name">PC-GARAGE</span>
            <small>PC COMPONENTS</small>
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
                    <a href="buy-sell.php" class="active">
                        Buy &amp; Sell
                    </a>
                    <a href="cart.php">Cart</a>
                    <a href="profile.php">Profile</a>
                </div>

            </details>
        </nav>

    </header>

    <!-- MAIN -->
    <main class="preview-container">

        <a href="buy-sell.php" class="preview-back">
            ← Back to Buy &amp; Sell
        </a>

        <h1 class="preview-title">
            TRADE REQUEST PREVIEW
        </h1>

        <p class="preview-subtitle">
            Review the details of your submitted hardware for appraisal.
        </p>

        <div class="preview-grid">

            <!-- HARDWARE IMAGE PLACEHOLDER -->
            <section class="preview-image-card">

                <div class="preview-image-placeholder">

                    <svg
                        viewBox="0 0 100 100"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="3"
                        aria-hidden="true"
                    >
                        <rect
                            x="28"
                            y="28"
                            width="44"
                            height="44"
                            rx="3"
                        />

                        <path d="
                            M36 15V28
                            M50 15V28
                            M64 15V28
                            M36 72V85
                            M50 72V85
                            M64 72V85
                            M15 36H28
                            M15 50H28
                            M15 64H28
                            M72 36H85
                            M72 50H85
                            M72 64H85
                        "/>
                    </svg>

                    <span>No Image Provided</span>

                </div>

            </section>

            <!-- TRADE DETAILS -->
            <section class="preview-details">

                <div class="preview-details-header">

                    <span class="preview-request-id">
                        Trade Request #
                        <?= tradeText($tradeNumber) ?>
                    </span>

                    <span class="preview-status">
                        <?= tradeText($status) ?>
                    </span>

                </div>

                <div class="preview-details-body">

                    <h2 class="preview-product-name">
                        <?= tradeText($trade["part_name"]) ?>
                    </h2>

                    <p class="preview-meta">
                        <?= tradeText($categoryLabel) ?>
                        &bull;
                        <?= tradeText($conditionLabel) ?>
                    </p>

                    <hr class="preview-divider">

                    <div class="preview-data-row">

                        <strong>Estimated Value</strong>

                        <span>
                            ₱<?= number_format(
                                (float) $trade["expected_price"],
                                2
                            ) ?>
                        </span>

                    </div>

                    <div class="preview-data-row">

                        <strong>Submitted On</strong>

                        <span>
                            <?= tradeText($submitted) ?>
                        </span>

                    </div>

                    <div class="preview-data-row">

                        <strong>Status</strong>

                        <span>
                            <?= tradeText($status) ?>
                        </span>

                    </div>

                    <hr class="preview-divider">

                    <h3 class="preview-notes-title">
                        Additional Information
                    </h3>

                    <p class="preview-notes"><?= tradeText(
                        $trade["notes"] ?: "No additional information provided."
                    ) ?></p>

                </div>

            </section>

        </div>

        <!-- APPRAISAL STATUS NOTICE -->
        <section class="preview-notice">

            <h3>Appraisal Status</h3>

            <p>
                Your hardware request has been submitted.
                Its current status is
                <strong><?= tradeText($status) ?></strong>.

                The final appraisal value may differ from
                your estimated value.
            </p>

        </section>

        <!-- NAVIGATION BUTTONS -->
        <div class="preview-actions">

            <a href="buy-sell.php" class="preview-button">
                ← Back to Buy &amp; Sell
            </a>

            <a
                href="buy-sell.php#tradeList"
                class="preview-button primary"
            >
                My Trade Requests
            </a>

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


<?php
session_start();

// Only authenticated users can submit hardware.
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// Accept POST submissions only.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: buy-sell.php");
    exit;
}

// Validate CSRF token.
if (
    empty($_SESSION["trade_csrf"]) ||
    !is_string($_POST["csrf_token"] ?? null) ||
    !hash_equals(
        $_SESSION["trade_csrf"],
        $_POST["csrf_token"]
    )
) {
    http_response_code(403);
    exit("Invalid submission. Please return to Buy & Sell and try again.");
}

require_once "db.php";

// Identify the logged-in seller.
$userId = (int) $_SESSION["user_id"];

// Retrieve form information.
$category = trim($_POST["category"] ?? "");
$partName = trim($_POST["part_name"] ?? "");
$condition = trim($_POST["item_condition"] ?? "");
$priceInput = $_POST["expected_price"] ?? "";
$notes = trim($_POST["notes"] ?? "");

// Allowed values must match the Buy & Sell form.
$validCategories = [
    "gpu",
    "cpu",
    "ram",
    "storage",
    "motherboard",
    "power"
];

$validConditions = [
    "like-new",
    "good",
    "fair"
];

// Validate hardware details.
if (
    !in_array($category, $validCategories, true) ||
    !in_array($condition, $validConditions, true) ||
    $partName === "" ||
    strlen($partName) > 255 ||
    strlen($notes) > 5000 ||
    !is_numeric($priceInput) ||
    !is_finite((float) $priceInput) ||
    (float) $priceInput < 500 ||
    (float) $priceInput > 9999999999.99
) {
    http_response_code(422);
    exit("Invalid hardware details. Please check your submission.");
}

$expectedPrice = round((float) $priceInput, 2);

try {
    // Save hardware appraisal request to MySQL.
    $stmt = $conn->prepare(
        "INSERT INTO trade_requests
         (
             user_id,
             category,
             part_name,
             item_condition,
             expected_price,
             notes,
             status
         )
         VALUES (?, ?, ?, ?, ?, ?, 'Under Review')"
    );

    $stmt->bind_param(
        "isssds",
        $userId,
        $category,
        $partName,
        $condition,
        $expectedPrice,
        $notes
    );

    $stmt->execute();

    // Retrieve the newly created trade request ID.
    $tradeId = $conn->insert_id;

    $stmt->close();
    $conn->close();

    // Rotate token after successful submission.
    $_SESSION["trade_csrf"] = bin2hex(random_bytes(32));

    // Redirect to the hardware preview page.
    header(
        "Location: trade-preview.php?id=" . $tradeId,
        true,
        303
    );
    exit;

} catch (mysqli_sql_exception $e) {

    error_log(
        "Trade submission error: " . $e->getMessage()
    );

    http_response_code(500);

    exit(
        "Unable to save your trade request. Please try again."
    );
}
?>

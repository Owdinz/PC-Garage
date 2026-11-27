<?php
declare(strict_types=1);

session_start();

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store');

function respond(int $status, array $data): void
{
    http_response_code($status);
    echo json_encode($data);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(405, [
        'success' => false,
        'message' => 'Method not allowed.'
    ]);
}

if (empty($_SESSION['user_id'])) {
    respond(401, [
        'success' => false,
        'message' => 'Please sign in before checkout.'
    ]);
}

$token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

if (
    !is_string($token) ||
    !isset($_SESSION['cart_csrf']) ||
    !hash_equals($_SESSION['cart_csrf'], $token)
) {
    respond(403, [
        'success' => false,
        'message' => 'Invalid security token.'
    ]);
}



$input = json_decode(file_get_contents('php://input'), true);

// First, validate the incoming JSON.
if (!is_array($input)) {
    respond(400, [
        'success' => false,
        'message' => 'Invalid checkout request.'
    ]);
}

// Assign the checkout request ID before validating it.
$requestId = $input['checkoutRequestId'] ?? '';

// Validate the request identifier.
if (
    !is_string($requestId) ||
    !preg_match('/^[a-f0-9]{64}$/', $requestId)
) {
    respond(422, [
        'success' => false,
        'message' => 'Invalid checkout request identifier.'
    ]);
}


$fullName = trim((string) ($input['fullName'] ?? ''));
$contact = trim((string) ($input['contact'] ?? ''));
$email = trim((string) ($input['email'] ?? ''));
$address = trim((string) ($input['address'] ?? ''));
$postal = trim((string) ($input['postalCode'] ?? ''));
$notes = trim((string) ($input['notes'] ?? ''));

$payment = (string) ($input['paymentMethod'] ?? '');
$shipping = (string) ($input['shippingMethod'] ?? '');

if (
    $fullName === '' || mb_strlen($fullName) > 150 ||
    $contact === '' || strlen($contact) > 30 ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) ||
    strlen($email) > 255 ||
    $address === '' || strlen($address) > 5000 ||
    $postal === '' || strlen($postal) > 20 ||
    strlen($notes) > 5000
) {
    respond(422, [
        'success' => false,
        'message' => 'Please provide valid customer and shipping information.'
    ]);
}

// Electronic payments require a real payment gateway.
if ($payment !== 'cod') {
    respond(422, [
        'success' => false,
        'message' => 'Only Cash on Delivery is currently available.'
    ]);
}

if (!in_array($shipping, ['standard', 'express'], true)) {
    respond(422, [
        'success' => false,
        'message' => 'Please select a supported delivery method.'
    ]);
}

require_once __DIR__ . '/db.php';

$userId = (int) $_SESSION['user_id'];

try {
    $conn->begin_transaction();

    // Serialize cart and checkout operations for this user.
    $stmt = $conn->prepare(
        'SELECT user_id FROM users WHERE user_id = ? FOR UPDATE'
    );
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$user) {
        throw new RuntimeException('User account not found.');
    }


// Check whether this checkout request was already completed.
// This check must happen before reading the shopping cart,
// because a successful checkout clears the purchased items.

$stmt = $conn->prepare(
    'SELECT order_id, total_amount
     FROM orders
     WHERE checkout_request_id = ?
       AND user_id = ?
     LIMIT 1'
);

$stmt->bind_param('si', $requestId, $userId);
$stmt->execute();

$existingOrder = $stmt->get_result()->fetch_assoc();
$stmt->close();

if ($existingOrder) {
    $conn->commit();

    respond(200, [
        'success' => true,
        'message' => 'Order already processed.',
        'order_id' => (int) $existingOrder['order_id'],
        'total' => (float) $existingOrder['total_amount'],
        'duplicate' => true,
        'redirect' => 'deliver.php'
    ]);
}


    $stmt = $conn->prepare(
        'SELECT cart_id FROM carts WHERE user_id = ? LIMIT 1'
    );


    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $cart = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$cart) {
        throw new RuntimeException('Your shopping cart is empty.');
    }

    $cartId = (int) $cart['cart_id'];

    $stmt = $conn->prepare(
        'SELECT ci.product_id, ci.quantity
         FROM cart_items ci
         WHERE ci.cart_id = ?
         ORDER BY ci.product_id'
    );
    $stmt->bind_param('i', $cartId);
    $stmt->execute();
    $cartItems = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    if (!$cartItems) {
        throw new RuntimeException('Your shopping cart is empty.');
    }

    $validatedItems = [];
    $totalCents = 0;

    // Lock products in a consistent order.
    foreach ($cartItems as $item) {
        $productId = (int) $item['product_id'];
        $quantity = (int) $item['quantity'];

        if ($quantity < 1 || $quantity > 99) {
            throw new RuntimeException('Invalid cart quantity.');
        }

        $stmt = $conn->prepare(
            'SELECT price, stock_quantity
             FROM products
             WHERE product_id = ?
             FOR UPDATE'
        );
        $stmt->bind_param('i', $productId);
        $stmt->execute();
        $product = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        if (!$product || $quantity > (int) $product['stock_quantity']) {
            throw new RuntimeException('A product is unavailable or out of stock.');
        }

        $priceCents = (int) round((float) $product['price'] * 100);

        if ($priceCents < 0) {
            throw new RuntimeException('Invalid product price.');
        }

        $totalCents += $priceCents * $quantity;

        $validatedItems[] = [
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $priceCents / 100
        ];
    }

    if ($totalCents <= 0 || $totalCents > 999999999999) {
        throw new RuntimeException('Invalid order total.');
    }

    // Current implementation has no delivery fees or discounts.
    $total = number_format($totalCents / 100, 2, '.', '');
    $status = 'Pending';


    $stmt = $conn->prepare(
        'INSERT INTO orders
     (user_id, total_amount, payment_method,
      shipping_method, order_status, checkout_request_id)
     VALUES (?, ?, ?, ?, ?, ?)'
    );

    $stmt->bind_param(
        'isssss',
        $userId,
        $total,
        $payment,
        $shipping,
        $status,
        $requestId
    );

    $stmt->execute();
    $orderId = (int) $conn->insert_id;
    $stmt->close();


    $stmt = $conn->prepare(
        'INSERT INTO order_shipping_details
         (order_id, full_name, contact_number, email,
          shipping_address, postal_code, delivery_notes)
         VALUES (?, ?, ?, ?, ?, ?, ?)'
    );
    $stmt->bind_param(
        'issssss',
        $orderId,
        $fullName,
        $contact,
        $email,
        $address,
        $postal,
        $notes
    );
    $stmt->execute();
    $stmt->close();

    foreach ($validatedItems as $item) {
        $productId = $item['product_id'];
        $quantity = $item['quantity'];
        $price = number_format($item['price'], 2, '.', '');

        $stmt = $conn->prepare(
            'INSERT INTO order_items
             (order_id, product_id, quantity, price)
             VALUES (?, ?, ?, ?)'
        );
        $stmt->bind_param(
            'iiis',
            $orderId,
            $productId,
            $quantity,
            $price
        );
        $stmt->execute();
        $stmt->close();

        $stmt = $conn->prepare(
            'UPDATE products
             SET stock_quantity = stock_quantity - ?
             WHERE product_id = ? AND stock_quantity >= ?'
        );
        $stmt->bind_param(
            'iii',
            $quantity,
            $productId,
            $quantity
        );
        $stmt->execute();

        if ($stmt->affected_rows !== 1) {
            throw new RuntimeException('Insufficient stock.');
        }
        $stmt->close();
    }

    $stmt = $conn->prepare(
        'DELETE FROM cart_items WHERE cart_id = ?'
    );
    $stmt->bind_param('i', $cartId);
    $stmt->execute();
    $stmt->close();

    $conn->commit();

    respond(200, [
        'success' => true,
        'message' => 'Order placed successfully.',
        'order_id' => $orderId,
        'total' => (float) $total,
        'redirect' => 'deliver.php'
    ]);

} catch (Throwable $error) {
    $conn->rollback();

    error_log('Checkout failed: ' . $error->getMessage());

    respond(400, [
        'success' => false,
        'message' => 'Checkout could not be completed. Please verify your cart and try again.'
    ]);
}

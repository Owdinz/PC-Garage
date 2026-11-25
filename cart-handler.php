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

if (!isset($_SESSION['user_id'])) {
    respond(401, [
        'success' => false,
        'message' => 'Please sign in to manage your cart.'
    ]);
}

require_once __DIR__ . '/db.php';

$userId = (int) $_SESSION['user_id'];
$method = $_SERVER['REQUEST_METHOD'];
$action = $_GET['action'] ?? '';

if ($method === 'GET' && $action === 'list') {
    $stmt = $conn->prepare(
        "SELECT p.product_id, p.product_name AS name,
                p.price, p.image_url AS image,
                ci.quantity, p.stock_quantity
         FROM carts c
         JOIN cart_items ci ON ci.cart_id = c.cart_id
         JOIN products p ON p.product_id = ci.product_id
         WHERE c.user_id = ?
         ORDER BY ci.cart_item_id"
    );

    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $items = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    $total = 0;

    foreach ($items as &$item) {
        $item['product_id'] = (int) $item['product_id'];
        $item['quantity'] = (int) $item['quantity'];
        $item['stock_quantity'] = (int) $item['stock_quantity'];
        $item['price'] = (float) $item['price'];

        $total += $item['price'] * $item['quantity'];
    }
    unset($item);

    respond(200, [
        'success' => true,
        'items' => $items,
        'total' => round($total, 2)
    ]);
}

if ($method !== 'POST') {
    respond(405, [
        'success' => false,
        'message' => 'Method not allowed.'
    ]);
}

// Protect state-changing requests against CSRF.
// A token is generated in the page's PHP session.
$csrf = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

if (
    !isset($_SESSION['cart_csrf']) ||
    !is_string($csrf) ||
    !hash_equals($_SESSION['cart_csrf'], $csrf)
) {
    respond(403, [
        'success' => false,
        'message' => 'Invalid security token.'
    ]);
}

$input = json_decode(file_get_contents('php://input'), true);

if (!is_array($input)) {
    respond(400, [
        'success' => false,
        'message' => 'Invalid JSON request.'
    ]);
}

$action = $input['action'] ?? '';
$productId = filter_var(
    $input['product_id'] ?? null,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1]]
);

$quantity = filter_var(
    $input['quantity'] ?? 1,
    FILTER_VALIDATE_INT,
    ['options' => ['min_range' => 1, 'max_range' => 99]]
);

if (
    !in_array($action, ['add', 'update', 'remove'], true) ||
    $productId === false ||
    $productId === null
) {
    respond(400, [
        'success' => false,
        'message' => 'Invalid cart action or product ID.'
    ]);
}

if ($action !== 'remove' && ($quantity === false || $quantity === null)) {
    respond(400, [
        'success' => false,
        'message' => 'Quantity must be between 1 and 99.'
    ]);
}

try {
    $conn->begin_transaction();

    // Serialize cart operations for this customer.
    $stmt = $conn->prepare(
        'SELECT user_id FROM users WHERE user_id = ? FOR UPDATE'
    );
    $stmt->bind_param('i', $userId);
    $stmt->execute();

    if (!$stmt->get_result()->fetch_assoc()) {
        throw new RuntimeException('User account not found.');
    }
    $stmt->close();

    $stmt = $conn->prepare(
        'SELECT cart_id FROM carts WHERE user_id = ? LIMIT 1'
    );
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $cart = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$cart && $action === 'add') {
        $stmt = $conn->prepare(
            'INSERT INTO carts (user_id) VALUES (?)'
        );
        $stmt->bind_param('i', $userId);
        $stmt->execute();
        $cartId = (int) $conn->insert_id;
        $stmt->close();
    } elseif ($cart) {
        $cartId = (int) $cart['cart_id'];
    } else {
        throw new RuntimeException('Cart item not found.');
    }

    $stmt = $conn->prepare(
        'SELECT stock_quantity FROM products WHERE product_id = ? FOR UPDATE'
    );
    $stmt->bind_param('i', $productId);
    $stmt->execute();
    $product = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$product) {
        throw new RuntimeException('Product not found.');
    }

    $stmt = $conn->prepare(
        'SELECT cart_item_id, quantity
         FROM cart_items
         WHERE cart_id = ? AND product_id = ?
         LIMIT 1'
    );
    $stmt->bind_param('ii', $cartId, $productId);
    $stmt->execute();
    $existing = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ($action === 'add' || $action === 'update') {
        if ($action === 'update' && !$existing) {
            throw new RuntimeException('Cart item not found.');
        }

        $newQuantity = $action === 'add' && $existing
            ? (int) $existing['quantity'] + $quantity
            : $quantity;

        if ($newQuantity > 99 ||
            $newQuantity > (int) $product['stock_quantity']) {
            throw new RuntimeException(
                'Requested quantity exceeds available stock or cart limit.'
            );
        }

        if ($existing) {
            $stmt = $conn->prepare(
                'UPDATE cart_items SET quantity = ?
                 WHERE cart_item_id = ?'
            );
            $itemId = (int) $existing['cart_item_id'];
            $stmt->bind_param('ii', $newQuantity, $itemId);
        } else {
            $stmt = $conn->prepare(
                'INSERT INTO cart_items
                 (cart_id, product_id, quantity)
                 VALUES (?, ?, ?)'
            );
            $stmt->bind_param('iii', $cartId, $productId, $newQuantity);
        }

        $stmt->execute();
        $stmt->close();
    } elseif ($existing) {
        $stmt = $conn->prepare(
            'DELETE FROM cart_items WHERE cart_item_id = ?'
        );
        $itemId = (int) $existing['cart_item_id'];
        $stmt->bind_param('i', $itemId);
        $stmt->execute();
        $stmt->close();
    }

    $conn->commit();

    respond(200, [
        'success' => true,
        'message' => 'Cart updated successfully.'
    ]);
} catch (Throwable $error) {
    $conn->rollback();

    error_log('Cart operation failed: ' . $error->getMessage());

    respond(400, [
        'success' => false,
        'message' => 'Unable to update cart. Check the product and available quantity.'
    ]);
}

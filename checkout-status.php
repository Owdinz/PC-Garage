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

// Only allow read-only GET requests.
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    respond(405, [
        'success' => false,
        'message' => 'Method not allowed.'
    ]);
}

// Require an authenticated customer.
if (empty($_SESSION['user_id'])) {
    respond(401, [
        'success' => false,
        'message' => 'Please sign in to check your order.'
    ]);
}

// Validate the checkout request identifier.
$requestId = $_GET['request_id'] ?? '';

if (
    !is_string($requestId) ||
    !preg_match('/^[a-f0-9]{64}$/', $requestId)
) {
    respond(422, [
        'success' => false,
        'message' => 'Invalid checkout request identifier.'
    ]);
}

require_once __DIR__ . '/db.php';

$userId = (int) $_SESSION['user_id'];

try {
    $stmt = $conn->prepare(
        'SELECT order_id, total_amount, order_status
         FROM orders
         WHERE checkout_request_id = ?
           AND user_id = ?
         LIMIT 1'
    );

    $stmt->bind_param('si', $requestId, $userId);
    $stmt->execute();

    $order = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$order) {
        respond(200, [
            'success' => true,
            'found' => false,
            'message' => 'No completed checkout found for this request.'
        ]);
    }

    respond(200, [
        'success' => true,
        'found' => true,
        'order_id' => (int) $order['order_id'],
        'total' => (float) $order['total_amount'],
        'order_status' => $order['order_status']
    ]);

} catch (Throwable $error) {
    error_log(
        'Checkout status lookup failed: ' .
        $error->getMessage()
    );

    respond(500, [
        'success' => false,
        'message' => 'Unable to check checkout status.'
    ]);
}

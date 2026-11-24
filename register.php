
<?php
header("Content-Type: application/json; charset=utf-8");

require_once "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
    exit;
}

$full_name = trim($_POST["full_name"] ?? "");
$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";

// Validate required fields.
if ($full_name === "" || $email === "" || $password === "") {
    echo json_encode([
        "success" => false,
        "message" => "Please complete all required fields."
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "success" => false,
        "message" => "Please enter a valid email address."
    ]);
    exit;
}

if (strlen($password) < 8) {
    echo json_encode([
        "success" => false,
        "message" => "Password must contain at least 8 characters."
    ]);
    exit;
}

// Check whether the email is already registered.
$check = $conn->prepare(
    "SELECT user_id FROM users WHERE email = ? LIMIT 1"
);
$check->bind_param("s", $email);
$check->execute();
$existing = $check->get_result()->fetch_assoc();
$check->close();

if ($existing) {
    echo json_encode([
        "success" => false,
        "message" => "This email is already registered. Please sign in."
    ]);
    exit;
}

// Securely hash the password.
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Save account to MySQL.
$stmt = $conn->prepare(
    "INSERT INTO users (full_name, email, password_hash)
     VALUES (?, ?, ?)"
);

$stmt->bind_param(
    "sss",
    $full_name,
    $email,
    $hashed_password
);

try {
    $stmt->execute();

    echo json_encode([
        "success" => true,
        "message" => "Registration successful! You can now sign in."
    ]);
} catch (mysqli_sql_exception $e) {
    error_log("Registration error: " . $e->getMessage());

    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Unable to create your account. Please try again."
    ]);
}

$stmt->close();
$conn->close();
?>

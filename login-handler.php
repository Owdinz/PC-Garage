
<?php
session_start();

header("Content-Type: application/json; charset=utf-8");

require_once "db.php";

// Only accept POST requests.
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode([
        "success" => false,
        "message" => "Invalid request method."
    ]);
    exit;
}

$email = trim($_POST["email"] ?? "");
$password = $_POST["password"] ?? "";

// Check empty fields.
if ($email === "" || $password === "") {
    echo json_encode([
        "success" => false,
        "message" => "Please enter your email and password."
    ]);
    exit;
}

// Find registered user.
$sql = "SELECT user_id, full_name, password_hash
        FROM users
        WHERE email = ?
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();

$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Account does not exist.
if (!$user) {
    echo json_encode([
        "success" => false,
        "code" => "ACCOUNT_NOT_FOUND",
        "message" => "You need to sign up first before logging in. Please click SIGN UP to create an account."
    ]);
    exit;
}

// Incorrect password.
if (!password_verify($password, $user["password_hash"])) {
    echo json_encode([
        "success" => false,
        "code" => "INVALID_PASSWORD",
        "message" => "Incorrect password. Please try again."
    ]);
    exit;
}

// Correct credentials.
session_regenerate_id(true);

$_SESSION["user_id"] = $user["user_id"];
$_SESSION["full_name"] = $user["full_name"];

echo json_encode([
    "success" => true,
    "message" => "Login successful.",
    "redirect" => "profile.php"
]);

$stmt->close();
$conn->close();
?>

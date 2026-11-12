<?php

include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $full_name = $_POST["full_name"];
    $email = $_POST["email"];
    $password = $_POST["password"];

    $hashed_password = password_hash(
        $password,
        PASSWORD_DEFAULT
    );

    $sql = "INSERT INTO users
            (full_name, email, password_hash)
            VALUES (?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "sss",
        $full_name,
        $email,
        $hashed_password
    );

    if ($stmt->execute()) {

        echo "Registration Successful";

    } else {

        echo "Error: " . $stmt->error;

    }

    $stmt->close();
}

$conn->close();

?>
<?php

session_start();

include "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE email = ?";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param("s", $email);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (
            password_verify(
                $password,
                $user["password_hash"]
            )
        ) {

            $_SESSION["user_id"] =
                $user["user_id"];

            $_SESSION["full_name"] =
                $user["full_name"];

            echo "Login Successful";

        } else {

            echo "Invalid Password";

        }

    } else {

        echo "User Not Found";

    }
}

?>
<?php
session_start();
require 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $nis = $_POST['nis'];

    // Check user in database
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND nis = ?");
    $stmt->bind_param("ss", $username, $nis);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Authentication successful
        $user = $result->fetch_assoc();
        $_SESSION['token'] = bin2hex(random_bytes(32)); // Generate a random token
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['nama'] = $user['nama'];
        $_SESSION['nis'] = $user['nis'];
        header("Location: dashboard.php");
        exit();
    } else {
        // Authentication failed
        $error = "Invalid username or NIS";
        header("Location: index.php?error=" . urlencode($error));
        exit();
    }
}
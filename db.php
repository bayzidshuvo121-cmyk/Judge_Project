<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if running on localhost or live server
if (getenv('DB_HOST')) {
    // LIVE SERVER (Render + Aiven)
    $host = getenv('DB_HOST');
    $user = getenv('DB_USER');
    $pass = getenv('DB_PASS');
    $db   = getenv('DB_NAME');
    $port = (int) getenv('DB_PORT');

    $conn = mysqli_init();
    mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);
    mysqli_real_connect($conn, $host, $user, $pass, $db, $port, NULL, MYSQLI_CLIENT_SSL);
} else {
    // LOCALHOST (XAMPP)
    $conn = mysqli_connect("localhost", "root", "", "judging_db");
}

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
?>

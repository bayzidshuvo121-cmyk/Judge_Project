
// $conn = mysqli_connect("localhost", "root", "", "judging_db");

// if (!$conn) { 
//     die("Database Connection Failed: " . mysqli_connect_error()); 
// }

// // ONLY start the session if it hasn't been started yet
// if (session_status() === PHP_SESSION_NONE) {
//     session_start();
// }



<?php
$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$pass = getenv('DB_PASS');
$db   = getenv('DB_NAME');
$port = getenv('DB_PORT') ?: 3306;

$conn = mysqli_init();
mysqli_ssl_set($conn, NULL, NULL, NULL, NULL, NULL);
mysqli_real_connect($conn, $host, $user, $pass, $db, (int)$port, NULL, MYSQLI_CLIENT_SSL);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>?>
<?php
include 'db.php';
$result = $conn->query("SELECT 1");
if ($result) {
    echo "DB alive - " . date("Y-m-d H:i:s");
} else {
    echo "DB error: " . $conn->error;
}
?>

<?php 
include 'db.php'; 
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // trim() removes accidental spaces from the input
    $user = mysqli_real_escape_string($conn, trim($_POST['username']));
    $pass = trim($_POST['password']);
    
    $res = $conn->query("SELECT * FROM users WHERE username='$user'");
    
    if ($row = $res->fetch_assoc()) {
        // This compares the typed '123456' to the hash in DB
        if ($pass == $row['password']) {
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            
            if ($row['role'] == 'admin') {
                header("Location: admin.php");
            } else {
                header("Location: judge_form.php");
            }
            exit();
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 h-screen flex items-center justify-center">
    <form method="POST" class="bg-white p-8 rounded shadow-lg w-96 border border-gray-200">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">User Login</h2>
        <?php if($error): ?> <p class="text-red-500 mb-4 text-center"><?php echo $error; ?></p> <?php endif; ?>
        <input type="text" name="username" placeholder="Username" class="w-full p-3 border rounded mb-4 focus:ring-2 focus:ring-blue-500 outline-none" required>
        <input type="password" name="password" placeholder="Password" class="w-full p-3 border rounded mb-6 focus:ring-2 focus:ring-blue-500 outline-none" required>
        <button type="submit" class="w-full bg-blue-600 text-white p-3 rounded font-bold hover:bg-blue-700 transition">Login</button>
    </form>
</body>
</html>
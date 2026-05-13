<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>CS Project Judging Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-900 h-screen flex flex-col items-center justify-center text-white font-sans">
    
    <div class="text-center mb-12 animate-bounce">
        <i class="fas fa-graduation-cap text-6xl text-blue-500 mb-4"></i>
        <h1 class="text-5xl font-extrabold tracking-tight">Judging Portal</h1>
        <p class="text-slate-400 mt-2 text-lg">Computer Science Department Project Evaluation</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 w-full max-w-4xl px-6">
        <!-- Judge Card -->
        <a href="login.php" class="group bg-slate-800 p-8 rounded-2xl border border-slate-700 hover:border-blue-500 hover:bg-slate-750 transition-all shadow-2xl">
            <div class="flex items-center justify-between mb-4">
                <div class="p-4 bg-blue-600 rounded-lg"><i class="fas fa-user-tie text-2xl"></i></div>
                <i class="fas fa-arrow-right text-slate-600 group-hover:text-blue-500 transition"></i>
            </div>
            <h2 class="text-2xl font-bold">Judge Login</h2>
            <p class="text-slate-400 mt-2">Access your evaluation dashboard and score student projects.</p>
        </a>

        <!-- Admin Card -->
        <a href="login.php" class="group bg-slate-800 p-8 rounded-2xl border border-slate-700 hover:border-red-500 hover:bg-slate-750 transition-all shadow-2xl">
            <div class="flex items-center justify-between mb-4">
                <div class="p-4 bg-red-600 rounded-lg"><i class="fas fa-user-shield text-2xl"></i></div>
                <i class="fas fa-arrow-right text-slate-600 group-hover:text-red-500 transition"></i>
            </div>
            <h2 class="text-2xl font-bold">Admin Login</h2>
            <p class="text-slate-400 mt-2">View real-time analytics, averages, and group performance.</p>
        </a>
    </div>

    <footer class="absolute bottom-8 text-slate-500 text-sm">
        &copy; <?php echo date("Y"); ?> CS Department Grading System
    </footer>
</body>
</html>
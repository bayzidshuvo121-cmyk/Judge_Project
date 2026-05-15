<?php 
include 'db.php'; 
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') header("Location: login.php");

// Fetch all unique groups that have been graded
// FIXED ✅
$group_list = $conn->query("SELECT group_number, MIN(project_title) as project_title FROM project_scores GROUP BY group_number ORDER BY group_number ASC");

$selected_group = isset($_GET['group_id']) ? mysqli_real_escape_string($conn, $_GET['group_id']) : null;
$judges_scores = [];
$group_info = null;

if ($selected_group) {
    // Get all judge evaluations for this specific team
    $query = "SELECT s.*, u.username FROM project_scores s 
              JOIN users u ON s.judge_id = u.id 
              WHERE s.group_number = '$selected_group'";
    $judges_scores = $conn->query($query);
    
    // Get summary info for header
   // FIXED ✅
$info_res = $conn->query("SELECT MIN(project_title) as project_title, MIN(group_members) as group_members, AVG(total_score) as final_avg FROM project_scores WHERE group_number = '$selected_group' GROUP BY group_number");
    $group_info = $info_res->fetch_assoc();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Detailed Team Scores</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-slate-900 text-slate-100 flex h-screen overflow-hidden font-sans">

    <!-- Sidebar: Team Selection -->
    <div class="w-80 bg-slate-800 border-r border-slate-700 flex flex-col">
        <div class="p-6 border-b border-slate-700">
            <h2 class="text-xl font-bold text-blue-400"><i class="fas fa-users mr-2"></i> Graded Teams</h2>
        </div>
        <div class="flex-grow overflow-y-auto p-4 space-y-3">
            <?php while($row = $group_list->fetch_assoc()): ?>
            <a href="?group_id=<?= $row['group_number'] ?>" 
               class="block p-4 rounded-xl border transition-all <?= ($selected_group == $row['group_number']) ? 'bg-blue-600 border-blue-400 text-white shadow-lg' : 'bg-slate-750 border-slate-700 hover:border-slate-500' ?>">
                <div class="text-xs uppercase opacity-60">Group #<?= $row['group_number'] ?></div>
                <div class="font-bold truncate"><?= $row['project_title'] ?></div>
            </a>
            <?php endwhile; ?>
        </div>
        <div class="p-4 border-t border-slate-700"><a href="logout.php" class="text-red-400 text-sm font-bold hover:underline"><i class="fas fa-sign-out-alt mr-2"></i>Logout Admin</a></div>
    </div>

    <!-- Main Content: Detailed Dashboard -->
    <div class="flex-grow overflow-y-auto p-8">
        <?php if($selected_group && $group_info): ?>
            <!-- Team Header -->
            <div class="bg-slate-800 p-8 rounded-2xl border border-slate-700 mb-8 shadow-2xl relative overflow-hidden">
                <div class="relative z-10">
                    <h1 class="text-4xl font-black text-white mb-2"><?= $group_info['project_title'] ?></h1>
                    <p class="text-slate-400 italic mb-4">Members: <span class="text-blue-300"><?= $group_info['group_members'] ?></span></p>
                    <div class="flex items-center gap-4">
                        <div class="bg-blue-900 text-blue-200 px-4 py-2 rounded-lg text-sm font-bold">Group #<?= $selected_group ?></div>
                        <div class="bg-green-600 text-white px-6 py-2 rounded-lg font-black text-xl shadow-lg">FINAL AVERAGE: <?= number_format($group_info['final_avg'], 2) ?></div>
                    </div>
                </div>
                <i class="fas fa-award absolute -right-4 -bottom-4 text-9xl text-slate-700 opacity-20"></i>
            </div>

            <!-- Individual Judge Breakdown -->
            <h3 class="text-lg font-bold mb-6 text-slate-400 uppercase tracking-widest">Individual Judge Scores</h3>
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <?php while($js = $judges_scores->fetch_assoc()): ?>
                <div class="bg-white rounded-2xl overflow-hidden shadow-xl border-t-4 border-blue-500">
                    <div class="bg-slate-100 p-4 border-b flex justify-between items-center">
                        <span class="text-slate-800 font-black uppercase text-sm"><i class="fas fa-user-tie mr-2 text-blue-600"></i> <?= $js['username'] ?></span>
                        <span class="bg-blue-600 text-white px-4 py-1 rounded-full font-bold">Total: <?= $js['total_score'] ?></span>
                    </div>
                    <div class="p-6 text-slate-700">
                        <div class="grid grid-cols-2 gap-y-4 gap-x-8 text-sm">
                            <div class="flex justify-between border-b pb-1"><span>Requirements</span><span class="font-bold"><?= $js['score1'] ?></span></div>
                            <div class="flex justify-between border-b pb-1"><span>Tools/Methods</span><span class="font-bold"><?= $js['score2'] ?></span></div>
                            <div class="flex justify-between border-b pb-1"><span>Presentation</span><span class="font-bold"><?= $js['score3'] ?></span></div>
                            <div class="flex justify-between border-b pb-1"><span>Teamwork</span><span class="font-bold"><?= $js['score4'] ?></span></div>
                        </div>
                        <div class="mt-6 bg-slate-50 p-3 rounded border italic text-xs text-slate-500">
                            <strong>Comment:</strong> "<?= $js['comments'] ?: 'No comments provided.' ?>"
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <!-- Placeholder -->
            <div class="h-full flex flex-col items-center justify-center opacity-30">
                <i class="fas fa-th-large text-9xl mb-6"></i>
                <h2 class="text-2xl font-bold">Select a team from the sidebar to view full dashboard</h2>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>

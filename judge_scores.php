<?php 
include 'db.php'; 
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'judge') header("Location: login.php");

$j_id = $_SESSION['user_id'];
$res = $conn->query("SELECT * FROM project_scores WHERE judge_id = '$j_id' ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Scores</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 p-8">
    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800">My Evaluation History</h1>
            <div class="space-x-4">
                <a href="judge_form.php" class="bg-blue-600 text-white px-6 py-2 rounded shadow">Score New Project</a>
                <a href="logout.php" class="text-red-500 font-bold">Logout</a>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200">
            <table class="w-full text-left">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="p-4">Group #</th>
                        <th class="p-4">Project Title</th>
                        <th class="p-4">Members</th>
                        <th class="p-4 text-center">Score Given</th>
                        <th class="p-4">Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if($res->num_rows > 0): ?>
                        <?php while($row = $res->fetch_assoc()): ?>
                        <tr class="border-b hover:bg-blue-50 transition">
                            <td class="p-4 font-bold text-blue-700"><?= $row['group_number'] ?></td>
                            <td class="p-4 text-gray-800"><?= $row['project_title'] ?></td>
                            <td class="p-4 text-sm text-gray-500 italic"><?= $row['group_members'] ?></td>
                            <td class="p-4 text-center font-bold text-xl text-green-600"><?= $row['total_score'] ?></td>
                            <td class="p-4 text-gray-400 text-xs"><?= date("M d, Y", strtotime($row['created_at'])) ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="5" class="p-12 text-center text-gray-400 italic">No projects evaluated yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
<?php 
include 'db.php'; 
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'judge') header("Location: login.php");

$msg = ""; $status = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $members = mysqli_real_escape_string($conn, $_POST['members']);
    $group_num = mysqli_real_escape_string($conn, $_POST['group_num']);
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $comments = mysqli_real_escape_string($conn, $_POST['comments']);
    $j_id = $_SESSION['user_id'];

    // PHP Check: Has this judge already scored this team?
    $check = $conn->query("SELECT id FROM project_scores WHERE judge_id = '$j_id' AND group_number = '$group_num'");
    
    if ($check->num_rows > 0) {
        $msg = "Error: You have already submitted scores for Group #$group_num!";
        $status = "error";
    } else {
       // FIXED ✅
$s1 = (int)(($_POST['dev1'] ?? '') ?: ($_POST['acc1'] ?? 0));
$s2 = (int)(($_POST['dev2'] ?? '') ?: ($_POST['acc2'] ?? 0));
$s3 = (int)(($_POST['dev3'] ?? '') ?: ($_POST['acc3'] ?? 0));
$s4 = (int)(($_POST['dev4'] ?? '') ?: ($_POST['acc4'] ?? 0));
        $total = $s1 + $s2 + $s3 + $s4;

        $sql = "INSERT INTO project_scores (judge_id, group_members, group_number, project_title, score1, score2, score3, score4, total_score, comments) 
                VALUES ('$j_id', '$members', '$group_num', '$title', '$s1', '$s2', '$s3', '$s4', '$total', '$comments')";
        
        if ($conn->query($sql)) {
            $msg = "Success! Scores for Group #$group_num saved.";
            $status = "success";
        } else {
            $msg = "Database Error. Please try again.";
            $status = "error";
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Judge Evaluation</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        input:disabled { background: #f3f4f6; opacity: 0.5; cursor: not-allowed; }
        .score-input { width: 100%; text-align: center; border:none; padding: 15px; font-size: 1.2rem; }
    </style>
</head>
<body class="bg-gray-100 p-4 md:p-12 font-serif">
    <div class="max-w-5xl mx-auto flex justify-between mb-4">
        <a href="judge_scores.php" class="text-blue-600 font-bold underline">My Evaluated Projects</a>
        <a href="logout.php" class="text-red-600 font-bold underline">Logout</a>
    </div>

    <form id="evaluationForm" method="POST" class="max-w-5xl mx-auto bg-white border border-gray-400 shadow-2xl">
        <div class="bg-gray-300 p-3 text-center font-bold text-xl border-b border-gray-400 uppercase tracking-wider">Computer Science Project Evaluation</div>
        
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6 border-b border-gray-400">
            <div class="flex items-center"><span class="font-bold mr-4 w-32 text-gray-700">Members:</span> <input type="text" name="members" class="border-b border-gray-400 flex-grow focus:outline-none" required></div>
            <div class="flex items-center"><span class="font-bold mr-4 w-32 text-gray-700">Group #:</span> <input type="text" name="group_num" class="border-b border-gray-400 flex-grow focus:outline-none font-bold text-blue-800" required></div>
            <div class="col-span-full flex items-center"><span class="font-bold mr-4 w-32 text-gray-700">Project Title:</span> <input type="text" name="title" class="border-b border-gray-400 flex-grow focus:outline-none" required></div>
        </div>

        <table class="w-full border-collapse">
            <tr class="bg-gray-100 border-b-2 border-gray-400 text-gray-800">
                <th class="border-r border-gray-400 p-4 text-left w-2/5">Criteria</th>
                <th class="border-r border-gray-400 p-4 w-1/4">Developing (0-10)</th>
                <th class="p-4 w-1/4">Accomplished (11-15)</th>
            </tr>
            <?php 
            $criteria = [1=>"Articulate requirements", 2=>"Appropriate tools & methods", 3=>"Oral presentation", 4=>"Functioned well as a team"];
            foreach($criteria as $id => $label): ?>
            <tr class="border-b border-gray-400 h-16">
                <td class="p-4 border-r border-gray-400 font-bold text-gray-700 bg-gray-50"><?= $label ?></td>
                <td class="border-r border-gray-400 p-0"><input type="number" name="dev<?= $id ?>" min="0" max="10" class="score-input" data-pair="acc<?= $id ?>"></td>
                <td class="p-0"><input type="number" name="acc<?= $id ?>" min="11" max="15" class="score-input" data-pair="dev<?= $id ?>"></td>
            </tr>
            <?php endforeach; ?>
            <tr class="bg-gray-200 font-bold border-b border-gray-400 text-xl">
                <td class="p-4 border-r border-gray-400 text-right">Total Grade</td>
                <td colspan="2" class="p-4 text-center text-blue-700" id="totalBox">0</td>
            </tr>
        </table>

        <div class="p-6">
            <div class="mb-4"><span class="font-bold mr-2">Judge:</span> <span class="border-b border-gray-400 px-6 font-bold text-blue-800 italic uppercase"><?= $_SESSION['username'] ?></span></div>
            <textarea name="comments" placeholder="Add comments here..." class="w-full border border-gray-400 p-4 h-24 focus:ring-2 focus:ring-blue-200 outline-none"></textarea>
            <div class="mt-8 text-center"><button type="submit" id="submitBtn" class="bg-blue-800 text-white px-16 py-4 rounded-lg font-bold shadow-xl hover:bg-blue-900 transition-all uppercase tracking-widest">Submit Final Score</button></div>
        </div>
    </form>

    <script>
        // Disable column logic (Developing vs Accomplished)
        document.querySelectorAll('.score-input').forEach(input => {
            input.addEventListener('input', () => {
                const pair = document.getElementsByName(input.dataset.pair)[0];
                pair.disabled = input.value !== "";
                let total = 0;
                document.querySelectorAll('.score-input').forEach(i => { if(i.value) total += parseInt(i.value); });
                document.getElementById('totalBox').innerText = total;
            });
        });

        // Prevention of double clicking the button
        document.getElementById('evaluationForm').onsubmit = function() {
            document.getElementById('submitBtn').disabled = true;
            document.getElementById('submitBtn').innerText = "Processing Score...";
        };

        // SweetAlert Feedback
        <?php if($msg): ?>
        Swal.fire({
            title: '<?= $status == "success" ? "Done!" : "Wait!" ?>',
            text: '<?= $msg ?>',
            icon: '<?= $status ?>',
            confirmButtonColor: '#1e40af'
        }).then(() => {
            <?php if($status == "success"): ?> window.location.href = 'judge_scores.php'; <?php endif; ?>
        });
        <?php endif; ?>
    </script>
</body>
</html>
